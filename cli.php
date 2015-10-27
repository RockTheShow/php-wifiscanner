<?php

use Config\ArgvParser;
use Io\AsynchronousInput;
use Network\TsharkNetworkSniffer;
use Process\ProcessFactory;
use Processor\ProbeRequestProcessor;

require_once(__DIR__.'/autoload.php');

const NOTIFY_TIMER = 20000; // usecs
const USE_SUDO = false;
const USE_MONITOR = true;

// this check will be removed soon
if (!ini_get('date.timezone')) {
    fprintf(
        STDERR,
        'Warning: No timezone defined. Assuming Europe/Paris.'.PHP_EOL.
        'This default will be removed in a future release. You are highly encouraged '.PHP_EOL.
        'to define the `date.timezone` setting in '.php_ini_loaded_file().'.'.PHP_EOL
    );
    ini_set('date.timezone', 'Europe/Paris');
}

$config = new ArgvParser($_SERVER['argv']);
$processFactory = new ProcessFactory(USE_SUDO);
$fileReader = new AsynchronousInput;
$rendererClass = $config->getRendererClass();
$renderer = new $rendererClass($fileReader, $config->getShowOnlyTargetedProbes());
set_exception_handler([$renderer, 'spawnError']);
$processor = new ProbeRequestProcessor($renderer);
$sniffer = new TsharkNetworkSniffer($processFactory, $processor, $config->getNetworkInterfaceName(), USE_MONITOR);

$process = $sniffer->sniffProbeRequests();
while ($process->isRunning()) {
    $renderer->render();
    if ($renderer->closeRequested())
        $process->stop();
    usleep(NOTIFY_TIMER);
}
if (!$process->isSuccessful())
    throw new RuntimeException('Process failed: '.$process->getErrorOutput());