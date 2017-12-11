<?php

$holdClasses = array();
$classes = array();
$lineToInsert = "";

$arrCounter = -1;

$handle = fopen("test-hr_HR.aff", "r");

if($handle) {

	while(($line = fgets($handle, 2048)) !== false) {

		// need to trim it as test file is pasted from spreadsheet so empty lines are not really empty
		if(preg_match("/SFX ([A-Z][A-Z])/", $line, $matches) && $line != "") {

			if(!in_array($matches[1], $classes)) {

				array_push($classes, $matches[1]);

			}

			$lineToInsert .= trim($line) . "\n";

		}	else {

			// array_push($holdClasses,$lineToInsert);

			if(trim($lineToInsert) != "") {

				$holdClasses[] = trim($lineToInsert);

			}

			$lineToInsert = "";

		}

	}

}

// array_unique($holdClasses);

echo count($holdClasses);

array_unique($holdClasses);

echo "\n----------\n" . count($holdClasses) . "\n";



// echo count($classes);

?>