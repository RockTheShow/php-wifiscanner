<?php

namespace Renderer;

class LightColorConsoleRenderer extends SimpleConsoleRenderer
{
    const NO_PROB_CAP = "\033[0;37mNo probe requests to show yet.\033[0m";
    const STA_CAP = "STA mac=\033[1;33m%s\033[0m, rssi=\033[1;35m%s\033[0m probed:";
    const UNTARG_PROB_CAP = "\033[0;37m - Any AP in its range (untargeted probe)\033[0m";
    const TARG_PROB_CAP = " - AP eSSID=\033[0;36m%s\033[0m";
    const ERROR_MSG = "\033[1;31mE: \033[0m%s (\033[1;31m%s\033[0m)";
}
