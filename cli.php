<?php

use Network\AirmonNetworkAdapter;
use Process\ProcessFactory;

require_once(__DIR__.'/autoload.php');

$processFactory = new ProcessFactory;
$adapter = new AirmonNetworkAdapter($processFactory, 'wlan0', 'mon0');
if (!$adapter->isMonitorEnabled())
    $adapter->enableMonitor();
