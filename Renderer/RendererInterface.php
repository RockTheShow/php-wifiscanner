<?php

namespace Renderer;

use Event\EventSubscriberInterface;

interface RendererInterface extends EventSubscriberInterface
{
    /**
     * @return bool
     */
    function closeRequested();
    
    function render();
}
