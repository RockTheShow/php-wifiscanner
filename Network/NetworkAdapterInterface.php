<?php

namespace Network;

interface NetworkAdapterInterface
{
    /**
     * @return bool
     */
    function isMonitorEnabled();
    
    function enableMonitor();
    
    /**
     * @return string
     */
    function getName();
}
