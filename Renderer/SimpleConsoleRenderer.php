<?php

namespace Renderer;

use Io\InputInterface;

class SimpleConsoleRenderer implements RendererInterface
{
    /**
     * @var InputInterface 
     */
    protected $fileReader;
    
    protected $modelView;
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
    
    public function __construct(InputInterface $fileReader)
    {
        $this->fileReader = $fileReader;
        $this->modelView = [];
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
        
        if (count($this->modelView) === 0)
            echo 'No probe requests captured yet.'.PHP_EOL;
        foreach ($this->modelView as $station) {
            if (count($station->getProbedAps()) === 0)
                continue;
            echo 'STA mac='.$station->getStationMac().
                 ', rssi='.$station->getHighestRssi().
                 ' probed: '.PHP_EOL;
            foreach ($station->getProbedAps() as $ap)
                echo ' - AP eSSID='.$ap.PHP_EOL;
        }
        $this->renderRequested = false;
    }
    
    public function closeRequested()
    {
        return $this->closeRequested;
    }
}
