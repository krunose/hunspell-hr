<?php


/*

Kruno; kruno.se@gmx.com; march 2018.

This is very simple script to create wordlist from Hunspell dictionary.

What you need to change in order for list to work is change first two variables so they reflect valid paths and file names of dictionary file and affix file. This is not intended to replace unmunch or other such tools, but since lot of dictionaries has simple structure (only suffixes, no nosuggest, compound and such), it might be helpful for more then Croatian dictionary version 2.1.1.

Keep in mind that Croatian dictionary 2.1.1 uses AF feature: word/221 → AF AAABAC	# 221, check hr_HR.aff from line 299. This might mean that script will not work for dictionaries not using this feature.

Script has many, many limitations as it's written as quick, dirty and simple way to extract wordlist from Hunspell dictionary. For now it

	- only support suffixes (no prefixes)
	- it does _not_ handle conditional suffixes well ([^abc]d; [abc]d)
	- supports only one fold affixes (no prefixes)
	- no compounding (or any anything like that for that matter)
	- it's procedural code so adding or improving will probably require complete rewrite
	- it's written in PHP as I'm not proficient in anything else (not even PHP)
	- everything else from TODO list


TODO (in that order)

	- deal with most common classes not reserved with prefixes, suffixes or compounding (nosuggest, needaffix, circumfix ...)
	- support prefixes
	- support two folds (suffixes and prefixes (complexprefixes))
	- support compounds (which are not using regular expressions)
	- write this as PHP class as CLI program that can
			- return list of words written in dictionary file (base forms)
			- return wordlist with all forms of every word
			- info about specific word (php dictman.php wordform word/323)
					- returns which classes are hiding under 323 (php dictman.php wordform --list-classes word/323)
					- returns morphological info (php dictman.php wordform --morphology word/323)
					- return all wordforms (php dictman.php wordform --list-forms word/323)
			- suggest most probable affix for word to be added (php dictman.php wordform --suggest newWord)
					by comparing ending of word to be added with existing in dictionary and return all possible solutions sorted by most probable on top
			- 
			- return some statistics about dictionary in general (how many words, how many wordforms in general, how many classes... use your imagination)
	- rewrite this in more suitable language as Python (maybe even C++, but Python might attract more hackers and wannabe programmers like myself)


Backup everything you have and can before using this script.

Wordlist on https://github.com/krunose/hr-hunspell does not have words with class UB (superlative adjectives with declination) and roman numerals which have class ZP (nosuggest, nosuggest, needaffix, circumfix).

Made this so I can use existing wordlist as base for rewriting Croatian dictionary from scratch and to use this wordlist as base for updating hyphenation rules for Croatian. If you can help, please, don't just make complete CLI application and rewrite everything without any explanation or documentation. I would very much appreciate any opportunity to learn pretty much anything about programming.

*/


mb_internal_encoding("UTF-8");

$dictFile = "hr_HR.dic";
$affixFile = "hr_HR.aff";


$handle = fopen($affixFile, "r");

$affixNums = array();
$classes = array();




while(($line = fgets($handle)) !== false) {


		if(mb_substr($line, 0, 2) == "AF") {
	
			$affixNums[] = trim($line);


		} else if(mb_substr($line, 0, 3) == "SFX") {

			$classes[mb_substr($line, 4, 2)][] = trim($line);

		} else {

			continue;

		}

	}

fclose($handle);



	unset($affixNums[0]);

	foreach($classes as $classKey => $class) {

		unset($class[0]);
		$classes[$classKey] = $class;

	}


$handle = fopen($dictFile, "r");


while(($line = fgets($handle)) !== false) {

	if(mb_strpos($line, "/") !== false) {

		$explodeLine = explode("/", $line);

		$affixNum = $explodeLine[1];
		$wordBase = $explodeLine[0];

		$classesNeeded = $affixNums[trim($affixNum)];

		$explodeClassesNeeded = explode("#", mb_substr($classesNeeded, 3, mb_strlen($classesNeeded)));

		unset($explodeClassesNeeded[1]);

		$classesNeeded = preg_split('//u', trim($explodeClassesNeeded[0]), null, PREG_SPLIT_NO_EMPTY);

 
 		$neededClass = "";
 		$neededClasses = array();

		$i = 0;

		foreach($classesNeeded as $class) {

			if($i < 1) {

				$neededClass .= $class;

				$i++;

			} else {

				$neededClass .= $class;

				$neededClasses[] = $neededClass;

				$neededClass = "";

				$i = 0;

			}


		}

		foreach($neededClasses as $neededClass) {

			foreach($classes[$neededClass] as $key => $classLine) {

				$classLine = mb_substr(trim($classLine), 7, mb_strlen($classLine));

				$explodeClassLine = explode(" ", $classLine);

				$actuallClassRules = array();

				foreach($explodeClassLine as $explodedClassLine) {

					if($explodedClassLine != "") {

						$actuallClassRules[] = $explodedClassLine;

					}

				}

				$condition = $actuallClassRules[0];
				$add = $actuallClassRules[1];
				$remove = $actuallClassRules[2];

				if(mb_strpos($condition, "[") !== false) {

					$condition = explode("]", ltrim($condition, "["));

					$fixedPart = $condition[1];
					$varPart = $condition[0];

					$explodeVarPart = preg_split('//u', trim($explodeClassesNeeded[0]), null, PREG_SPLIT_NO_EMPTY);

					if(mb_substr($explodeVarPart[0], 0, 1) == "^") {

						$negate = true;

						unset($explodeVarPart[0]);

					} else {

						$negate = false;

					}

					foreach($explodeVarPart as $explodedVarPart) {

						$condition = $explodedVarPart . $fixedPart;

						if($negate == true) {

								if(mb_substr($wordBase, -(mb_strlen($condition)), mb_strlen($condition)) != $condition) {

									echo mb_substr($wordBase, -(mb_strlen($remove)), mb_strlen($remove)) . $add . "\n";

								}

						} else {

							if(mb_substr($wordBase, -(mb_strlen($condition)), mb_strlen($condition)) == $condition) {

								echo mb_substr($wordBase, -(mb_strlen($remove)), mb_strlen($remove)) . $add . "\n";

							}

						}

					}

				} else {

					if(mb_substr($wordBase, -(mb_strlen($condition)), mb_strlen($condition)) == $condition) {

						echo rtrim($wordBase, $remove) . $add . "\n";

					}

				}

			}

		}

	} else {

		echo $line;

	}

}

fclose($handle);


?>