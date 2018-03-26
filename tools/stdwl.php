<?php

/*

	Kruno, kruno.se (on gmx.com), february 2018.


	DESCRIPTION

		stdwl stands for 'sort to-do word list'.

		Working on the dictionary, always find myself keeping track of what needs to be added or deleted in willy nilly auxiliary file willy nilly marks what is yet to be added or removed and what is deleted or removed.

		This is a simple script for sorting words that are collected in such one auxiliary file so maintainer of a dictionary can keep track of what has to be done and what was done.

		I write auxiliary file like in example below:

			- word2
			-- word1
			+- word4
			+ word 3

		script will read the first two characters of every line of such file and sorts lines by those two characters in following order:

			[1] -- word1
			[2] - word2
			[3] + word3
			[4] +- word4

		Markings are:

			[1] is two minus signs indicating a word that should be removed from dictionary but it's not yet
			[2] minus sign followed by space indicates word that should be added to dictionary but it's not yet
			[3] plus and minus sign indicates word that was initially marked [1] but now it's removed from dictionary (completed, done)
			[4] plus followed by space indicates that word that was initially marked [2], now is added to dictionary (completed, done).

		You can name such auxiliary file as you wish, run it and script will read such file, sort entries in above described manner and replace initial content of auxiliary file with sorted one.

		Backup everything before running this. This is a poor man tool.


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