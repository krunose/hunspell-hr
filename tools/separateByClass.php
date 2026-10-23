<?php

$loadFile = 'wordlistSeparatedByClass';

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

    )

);

while(($line = fgets($fileHandle)) != false) {

    $line = trim($line);

    if($line != "") {

        echo $line . "\n";

        $startPattern = mb_substr($line, -3);
        $endPattern = mb_substr($line, -3);

        $separatorPattern = mb_substr($line, -3);

        if(in_array($separatorPattern, array_keys($separators))) {


            $end = $separators[$endPattern]['end'];

            while(mb_substr($line, -3) != $end) {

                $line = fgets($fileHandle);

                $line = trim($line);

                $checkPattern = mb_substr($line, -3);

                if($checkPattern != $end) {

                    echo $line . "\n";

                }

            }

        } else {

            continue;

        }

    } else {

        echo "\n";

    }

}

fclose($fileHandle);

?>
