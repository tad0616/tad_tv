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
xoops_loadLanguage('admin_common', 'tadtools');
define('_TAD_NEED_TADTOOLS', '需要 tadtools 模組，可至<a href="http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1" target="_blank">XOOPS輕鬆架</a>下載。');

//tad_tv-edit
define('_MA_TADTV_TAD_TV_TITLE', 'Name');
define('_MA_TADTV_TAD_TV_URL', 'URL');
define('_MA_TADTV_TAD_TV_ENABLE', 'Is it enabled');
define('_MA_TADTV_TAD_TV_CATE_SN', 'Classification Number');
define('_MA_TADTV_TAD_TV_COUNTER', 'Popular');
define('_MA_TADTV_TAD_TV_SN', 'Number');
define('_MA_TADTV_TAD_TV_SORT', 'Order');
define('_MA_TADTV_TAD_TV_CONTENT', 'Description');
define('_MA_TADTV_TAD_TV_CATE_PARENT_SN', 'Parent Category');
define('_MA_TADTV_TAD_TV_CATE_TITLE', 'Classification Title');
define('_MA_TADTV_TAD_TV_CATE_DESC', 'Classification Description');
define('_MA_TADTV_TAD_TV_CATE_SORT', 'Class Sorting');
define('_MA_TADTV_TAD_TV_CATE_ENABLE', 'Status');
define('_MA_TADTV_FORM', 'Edit Live Source');
define('_MA_TADTV_CATE_FORM', 'Edit Category');
define('_MA_TADTV_IMPORT_CSV', 'Import CSV');
define('_MA_TADTV_EXPORT_CSV', 'Export CSV');
define('_MA_TADTV_IMPORT_M3U', 'Import m3u list');
define('_MA_TADTV_EXPORT_M3U', 'Export m3u list');
define('_MA_TADTV_IMPORT', 'Import');
define('_MA_TADTV_M3U8_FROM_URL', 'Live source URL');
define('_MA_TADTV_SERVER_OK', 'Can be connected, if the live broadcast source shows "expired", it may be a network problem, or the live broadcast source does not exist. ');
define('_MA_TADTV_SERVER_NG', 'The host has been hung up, maybe it is temporarily disconnected, or other factors can\'t connect. But if it can\'t be connected for a long time, then it is recommended to delete all the live broadcast sources of the host.');
define('_MA_TADTV_DATA_TOTAL', 'Total number of data: ');
define('_MA_TADTV_CHECK_AGAIN', 'Recheck');
define('_MA_TADTV_DEL_ALL', 'Remove all checked live streams');
define('_MA_TADTV_UNABLE_ALL', 'Close all checked live streams');
define('_MA_TADTV_CHK_URL_FROM_SAME', 'Check host');

define('_MA_TADTV_FORMAT_ERROR', 'Incorrect format, cannot import');
define('_MA_TADTV_CSV_DEMO', 'CSV import file example');
define('_MA_TADTV_M3U_DEMO', 'M3U import file example');
define('_MA_TADTV_TAD_CHK_REPEAT', 'Check duplicate live source');
define('_MA_TADTV_TAD_NO_REPEAT', 'No duplicate live source');
define('_MA_TADTV_MOVE_TO', 'Move the selected live stream to the specified category: ');
define('_MA_TADTV_MOVE', 'Move');
define('_MA_TADTV_PERM_TITLE', 'Online live details permission settings');
define('_MA_TADTV_PERM_DESC', 'Please check the permissions you want to open to the group:');
define('_MA_TADTV_PERM_VIEW', 'View Permission');
