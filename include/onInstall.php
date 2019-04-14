<?php
/**
 * Tad Tv module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license    http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package    Tad Tv
 * @since      2.5
 * @author     tad
 * @version    $Id $
 **/
use XoopsModules\Tad_tv\Utility;

include dirname(__DIR__) . '/preloads/autoloader.php';

function xoops_module_install_tad_tv(&$module)
{
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_tv');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_tv/file');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_tv/image');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_tv/image/.thumbs');

    return true;
}
