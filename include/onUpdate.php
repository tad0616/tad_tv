<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

function xoops_module_update_tad_tv($module, $old_version)
{
    global $xoopsDB;

    return true;
}
