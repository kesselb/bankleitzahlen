<?php

/**
 * Script to parse blz-aktuell-txt-data.txt
 * https://www.bundesbank.de/de/aufgaben/unbarer-zahlungsverkehr/serviceangebot/bankleitzahlen
 */

require 'vendor/autoload.php';

$sourceFile = __DIR__ . '/blz-aktuell-txt-data.txt';
$dataDirectory = __DIR__ . '/data';

$parser = new \kesselb\bankleitzahlen\Parser($sourceFile, $dataDirectory);
$parser->parse();