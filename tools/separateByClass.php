<?php

$loadFile = 'wordlistSepByClass';

$fileHandle = fopen($loadFile, 'r');

$separators = array(

    'ost' => array(

        'start' => 'ost',
        'end' => 'šću'

    ),

    'nje' => array(

        'start' => 'nje',
        'end' => 'ima'

    ),

    'čin' => array(

        'start' => 'čin',
        'end' => 'nom'

    ),

    'jin' => array(

        'start' => 'jin',
        'end' => 'nom'

    ),

    'gov' => array(

        'start' => 'gov',
        'end' => 'omu'

    ),

    'ent' => array(

        'start' => 'ent',
        'end' => 'tom'

    ),

    'ast' => array(

        'start' => 'ast',
        'end' => 'ima'

    ),

    'ski' => array(

        'start' => 'ski',
        'end' => 'omu'

    ),

    'nja' => array(

        'start' => 'nja',
        'end' => 'ama'

    )

);

$firstLineInClass = true;

while(($line = fgets($fileHandle)) != false) {

    $line = trim($line);

    if($line != "") {

        $patternSeparator = mb_substr($line, -3);

        if(in_array($patternSeparator, array_keys($separators))) {

            echo "\n";

            if($patternSeparator == $separators[$patternSeparator]['start']) {

                echo $line . "\n";

            } else {

                $line = fgets($fileHandle);

                $line = trim($line);

                do {

                    echo $line . "\n";

                    $line = fgets($fileHandle);

                    $line = trim($line);

                    $patternSeparator = mb_substr($line, -3);

                } while($patternSeparator != $separators[$patternSeparator]['end']);

            }

        } else {

            echo $line . "\n";

        }

    } else {

        echo "\n";

    }

}

fclose($fileHandle);

?>
