<?php

namespace Renderer;

use Exception;
use Io\InputInterface;
use Network\StationInfo;

class SimpleConsoleRenderer implements RendererInterface
{
    const NO_PROB_CAP = 'No probe requests to show yet.';
    const STA_CAP = 'STA mac=%s, rssi=%s probed:';
    const UNTARG_PROB_CAP = ' - Any AP in its range (untargeted probe)';
    const TARG_PROB_CAP = ' - AP eSSID=%s';
    const ERROR_MSG = 'E: %s (%s)';
    
    /**
     * @var InputInterface 
     */
    protected $fileReader;
    
    protected $modelView;
    protected $onlyTargetedProbes;
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
    
    public function __construct(InputInterface $fileReader, $onlyTargetedProbes = false)
    {
        $this->fileReader = $fileReader;
        $this->modelView = [];
        $this->onlyTargetedProbes = $onlyTargetedProbes;
        $this->closeRequested = false;
        $this->renderRequested = false;

        fprintf(STDERR, 'Starting. Press <ENTER> to show collected info, q<ENTER> to quit.'.PHP_EOL);
    }
    
    public function notify($data)
    {
        usort($data, function(StationInfo $sta1, StationInfo $sta2)
        {
            // WTB Spaceship Operator :[
            if ($sta1->getHighestRssi() < $sta2->getHighestRssi())
                return -1;
            if ($sta1->getHighestRssi() > $sta2->getHighestRssi())
                return 1;
            return 0;
        });
        $this->modelView = array_reverse($data);
    }
    
    public function render()
    {
        $this->processInput();
        if (!$this->renderRequested)
            return;
        
        $stationsWereShown = false;
        foreach ($this->modelView as $station) {
            // If STA didn't target any AP -AND- (it didn't send wildcard probes -OR- we filter out wildcard probes)
            if (count($station->getProbedAps()) === 0 &&
                (!$station->sentUntargetedProbe() || $this->onlyTargetedProbes))
                continue;
            
            echo sprintf(static::STA_CAP.PHP_EOL, $station->getStationMac(), $station->getHighestRssi());
            if ($station->sentUntargetedProbe() && !$this->onlyTargetedProbes)
                echo static::UNTARG_PROB_CAP.PHP_EOL;
            foreach ($station->getProbedAps() as $ap)
                echo sprintf(static::TARG_PROB_CAP.PHP_EOL, $ap->getEssid());
            $stationsWereShown = true;
        }
        
        if (!$stationsWereShown)
            echo static::NO_PROB_CAP.PHP_EOL;
        
        $this->renderRequested = false;
    }
    
    public function closeRequested()
    {
        return $this->closeRequested;
    }
    
    public function spawnError(Exception $e)
    {
        fprintf(STDERR, static::ERROR_MSG.PHP_EOL, $e->getMessage(), get_class($e));
    }
}
