<?php

namespace Processor;

class ApInfo
{
    protected $essid;
    
    public function __construct($essid = null)
    {
        $this->essid = $essid;
    }

    public function getEssid()
    {
        return $this->essid;
    }
}
