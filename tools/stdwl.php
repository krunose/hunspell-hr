<?php

/*

	Kruno, kruno.se (on gmx.com), february 2018.


	DESCRIPTION

		stdwl.php => sort to-do word list

		Simple script for sorting words that are collected in auxiliary file so maintainer of a dictionary can keep track of what's added, deleted or what yet needs to be done.

		Script reads the first two characters of such file and sorts lines by those two characters in following order:

			[1] -- word1
			[2] - word2
			[3] + word3
			[4] +- word4

		[1] is two minus signs indicating a word that should be deleted from dictionary but it's not yet
		[2] minus sign followed by space indicates word that should be added to dictionary but it's not yet added
		[3] plus and minus sign indicates word that was initially marked [1] but now it's removed from dictionary (completed)
		[4] plus followed by space indicates that word that was initially marked [2], now is added to dictionary.

		You can name such auxiliary file as you wish. Script will read such file, sort entries in above described order and replace initial content of auxiliary file with sorted one.

		Backup everything before running this.


	USAGE

		Run script like this:

			php stdwl.php wordlist

		where 'wordlist' is auxiliary file.

*/


	$file = $argv[1];

	$wordlist = file($file);

	$results = array();

	$order = array ("--", "- ", "+ ", "+-");


	foreach ($wordlist as $word) {

		$startsWith = substr($word, 0, 2);

		$results[$startsWith][] = $word;

	}

	unlink($file);

	$handle = fopen($file, "a+");

	for($i = 0; $i < count($order); $i++) {

		foreach ($results[$order[$i]] as $key => $result) {

			fwrite($handle, $result);

		}

	}

	fclose($handle);

?>