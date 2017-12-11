<?php


function writeToFile($content, $file) {

	$fileHandle = fopen($file, "a+");
		fwrite($fileHandle, $content);
			fclose($fileHandle);

}


$handle = fopen("wordlist", "r");


/* prepare variables */
$mainArr = array();
$tmpArr = array();

$currNum;
$prevNum = "0";

$className = null;

$bckpKey = 0;

if($handle) {

	while(($line = fgets($handle)) !== false) {

		$line = trim($line);

		$explodedLine = explode(" | ", $line);

		$currNum = $explodedLine[0];

		if($prevNum != $currNum) {

			$tmpArr[$explodedLine[0]][$explodedLine[2]] = $explodedLine[1];

			$prevNum = $currNum;

			$explodedLine = explode(" | ", (trim($line = fgets($handle))));

			$currNum = $explodedLine[0];

			while($prevNum == $currNum) {

				$tmpArr[$explodedLine[0]][$explodedLine[2]] = $explodedLine[1];

				$prevNum = $currNum;

				$explodedLine = explode(" | ", (trim($line = fgets($handle))));

				$currNum = $explodedLine[0];

			}

			// do not make empty array at the end of file read
			if($explodedLine[0] != "" && $explodedLine[1] != "" && $explodedLine[2] != "" && $explodedLine[2]) {

				// for words that change (nouns, verbs...)
				$tmpArr[$explodedLine[0]][$explodedLine[2]] = $explodedLine[1];

			}

		} // if $prevNum != $currNum

		// print_r($tmpArr);

		/* format classes */

		$classSize = count($tmpArr[$prevNum]);

		if($className !== null) {

			$className++;

		} else {

			$className = "AA";

		}

		$doneClass = "SFX " . $className . " Y " . $classSize . "\n";
		$wordBase = "";

		foreach($tmpArr[$prevNum] as $tmpArrKey => $tmpArrItem) {

			$explodeTmpArrItem = explode(" ", $tmpArrItem);


			$doneClass .= "SFX " . $className . " " . $explodeTmpArrItem[1] . " " . $explodeTmpArrItem[2] . " .";

				if(!is_numeric($tmpArrKey)) {

					$doneClass .= "\t+" . $tmpArrKey ." \n";

				} else {

					$doneClass .= "\n";

				}

			$wordBase = $explodeTmpArrItem[0]; 

		}

		// echo $doneClass;

		/* format classes END */

		/* assign class to a word */

			if(empty($mainArr)) {

				$word = $wordBase . "/" . $className . "\n";
				$mainArr[$className] = $doneClass;
				echo $word;

				writeToFile($word, "test-hr_HR.dic");

			} else {

				end($mainArr);
				$lastMainArrKey = key($mainArr);

				foreach($mainArr as $mainArrKey => $mainArrItem) {

					$forSearch = str_replace(" " . $mainArrKey . " ", " " . $className . " ", $mainArrItem);

					if($forSearch == $doneClass) {

						$word = $wordBase . "/" . $mainArrKey . "\n";
						echo $word;

						writeToFile($word, "test-hr_HR.dic");

						// weird but necessary way to decrement alphabetical values as $className-- doesn't work on "AE" values. Incrementing works, but not decrementing
						end($mainArr);
						$className = key($mainArr);

							break;

					} else {

						if($mainArrKey >= $lastMainArrKey) {

							$mainArr[$className] = $doneClass;
							$word = $wordBase . "/" . $className . "\n";
							echo $word;

							writeToFile($word, "test-hr_HR.dic");

						}

					}

				} // END foreach

			} // ELSE empty($mainArr)

		/* assign class to a word END*/

	} // end while with reading the file

	fclose($handle);

}

// create  affix file
writeToFile("SET UTF-8\n\nTRY ABCDEFGHIJKLMNOPQRSTUVWXYćĆčČđĐžŽabcdefghijklmnopqrstuvwxy-'.ëü\n\nKEY qwertzuiopšđ|asdfghjklčćž|yxcvbnm|žšđ|ćščđž|žćđ|đšćž|zy|qawsedrftgyhuj|jikolpč|aysxdcfvgbh|hnjmk\n\nFLAG long\n\n", "test-hr_HR.aff");

foreach($mainArr as $mainArrKey => $mainArrItem) {

	writeToFile($mainArrItem . "\n", "test-hr_HR.aff");

}

// print_r($mainArr);
// echo count($mainArr);

// array_unique($mainArr);

// echo " | " . count($mainArr);

// print_r($mainArr);

?>
