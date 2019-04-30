<?php
use XoopsModules\Tadtools\Utility;

include dirname(__DIR__) . '/preloads/autoloader.php';

function xoops_module_install_tad_tv(&$module)
{
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_tv');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_tv/file');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_tv/image');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_tv/image/.thumbs');

    return true;
}
