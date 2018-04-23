<?php


/*

	Kruno, krunose at gmx, April 2018.


INTRODUCTION

	This script is made for creating wordlist from Croatian dictionary version 2.1.1 and
	it's not guaranteed it will work with any other version as not all Hunspell's features are
	supported here.

	This is completely spaghetti code as I needed a tool for making wordlist asap so I came up
	with this. Many aspects of it is not planned but result of fixing bugs and adding functionality
	on the spot many things can be done more efficiently. I'm not a programmer by no means. Heck,
	I did it in PHP, that's says a lot.

	Any improvements are more then welcome, but I would like to learn from them so any (potential)
	patch should be followed with some explanation.

	Script works really well, but it's only tested with Croatian dictionary version 2.1.1. From
	53719 dictionary entries for language like Croatian with many many prefixes and suffixes (just
	take a look in hr_HR.aff), script is able to produce 1069811 word forms in about 25 seconds;
	and there's plenty of room for improvements and optimizing the code.


DESCRIPTION

	Script work with UTF-8 encoded dictionary and affix file. Currently script only generates
	wordlist, but it's written as class so it has potential to become a tool for manipulating
	Hunspell's dictionary files, thus 'dictman': dictionary manipulation.

	Although it's written for Croatian dictionary version 2.1.1, it should work with any other
	UTF-8 encoded dictionary. If dictionary is encoded differently, just change first line of the
	script to reflect needed encoding. Just make sure both dictionary and affix file has same
	encoding (was not case with Croatian dictionary).


SUPPORTED

	Currently script supports these Hunspell's features
		- words without flags
		- words with class flags
			- prefixing
			- suffixing
			- prefixing and suffixing


NOT SUPPORTED

	Well, there's a yet a lot to do. Firstly, this should be written as proper class. Somebody
	more skillful should rewrite the whole thing. I'll do with when I learn more of programming.

	What's not supported is
		- CIRCUMFIX flag
		- two fold suffix stripping

	Everything needed for implementing this features is already there, but some planning and 
	rewriting is needed. This is my real class (beyond 'Hello World') so it's clearly amateurish
	and childish and thus limiting in it's full potential.


TODO

	Rewrite the whole thing as proper PHP class using constructor for taking CLI arguments as this
	should become tool for manipulating Hunspell's dictionaries and CLI arguments are first step.

	Adding function (accessible trough CLI arguments) for checking for duplicates in dictionary file.

	Documenting the whole thing and documenting every function separately as it wont be long and
	I'll not even remember why certain things are done in certain way. Best would be to make a plan,
	a list of features which such tool should have and then write it accordingly. With this I have
	COMPOUND feature particularly in mind.

	Rewrite core functions as where written on the spot as result of fixing bugs and lack of
	functionality so many things here are quick fix, spaghetti code.

	Make script work without alias compression (see Hunspell4.pdf on the Internet)

	Add feature to automatically add alias compression part is dictionary is not using it but
	would like to.

	Add a way to inspect particular word or class for more convenient way to add new words to
	dictionary.

	Everything else I can think of and I'm able to figure out how to code it in.


LIMITATIONS

	Rewrite this so script can detect is dictionary using AF (alias compression) feature or not.
	For now, dictionary needs an alias compression so this can be limitation for many. luckily,
	feature can be added that can mimic alias compression and make script work regardless of how
	actual dictionary is structured.


MORE SUITED TOOLS

	If you need something good and reliable for manipulating dictionary, check PGT on

		http://marcoagpinto.cidadevirtual.pt/proofingtoolgui.html

	as it's more mature than this one-off script.

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



	public function makeWordlist($affixFile, $dictFile) {


		$this->collectClasses($affixFile, $this->affixList, $this->affixRules);

		$handle = fopen($dictFile, "r");

		while(($line = fgets($handle, 20148)) !== false) {

			$line = trim($line);

			if(mb_strpos($line, "/")) {

				$explodeLine = explode("/", $line);

				$affixNum = trim($explodeLine[1]);
				$word = trim($explodeLine[0]);

				$classes = $this->returnListOfClasses($affixNum);

				// echo dictionary entry only if there's no NEEDAFFIX flag assigned to it
				if(!in_array($this->specFlags, $classes)) {

					echo $word . "\n";

				}


				$crossProd = array(

					'PFX' => array(),
					'SFX' => array()

				);


				foreach($classes as $class) {

					if($this->affixRules[$class]['info']['crossProd'] == "Y" && !in_array(key($this->affixRules[$class]), $this->specFlags)) {

						$crossProd[$this->affixRules[$class]['info']['affType']][] = $class;

					}

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

		}

		fclose($handle);

	}


}



$dictman = new dictman;


echo $dictman->makeWordlist("hr_HR.aff", "hr_HR.dic");



?>