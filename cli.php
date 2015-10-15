<?php

use Io\AsynchronousInput;
use Network\TsharkNetworkSniffer;
use Process\ProcessFactory;
use Processor\ProbeRequestProcessor;

require_once(__DIR__.'/autoload.php');

const WLAN_ADAPTER = 'en2';
const NOTIFY_TIMER = 20000; // usecs
const USE_SUDO = false;
const USE_MONITOR = true;

if (!ini_get('date.timezone'))
    ini_set('date.timezone', 'Europe/Paris');

$fileReader = new AsynchronousInput;
$processFactory = new ProcessFactory(USE_SUDO);
$processor = new ProbeRequestProcessor;
$sniffer = new TsharkNetworkSniffer($processFactory, $processor, WLAN_ADAPTER, USE_MONITOR);
$process = $sniffer->sniffProbeRequests();

while ($process->isRunning()) {
    $input = $fileReader->read(STDIN); // Show info on '<ENTER', Stop on 'q+<ENTER>'
    if ($input) {
        if ($input === "q")
            break;
        // TODO code rendering engine(s)
        if (count($processor->getStations()) === 0)
            echo 'No probe requests captured yet.'."\n";
        foreach ($processor->getStations() as $station) {
            foreach ($station->getProbedAps() as $ap) {
                echo $station->getStationMac().' probed '.$ap."\n";
            }
        }
    }
    usleep(NOTIFY_TIMER);
}
$process->stop();
if (!$process->isSuccessful())
    throw new RuntimeException('Process failed: '.$process->getErrorOutput());
