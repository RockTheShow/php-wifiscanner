<?php

use Io\AsynchronousInput;
use Network\TsharkNetworkSniffer;
use Process\ProcessFactory;
use Processor\ProbeRequestProcessor;
use Renderer\SimpleConsoleRenderer;

require_once(__DIR__.'/autoload.php');

const WLAN_ADAPTER = 'en1';
const NOTIFY_TIMER = 20000; // usecs
const USE_SUDO = false;
const USE_MONITOR = true;

if (!ini_get('date.timezone')) {
    fprintf(
        STDERR,
        'Warning: No timezone defined. Assuming Europe/Paris.'.PHP_EOL.
        'This default will be removed in a future release. You are highly encouraged '.PHP_EOL.
        'to define the `date.timezone` setting in '.php_ini_loaded_file().'.'.PHP_EOL
    );
    ini_set('date.timezone', 'Europe/Paris');
}

$processFactory = new ProcessFactory(USE_SUDO);
$fileReader = new AsynchronousInput;
$renderer = new SimpleConsoleRenderer($fileReader);
$processor = new ProbeRequestProcessor($renderer);
$sniffer = new TsharkNetworkSniffer($processFactory, $processor, WLAN_ADAPTER, USE_MONITOR);

$process = $sniffer->sniffProbeRequests();
while ($process->isRunning()) {
    $renderer->render();
    if ($renderer->closeRequested())
        $process->stop();
    usleep(NOTIFY_TIMER);
}
if (!$process->isSuccessful())
    throw new RuntimeException('Process failed: '.$process->getErrorOutput());
