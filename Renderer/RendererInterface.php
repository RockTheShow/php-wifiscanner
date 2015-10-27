<?php

namespace Renderer;

use Event\EventSubscriberInterface;
use Exception;

interface RendererInterface extends EventSubscriberInterface
{
    /**
     * @return bool
     */
    function closeRequested();
    
    function render();
    
    function spawnError(Exception $e);
}
