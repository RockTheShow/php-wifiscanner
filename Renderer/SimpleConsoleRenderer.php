<?php

namespace Renderer;

use Io\InputInterface;

class SimpleConsoleRenderer implements RendererInterface
{
    static $NO_PROBE_CAPTURED = 'No probe requests to show yet.';
    static $STA_CAPTURED = 'STA mac=%s, rssi=%s probed:';
    static $UNTARGETED_PROBE_CAPTURED = ' - Any AP in its range (untargeted probe)';
    static $TARGETED_PROBE_CAPTURED = ' - AP eSSID=%s';
    
    /**
     * @var InputInterface 
     */
    protected $fileReader;
    
    protected $modelView;
    protected $showOnlyTargetedProbes;
    protected $closeRequested;
    protected $renderRequested;
    
    protected function processInput()
    {
        if ($this->closeRequested)
            return;
        $input = $this->fileReader->read(STDIN); // Show info on '<ENTER>', Stop on 'q+<ENTER>'
        $this->closeRequested = ($input === 'q');
        $this->renderRequested = ($input === '');
    }
    
    public function __construct(InputInterface $fileReader, $showOnlyTargetedProbes = false)
    {
        $this->fileReader = $fileReader;
        $this->modelView = [];
        $this->showOnlyTargetedProbes = $showOnlyTargetedProbes;
        $this->closeRequested = false;
        $this->renderRequested = false;
    }
    
    public function notify($data)
    {
        $this->modelView = $data;
    }
    
    public function render()
    {
        $this->processInput();
        if (!$this->renderRequested)
            return;
        
        $stationsWereShown = false;
        foreach ($this->modelView as $station) {
            if (count($station->getProbedAps()) === 0 && // Didn't target any AP
                (!$station->sentUntargetedProbe() || $this->showOnlyTargetedProbes))
                continue;
            
            echo sprintf(static::$STA_CAPTURED.PHP_EOL, $station->getStationMac(), $station->getHighestRssi());
            if ($station->sentUntargetedProbe() && !$this->showOnlyTargetedProbes)
                echo static::$UNTARGETED_PROBE_CAPTURED.PHP_EOL;
            foreach ($station->getProbedAps() as $ap)
                echo sprintf(static::$TARGETED_PROBE_CAPTURED.PHP_EOL, $ap->getEssid());
            $stationsWereShown = true;
        }
        
        if (!$stationsWereShown)
            echo static::$NO_PROBE_CAPTURED.PHP_EOL;
        
        $this->renderRequested = false;
    }
    
    public function closeRequested()
    {
        return $this->closeRequested;
    }
    
    public function setShowOnlyTargetedProbes($showOnlyTargetedProbes)
    {
        $this->showOnlyTargetedProbes = $showOnlyTargetedProbes;
    }
}
