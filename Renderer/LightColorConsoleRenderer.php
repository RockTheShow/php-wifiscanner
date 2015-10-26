<?php

namespace Renderer;

class LightColorConsoleRenderer extends SimpleConsoleRenderer
{
    static $NO_PROBE_CAPTURED = "\033[1;31mNo probe requests to show yet.\033[0m";
    static $STA_CAPTURED = "STA mac=\033[1;33m%s\033[0m, rssi=\033[1;35m%s\033[0m probed:";
    static $UNTARGETED_PROBE_CAPTURED = "\033[0;37m - Any AP in its range (untargeted probe)\033[0m";
    static $TARGETED_PROBE_CAPTURED = " - AP eSSID=\033[0;36m%s\033[0m";
}
