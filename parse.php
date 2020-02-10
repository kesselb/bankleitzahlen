<?php

require 'vendor/autoload.php';

$sourceFile = __DIR__ . '/blz-aktuell-txt-data.txt';
$dataDirectory = __DIR__ . '/data';

$parser = new \kesselb\bankleitzahlen\Parser($sourceFile, $dataDirectory);
$parser->parse();