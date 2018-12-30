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

/*-----------引入檔案區--------------*/
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
// header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');
// header('Access-Control-Allow-Credentials:true');

include "header.php";
$xoopsOption['template_main'] = 'tad_tv_index.tpl';
include_once XOOPS_ROOT_PATH . "/header.php";

/*-----------功能函數區--------------*/
//以流水號取得某筆tad_tv資料
function get_tad_tv($tad_tv_sn = '')
{
    global $xoopsDB;

    if (empty($tad_tv_sn)) {
        return;
    }

    $sql = "select * from `" . $xoopsDB->prefix("tad_tv") . "`
    where `tad_tv_sn` = '{$tad_tv_sn}'";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

//新增tad_tv計數器
function add_tad_tv_counter($tad_tv_sn = '')
{
    global $xoopsDB;

    if (empty($tad_tv_sn)) {
        return;
    }

    $sql = "update `" . $xoopsDB->prefix("tad_tv") . "`
    set `tad_tv_counter` = `tad_tv_counter` + 1
    where `tad_tv_sn` = '{$tad_tv_sn}'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
}

//列出所有tad_tv資料
function list_tad_tv($tad_tv_sn = "")
{
    global $xoopsDB, $xoopsTpl, $isAdmin;
    $live = false;
    if ($tad_tv_sn) {
        $tv   = get_tad_tv($tad_tv_sn);
        $live = chkurl($tv['tad_tv_url']);
        $xoopsTpl->assign('live', $live);
    } else {
        $tad_tv_sn = 0;
        $tv        = '';
    }
    // die(var_export($tv));
    $xoopsTpl->assign('def_tad_tv_sn', $tad_tv_sn);
    $xoopsTpl->assign('the_tv', $tv);

    $myts        = MyTextSanitizer::getInstance();
    $i           = 0;
    $all_content = '';
    $sql         = "select tad_tv_cate_sn,tad_tv_cate_title from `" . $xoopsDB->prefix("tad_tv_cate") . "` where tad_tv_cate_enable='1' order by `tad_tv_cate_sort`";
    $result      = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    while (list($tad_tv_cate_sn, $tad_tv_cate_title) = $xoopsDB->fetchRow($result)) {
        //判斷目前使用者是否有：觀看權限
        $perm_view = power_chk("perm_view", $tad_tv_cate_sn);
        if (!$perm_view) {
            continue;
        }

        $sql     = "select * from `" . $xoopsDB->prefix("tad_tv") . "` where tad_tv_cate_sn='{$tad_tv_cate_sn}' and tad_tv_enable='1' order by `tad_tv_sort`";
        $result2 = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

        while ($all = $xoopsDB->fetchArray($result2)) {
            //以下會產生這些變數： $tad_tv_sn, $tad_tv_title, $tad_tv_url, $tad_tv_sort, $tad_tv_enable, $tad_tv_cate_sn, $tad_tv_content, $tad_tv_counter
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            //將是/否選項轉換為圖示
            $tad_tv_enable = $tad_tv_enable == 1 ? '<img src="' . XOOPS_URL . '/modules/tad_tv/images/yes.gif" alt="' . _YES . '" title="' . _YES . '">' : '<img src="' . XOOPS_URL . '/modules/tad_tv/images/no.gif" alt="' . _NO . '" title="' . _NO . '">';

            //過濾讀出的變數值
            $tad_tv_title   = $myts->htmlSpecialChars($tad_tv_title);
            $tad_tv_url     = $myts->htmlSpecialChars($tad_tv_url);
            $tad_tv_content = $myts->displayTarea($tad_tv_content, 0, 1, 0, 1, 1);

            $all_content[$tad_tv_cate_title][$i]['tad_tv_sn']         = $tad_tv_sn;
            $all_content[$tad_tv_cate_title][$i]['tad_tv_title']      = $tad_tv_title;
            $all_content[$tad_tv_cate_title][$i]['tad_tv_url']        = $tad_tv_url;
            $all_content[$tad_tv_cate_title][$i]['tad_tv_sort']       = $tad_tv_sort;
            $all_content[$tad_tv_cate_title][$i]['tad_tv_enable']     = $tad_tv_enable;
            $all_content[$tad_tv_cate_title][$i]['tad_tv_cate_sn']    = $tad_tv_cate_sn;
            $all_content[$tad_tv_cate_title][$i]['tad_tv_content']    = $tad_tv_content;
            $all_content[$tad_tv_cate_title][$i]['tad_tv_counter']    = $tad_tv_counter;
            $all_content[$tad_tv_cate_title][$i]['tad_tv_cate_title'] = $tad_tv_cate_title;

            $i++;
        }
    }
    //刪除確認的JS
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
    $sweet_alert_obj    = new sweet_alert();
    $delete_tad_tv_func = $sweet_alert_obj->render('delete_tad_tv_func',
        "{$_SERVER['PHP_SELF']}?op=delete_tad_tv&tad_tv_sn=", "tad_tv_sn");
    $xoopsTpl->assign('delete_tad_tv_func', $delete_tad_tv_func);

    $xoopsTpl->assign('tad_tv_jquery_ui', get_jquery(true));
    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('isAdmin', $isAdmin);
    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('now_op', 'list_tad_tv');

    if ($isAdmin) {
        //加入Token安全機制
        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        $token      = new XoopsFormHiddenToken();
        $token_form = $token->render();
        $xoopsTpl->assign('token_form', $token_form);

        //父分類
        $sql                          = "select `tad_tv_cate_sn`, `tad_tv_cate_title` from `" . $xoopsDB->prefix("tad_tv_cate") . "` order by tad_tv_cate_sort";
        $result                       = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        $i                            = 0;
        $tad_tv_cate_sn_options_array = '';
        while (list($tad_tv_cate_sn, $tad_tv_cate_title) = $xoopsDB->fetchRow($result)) {
            $tad_tv_cate_sn_options_array[$i]['tad_tv_cate_sn']    = $tad_tv_cate_sn;
            $tad_tv_cate_sn_options_array[$i]['tad_tv_cate_title'] = $tad_tv_cate_title;
            $i++;
        }
        $xoopsTpl->assign("tad_tv_cate_sn_options", $tad_tv_cate_sn_options_array);
    }

    //判斷目前使用者是否有：發布權限
    $tad_tv_groupperm_1 = power_chk("tad_tv", 1);
    $xoopsTpl->assign('tad_tv_groupperm_1', $tad_tv_groupperm_1);
}

//更新tad_tv某一筆資料
function simple_update_tad_tv($tad_tv_sn = '')
{
    global $xoopsDB, $isAdmin, $xoopsUser;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    $myts = MyTextSanitizer::getInstance();

    $tad_tv_sn      = intval($_POST['tad_tv_sn']);
    $tad_tv_title   = $myts->addSlashes($_POST['tad_tv_title']);
    $tad_tv_url     = $myts->addSlashes($_POST['tad_tv_url']);
    $tad_tv_cate_sn = intval($_POST['tad_tv_cate_sn']);

    $sql = "update `" . $xoopsDB->prefix("tad_tv") . "` set
       `tad_tv_title` = '{$tad_tv_title}',
       `tad_tv_url` = '{$tad_tv_url}',
       `tad_tv_cate_sn` = '{$tad_tv_cate_sn}'
    where `tad_tv_sn` = '$tad_tv_sn'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    return $tad_tv_sn;
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op             = system_CleanVars($_REQUEST, 'op', '', 'string');
$tad_tv_sn      = system_CleanVars($_REQUEST, 'tad_tv_sn', '', 'int');
$tad_tv_cate_sn = system_CleanVars($_REQUEST, 'tad_tv_cate_sn', '', 'int');
$player         = system_CleanVars($_REQUEST, 'player', 'flash', 'string');

switch ($op) {
    /*---判斷動作請貼在下方---*/

    case "tad_tv_form":
        tad_tv_form($tad_tv_sn);
        break;

    case "delete_tad_tv":
        delete_tad_tv($tad_tv_sn);
        header("location: {$_SERVER['PHP_SELF']}?player=$player");
        exit;

    //更新排序
    case "update_tad_tv_sort":
        $msg = update_tad_tv_sort();
        die($msg);
        break;

    //新增資料
    case "insert_tad_tv":
        $tad_tv_sn = insert_tad_tv();
        header("location: {$_SERVER['PHP_SELF']}?tad_tv_sn=$tad_tv_sn&player=$player");
        exit;

    //更新資料
    case "update_tad_tv":
        simple_update_tad_tv($tad_tv_sn);
        header("location: {$_SERVER['PHP_SELF']}?tad_tv_sn=$tad_tv_sn&player=$player");
        exit;

    //關閉
    case "unable_tv":
        change_tv_status($tad_tv_sn, 0);
        header("location: {$_SERVER['HTTP_REFERER']}");
        exit;
        break;

    default:
        list_tad_tv($tad_tv_sn);
        break;

        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign("toolbar", toolbar_bootstrap($interface_menu));
$xoopsTpl->assign("isAdmin", $isAdmin);
$xoopsTpl->assign("player", $player);
include_once XOOPS_ROOT_PATH . '/footer.php';
