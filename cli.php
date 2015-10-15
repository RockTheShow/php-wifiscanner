<?php

require_once(__DIR__.'/autoload.php');

$shell = new \Shell\UnixShell;
$output = $shell->exec('ls ~');
echo $output;
var_dump($output->getStatusCode());
