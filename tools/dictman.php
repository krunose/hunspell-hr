<?php


/*

	Kruno, krunose at gmx, May 2018.

	last update: February 2019.

INTRODUCTION

	This script is made for creating wordlist from Croatian dictionary version v2.1 and
	it's not guaranteed it will work with any other version or dictionary.

	Words are feed in as multi-byte strings because č, ć, ž, đ, š. So dictionary v2.1
	is UTF-8 encoded, and this script is set this way. For using this script you need
	to install

		php-mbstring,

	at least that's what is called on GNU/Linux Ubuntu 20.04. You can install it with

		sudo apt-get install php-mbstring

	The only thing you need to check is that dictionary files are named "hr_HR.dic" and
	"hr_HR.aff" and that they are in folder _one level up_ relative to this script.
	Optionaly, you can set path to .dic and aff. in line number 605 (last line of this script).

	If you downloaded GitHub repository as ZIP, just unpack and run script as
	on GNU/Linux as

	php dictman.php > wordlist

	and result will bi written id text file wordlist. It will complite in 10 to 20 seconds.


SUPPORTED

	Currently script make word list from
		- words without flags
		- words with classes when
			- prefixing
			- suffixing
			- prefixing and suffixing
			- support 'N' in class heading: SFX AA N 1 (partially, not tested, not sure if I got it right)


NOT IMPLEMENTED (current dictionary not using this features)

	Well, there's a yet a lot to do. Firstly, this should be made a proper tool so it's more
	than just a quick bodge.

	Features that should be implemented	

		- NEEDAFFIX
		- two fold suffix stripping
		- CIRCUMFIX
		- COMPLEXPREFIXES (not needed for Croatian)
		- don't know how to deal with COMPOUND feature when it comes to generating word lists.
		- make function to do alias compression if dictionary is not using it (and there's a  need for it,
			see Hunspell4.pdf on the Internet) 


MORE SUITABLE TOOLS

	If you want something with GUI, more polished and maintained, check PTG on

		http://marcoagpinto.cidadevirtual.pt/proofingtoolgui.html

	as it may be more fitted for the task.


*/


mb_internal_encoding("UTF-8");


class dictman {


	private $affixList = array();
	private $affixRules = array();
	private $specFlags = array(

		"NEEDAFFIX" => "",
		"CIRCUMFIX" => "",
		"KEEPCASE" => "",
		"NOSUGGEST" => ""

	);


	private function collectSpecFlags($line) {

		$explLine = explode(" ", preg_replace('/\s+/', ' ', $line));

		if(array_key_exists($explLine[0], $this->specFlags)) {

			$this->specFlags[$explLine[0]] = $explLine[1];

		}

		return $this->specFlags;

	}




	private function collectClasses($affixFile, $affixList, $affixRules) {

		$this->affixList = $affixList;
		$this->affixRules = $affixRules;

		$handle = fopen($affixFile, "r");

		while(($line = fgets($handle, 2048)) !== false) {

		$line = trim($line);

		$this->collectSpecFlags($line);

		$affType = mb_substr($line, 0, 3);

			if($affType == "SFX" || $affType == "PFX" ) {

				$className = mb_substr($line, 4, 2);

				$this->affixRules[$className]['info']['affType'] = trim($affType);

				$crossProd = mb_substr($line, 7, 1);

				if($crossProd == "Y" || $crossProd == "N") {

					if(mb_strpos($line, "#") !== false) {

						$explodeLine = explode("#", $line);

						$classLength = mb_substr(trim($explodeLine[0]), -2, 2);

					} else {

						$classLength = mb_substr($line, -2, 2);

					}

					$this->affixRules[$className]['info']['crossProd'] = trim($crossProd);
					$this->affixRules[$className]['info']['classLength'] = trim($classLength);

				} else {

					$this->affixRules[$className]['rules'][] = mb_substr($line, 7, mb_strlen($line));

				}

			} else if($affType == "AF ") {

				$explodeLine = explode("#", $line);
				$explodeLine[0] = trim($explodeLine[0]);
				$this->affixList[] = mb_substr($explodeLine[0], 3, mb_strlen($explodeLine[0]));

			}

		}


		fclose($handle);

	}




	private function returnListOfClasses($affixNum) {

		$askedForClasses = array();

		$tmpAskedForClasses = "";


		// explode classes in list of letters (AAABAC >> [0] => A, [1] => A, [2] => A, [3] => B, [4] => A, [5] => C >> [0] => AA, [1] => AB, [2] => AC)
		$classesList = preg_split('//u', $this->affixList[$affixNum], null, PREG_SPLIT_NO_EMPTY);

			$i = 0;

		foreach($classesList as $letterInList) {

			if($i < 1) {

				$tmpAskedForClasses .= $letterInList;

				$i++;

			} else {

				$tmpAskedForClasses .= $letterInList;

				$askedForClasses[] = $tmpAskedForClasses;

				$tmpAskedForClasses = "";

				$i = 0;

			}

		}

		return $askedForClasses; // array([0] => AA, [1] => AB, [2] => AC)

	}




	private function dealWithCondition($condition) {

		if($condition == ".") {

			$condRes['negate'] = 0;
			$condRes[] = "";

			return $condRes;

		} else {

			$condRes = array();

			if(mb_strpos($condition, "]") !== false) {

				$expldCond = explode("]", $condition);

				$fixedCondPart = $expldCond[1];

				$varCondPart = mb_substr($expldCond[0], 1, mb_strlen($expldCond[0]));

				if(mb_substr($varCondPart, 0, 1) == "^") {

					$varCondPart = mb_substr($varCondPart, 1, mb_strlen($varCondPart));

					$condRes['negate'] = 1;

				} else {

					$condRes['negate'] = 0;

				}

				$varCondParts = preg_split('//u', $varCondPart, null, PREG_SPLIT_NO_EMPTY);

				foreach($varCondParts as $condPart) {

					$condRes[] = $condPart . $fixedCondPart;

				}

			} else {

				if(mb_substr($condition, 0, 1) == "^") {

					$condition = mb_substr($condition, 1, mb_strlen($condition));

					$condRes['negate'] = 1;

				} else {

					$condRes['negate'] = 0;

				}

				$condRes[0] = $condition;

			}

			return $condRes;

		}

	}




	private function applyAffixation($word, $rule, $affixType) {

		$parts = explode(" ", preg_replace('/\s+/', ' ', $rule));

		$removeLength = mb_strlen($parts[0]);
		$wordLength = mb_strlen($word);

		if($parts[1] == "0") {

			$parts[1] = "";

		}

		$conditions = $this->dealWithCondition($parts[2]);

		if($conditions['negate'] == 1) {

			$apply = true;

			foreach($conditions as $condKey => $condition) {

				if(is_numeric($condKey)) {

					$condLength = mb_strlen($condition);

					if($affixType == "SFX") {

						if(mb_substr($word, -($condLength), $condLength) == $condition) {

							$apply = false;

						}

					} else {

						if(mb_substr($word, 0, $condLength) == $condition) {

							$apply = false;

						}

					}

				}

			}

			if($apply == true) {

				if($affixType == "SFX") {

					if($parts[0] != "0") {

						if(mb_substr($word, -($removeLength), $removeLength) == $parts[0]) {

							return mb_substr($word, 0, -($removeLength)) . $parts[1] . "\n";

						}

					} else {

						return $word . $parts[1]. "\n";

					}

				} else {

					if($parts[0] != "0") {

						if(mb_substr($word, 0, $removeLength) == $parts[0]) {

							return $parts[1] . mb_substr($word, $removeLength, $wordLength) . "\n";

						}

					} else {

						return $parts[1] . $word . "\n";

					}

				}

			}

		} else {

			$apply = false;

			foreach($conditions as $condKey => $condition) {

				if(is_numeric($condKey)) {

					$condLength = mb_strlen($condition);

					if($affixType == "SFX") {

						if(mb_substr($word, -($condLength), $condLength) == $condition) {

							$apply = true;

						}

					} else {

						if(mb_substr($word, 0, $condLength) == $condition) {

							$apply = true;

						}

					}

				}

			}

			if($apply == true) {

				if($affixType == "SFX") {

					if($parts[0] != "0") {

						if(mb_substr($word, -($removeLength), $removeLength) == $parts[0]) {

							return mb_substr($word, 0, -($removeLength)) . $parts[1] . "\n";

						}

					} else {

						return $word . $parts[1] . "\n";

					}

				} else {

					if($parts[0] != "0") {

						if(mb_substr($word, 0, $removeLength) == $parts[0]) {

							return $parts[1] . mb_substr($word, $removeLength, $wordLength) . "\n";

						}

					} else {

						return  $parts[1] . $word . "\n";

					}

				}

			}

		}

	}




	public function makeWordlist($affixFile, $dictFile, $sepByClass) {


		$this->collectClasses($affixFile, $this->affixList, $this->affixRules);

		$handle = fopen($dictFile, "r");

		while(($line = fgets($handle, 20148)) !== false) {

			$line = trim($line);

			if(mb_strpos($line, "/")) {

				$explodeLine = explode("/", $line);

				$affixNum = trim($explodeLine[1]);
				$word = trim($explodeLine[0]);

				$classes = $this->returnListOfClasses($affixNum);



				$echoDictEntry = true;

				foreach($classes as $class) {

						$tmpKey = array_search($class, $this->specFlags);

					if($tmpKey == "NEEDAFFIX" || $tmpKey == "CIRCUMFIX") {

						$echoDictEntry = false;

					}

				}


				// if no other operations should be done on this particular entry
				if($echoDictEntry == true) {

					echo $word . "\n";

				}


				$crossProd = array(

					'PFX' => array(),
					'SFX' => array()

				);


				foreach($classes as $class) {

					if(!in_array($class, $this->specFlags)) {

						// if class has crossProd == "Y", collect for further operations (applying prefixes and suffixes)
						if($this->affixRules[$class]['info']['crossProd'] == "Y") {

							$crossProd[$this->affixRules[$class]['info']['affType']][] = $class;

						// if crossProd == "N", echo it out as no other operation is needed
						} else {

							$rules = $this->affixRules[$class]['rules'];

							foreach($rules as $rule) {

								echo $this->applyAffixation($word, $rule, $this->affixRules[$class]['info']['affType']);

							}

						}

					} // here in else-statemen I can deal with NEEDAFFIX or such (but I already check for this in $echoDictEntry and $tmpKey so this should be written better and not to check this twice. This is just preventing errors in terminal while running this script.

				}



				// no two fold suffixes taken into consideration
				// if no need for prefixes
				if(empty($crossProd['PFX'])) {

					foreach($crossProd['SFX'] as $class) {

						$rules = $this->affixRules[$class]['rules'];

						foreach($rules as $rule) {

							echo $this->applyAffixation($word, $rule, "SFX");

						}

					}

				// if prefixes should be applied
				} else {

					foreach($crossProd['PFX'] as $class) {

						foreach($this->affixRules[$class]['rules'] as $rule) {

							$affixedWord = $this->applyAffixation($word, $rule, 'PFX');

							echo $affixedWord;

							foreach($crossProd['SFX'] as $suffixClass) {

								$suffixRules = $this->affixRules[$suffixClass]['rules'];

								foreach($suffixRules as $suffixRule) {

									// here I should check for CIRCUMFIX flag and echo only
									// suffixed and prefixed form of dictionary entry if CIRCUMFIX flag applicable

									// echo suffixed form of dictionary entry
									echo $this->applyAffixation($word, $suffixRule, 'SFX');

									// echo suffixed and prefixed form of dictionary entry
									echo $this->applyAffixation(trim($affixedWord), $suffixRule, 'SFX');

								}

							}

						}

					}

				}

			} else {

				echo $line . "\n";

			}
			
			if($sepByClass === true) {

				echo "\n";

			}

		}

		fclose($handle);

	}


}



$dictman = new dictman;

	// case last argument is true, you get word1-affixed \n\n word2-affixed, if it's false then you get word1-affixed\word2-affixed
	echo $dictman->makeWordlist("../hr_HR.aff", "../hr_HR.dic", false);



?>
