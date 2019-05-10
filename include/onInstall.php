<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}


function xoops_module_install_tad_tv(&$module)
{
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_tv');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_tv/file');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_tv/image');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_tv/image/.thumbs');

    return true;
}
