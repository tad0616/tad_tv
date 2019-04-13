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
include_once '../../mainfile.php';
include_once 'function.php';
//判斷是否對該模組有管理權限
$isAdmin = false;
if ($xoopsUser) {
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin = $xoopsUser->isAdmin($module_id);
}

//$interface_menu[_TAD_TO_MOD]="index.php";
$interface_menu[_MD_TADTV_SMNAME1] = 'index.php';
$interface_icon[_MD_TADTV_SMNAME1] = 'fa-chevron-right';
// $interface_menu['Flash']           = "index.php?player=flash";
// $interface_icon['Flash']           = "fa-chevron-right";
$interface_menu['Sewise'] = 'index.php?player=sewise';
$interface_icon['Sewise'] = 'fa-chevron-right';
$interface_menu['VLC'] = 'index.php?player=vlc';
$interface_icon['VLC'] = 'fa-chevron-right';
// $interface_menu['HTML5']           = "index.php?player=html5";
// $interface_icon['HTML5']           = "fa-chevron-right";
$interface_menu['HLS'] = 'index.php?player=hls';
$interface_icon['HLS'] = 'fa-chevron-right';

if ($isAdmin) {
    $interface_menu[_TAD_TO_ADMIN] = 'admin/main.php';
    $interface_icon[_TAD_TO_ADMIN] = 'fa-sign-in';
}
