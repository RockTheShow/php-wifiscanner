<?php

namespace Config;

interface ConfigurationParserInterface
{
    function getHelpRequested();
    function getVersionRequested();

    function getNetworkInterfaceName();
    function getShowOnlyTargetedProbes();
    function getRendererClass();
}
