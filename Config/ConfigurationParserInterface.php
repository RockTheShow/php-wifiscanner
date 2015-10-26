<?php

namespace Config;

interface ConfigurationParserInterface
{
    function getNetworkInterfaceName();
    function getShowOnlyTargetedProbes();
    function getRendererClass();
}
