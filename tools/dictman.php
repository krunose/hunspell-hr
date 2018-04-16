<?php

/*
Kruno; kruno.se@gmx.com; april, 10. 2018.

Version 0.2

[UPDATE 2018-04-16]
	Seams that script is not echoing out all possible forms. Use Pinto's PTG tool for generating wordlist.
		Download tool at least v3.0 build 125 from http://marcoagpinto.cidadevirtual.pt/proofingtoolgui.html#downloads


This is very simple script to create wordlist from Hunspell dictionary. It's written solely for Croatian dictionary version 2.1.1 and you _should not_ use it for anything else as it's not intended to be used with anything else.

Script can not replace Unmunch or similar tools, but since many dictionaries have simple structure (only suffixes), it might me helpful for generating wordlist for more then just Croatian dictionary version 2.1.1 for Hunspell. You can try use it only if your dictionary uses only one fold suffixes and AF. Supporting cross products (combining prefixes and suffixes) and make it compatible with dictionaries without AF is in progress.

Keep in mind that Croatian dictionary 2.1.1 uses AF feature: word/221 → AF AAABAC	# 221, check hr_HR.aff from line 299. This might mean that script will not work for dictionaries not using this feature. It should not be too hard to add feature to this script so it run without actually using AF in dictionary (fake AF internally). But that's for future versions of this script.

Script has many, many limitations as it's written as quick, dirty and simple way to extract wordlist from Hunspell dictionary. For now it
	- only support suffixes (no prefixes)
	- [fixed, but needs rewrite (function applyAffixes and lines from 333 to 342)] it does _not_ handle conditional suffixes well ([^abc]d; [abc]d)
	- supports only one fold suffixes (and prefixes). Two fold stripping is yet to be added
	- no compounding (or any anything like that for that matter)
	- it's mash of functions and procedural code, needs to be rewritten as proper class
	- it's written in PHP as I'm not proficient in anything else (not even PHP), maybe Python would be more suitable (maybe even GUI)

Backup everything you have and can before using this script.

Made this so I can use existing wordlist as base for rewriting Croatian dictionary from scratch and to use this wordlist as base for updating hyphenation rules for Croatian. If you can help, please, don't just make complete CLI application and rewrite everything without any explanation or documentation. I would very much appreciate any opportunity to learn pretty much anything about programming.

*/


	mb_internal_encoding("UTF-8");



	function checkIfNeg(array $arrCond) {

		if($arrCond[0] == "^") {

				return 1;

			} else {

				return 0;

			}

		return $arrCond;

	}




	// returns array of conditions: [abc]d >> array(['negate'] => 0, [1] => ad, [2] => bd, [3] => cd). see function checkIfNeg($array)
	function returnConditions($condition) {

		$condRes = array();

		if(mb_strpos($condition, "[") !== false) {

			if(mb_strpos($condition, "[") == 0) {

				$fixedAtEnd = true;

				$explodeCond = explode("]", ltrim($condition, "["));

				$conds = preg_split('//u', trim($explodeCond[0]), null, PREG_SPLIT_NO_EMPTY);

				$condRes['negate'] = checkIfNeg($conds);

				$fixedPart = $explodeCond[1];

			} else {

				$fixedAtEnd = false;

				$explodeCond = explode("[", rtrim($condition, "]"));

				$conds = preg_split('//u', trim($explodeCond[1]), null, PREG_SPLIT_NO_EMPTY);

				$condRes['negate'] = checkIfNeg($conds);

				$fixedPart = $explodeCond[0];

			}

			foreach($conds as $cond) {

				if($fixedAtEnd === true) {

					$condRes[] = $cond . $fixedPart;

				} else {

					$condRes[] = $fixedPart . $cond;

				}

			}

		} else {

			if(mb_substr($condition, 0, 1) == "^") {

				$condRes['negate'] = 1;

			} else {

				$condRes['negate'] = 0;

			}

			$condRes[] = $condition;

		}

		return $condRes;

	}




	// return word for condition met by removing what needs to be removed and adding what has to be added
	function applyAffixes($affix, $word, $add, $remove, $conditions, $wordSep) {

		if($affix == "SFX") {

			$apply = true;

			foreach($conditions as $condKey => $condition) {

				if($condition == "." && is_numeric($condKey)) {

					echo trim($word) . $add . $wordSep;

				} else if($condition != "." && is_numeric($condKey)) {

					if($conditions['negate'] == 0) {

						if(mb_substr($word, -(mb_strlen($condition)), mb_strlen($condition)) != $condition) {

							$apply = false;

						} else {

							continue;

						}

					} else {

						if(mb_substr($word, -(mb_strlen($condition)), mb_strlen($condition)) == $condition) {

							$apply = false;

						} else {

							continue;

						}

					}	

				}

			}

			// if condition is '.', we already echoed that out, no need to do it again, echo again only words that did not have '.' as condition (clumsy code)
			if($apply == true && $condition != ".") {

				if($remove != 0 || $remove != "0") {

					// we can not just strip last part of a word if that word is not ending with what class whats to strip. Remove ending of only those word that actually end on substring that should be stripped. for a ski a, word should actually end on 'a' to match pattern, but it also need to end on 'a' even if pattern checking passes
					// if rule is 'sfx a ski [^aeiou]' for word 'čovjek', pattern checking passes as word 'čovjek' is not ending with 'a', 'e', 'i', 'o', 'u', but we can not just strip last character 'k' and add ski (čovjeski) as rule is asking for stripping 'a', so we first need to make sure that word is ending on 'a' regardless of pattern
					if(mb_substr(trim($word), -(mb_strlen($remove)), mb_strlen($remove)) == $remove) {

						echo mb_substr(trim($word), 0, -(mb_strlen($remove))) . $add . $wordSep;

					}

				} else {

					echo $word . $add . $wordSep;

				}

			}

		} else {

			// prefix needs to be added

		}

	}





$dictFile = "hr_HR.dic";
$affixFile = "hr_HR.aff";

$affixList = array();
$affixRules = array();

$handle = fopen($affixFile, "r");


// read aff file line by line and sort classes in $affixRules and AF rules in $affixList.
while(($line = fgets($handle, 2048)) !== false) {

	$line = trim($line);

	$affType = mb_substr($line, 0, 3);

	if($affType == "SFX" || $affType == "PFX" ) {

		$className = mb_substr($line, 4, 2);

		$affixRules[$className]['info']['affType'] = trim($affType);

		$crossProd = mb_substr($line, 7, 1);

		if($crossProd == "Y" || $crossProd == "N") {

			if(mb_strpos($line, "#") !== false) {

				$explodeLine = explode("#", $line);

				$classLength = mb_substr(trim($explodeLine[0]), -2, 2);

			} else {

				$classLength = mb_substr($line, -2, 2);

			}

			$affixRules[$className]['info']['crossProd'] = trim($crossProd);
			$affixRules[$className]['info']['classLength'] = trim($classLength);

		} else {

			$affixRules[$className]['rules'][] = trim($line);

		}

	} else if($affType == "AF ") {


		$explodeLine = explode("#", $line);
		$explodeLine[0] = trim($explodeLine[0]);
		$affixList[] = mb_substr($explodeLine[0], 3, mb_strlen($explodeLine[0]));

	}

}


fclose($handle);




	function returnListOfClasses($affixNum) {

		global $affixList;
		$askedForClasses = array();

		$tmpAskedForClasses = "";


		// explode classes in list of letters (AAABAC >> [0] => A, [1] => A, [2] => A, [3] => B, [4] => A, [5] => C >> [0] => AA, [1] => AB, [2] => AC)
		$classesList = preg_split('//u', $affixList[$affixNum], null, PREG_SPLIT_NO_EMPTY);

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





	// assuming argument passed is array $affixClasses in form $affixClasses[AA][rules][$i]
	function returnAffixationParts($rule) {

		$parts = array();

		$explodeForClass = explode("+", $rule);

		$explodeForClass[0] = trim($explodeForClass[0]);
		$explodeForClass[0] = mb_substr($explodeForClass[0], 7, mb_strlen($rule));

		$line = trim(preg_replace('/\s+/', ' ', $explodeForClass[0]));

		$explodeParts = explode(" ", $line);

		$parts['remove'] = $explodeParts[0];

		if($explodeParts[1] == "0") {

			// if add part of rule is 0, we don't want to add that to word, it means 'nothing' so we set that here
			$parts['add'] = "";

		} else {

			$parts['add'] = $explodeParts[1];

		}

		$parts['condition'] = $explodeParts[2]; // on this, function returnConditions($parts['condition']) should be applied letter on

		return $parts;		

	}



	function applyRulesToWord($line) {

			global $affixList;
			global $affixRules;

			$line = trim($line);

				if(mb_strpos($line, "/")) {

					$explodeLine = explode("/", $line);

					$classesNeeded = returnListOfClasses($explodeLine[1]);

			/* */

					$crossProdClasses = array();

			/* */


					// for echoing part from dictionary, but actually we need to check if needaffix is in place and echo this only if there's no needaffix flag, script just echoes things that are affixed, not dictionary entry
					echo $explodeLine[0] . "\n";

					foreach($classesNeeded as $classNeededKey => $classNeeded) {

						if($affixRules[$classNeeded]['info']['crossProd'] == "Y") { /**/

							$crossProdClasses[$affixRules[$classNeeded]['info']['affType']][] = $affixRules[$classNeeded];

						} /**/

						foreach($affixRules[$classNeeded]['rules'] as $ruleKey => $rule) {

							echo applyAffixes($affixRules[$classNeeded]['info']['affType'], trim($explodeLine[0]), returnAffixationParts($rule)['add'], returnAffixationParts($rule)['remove'], returnConditions(returnAffixationParts($rule)['condition']), "\n");

						}

					}


				if(count($crossProdClasses['PFX'] >= 1) && count($crossProdClasses['SFX'] >= 1)) {

					// script trows error for in next line, but I can't really tell why... same for previous line. probably everything is OK when it comes to results but it tries to loop something which can't
					foreach($crossProdClasses['PFX'] as $crossProdPfxClass) {

						foreach($crossProdPfxClass['rules'] as $cppc) {

							// TODO: ADD WRITE PART FOR PREFIXES IN FUNCTION applyAffixes so this is return dynamically
							// actually this should be done trough 'applyAffix' function but only suffix part is written, no prefix part so instead doing it by function, I hard coded this as it's primarily used for Croatian dictionary and 'naj' is only prefix in that dictionary
							$pfxPart = 'naj';
							// same as hard coding $pfxPart - script is only echoing affixed forms, ignoring actual dictionary entry. This is echoing dictionary entry, but this should be done with function which should check if needaffix is in place and echo out this only if there's no needaffix flag set for this entry
							echo $pfxPart . $explodeLine[0] . "\n";

							foreach($crossProdClasses['SFX'] as $crossProdSfxClass) {

								foreach($crossProdSfxClass['rules'] as $rule) {

									echo applyAffixes($crossProdSfxClass['info']['affType'], $pfxPart . trim($explodeLine[0]), returnAffixationParts($rule)['add'], returnAffixationParts($rule)['remove'], returnConditions(returnAffixationParts($rule)['condition']), "\n");

								}

							}

						}

					}

				}

			} else {

				// for words not having classes assigned to it. this should respect $wordSep but it does not for now
				echo $line . "\n";

			}

	}




$handle = fopen($dictFile, "r");

while(($line = fgets($handle, 2048)) !== false) {

	$line = trim($line);

	applyRulesToWord($line);

}

fclose($handle);

// applyRulesToWord("nadražujući/358");

?>
