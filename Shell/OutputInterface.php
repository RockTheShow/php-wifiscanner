<?php

namespace Shell;

interface OutputInterface
{
    /**
     * @return bool
     */
    function succeeded();
    
    /**
     * @return int
     */
    function getStatusCode();
    
    /**
     * @return string[]
     */
    function getOutputAsArray();
    
    /**
     * @return string
     */
    function getOutput();
    
    /**
     * @return string
     */
    function __toString();
}
