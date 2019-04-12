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

//引入TadTools的函式庫
if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/tad_function.php")) {
    redirect_header("http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1", 3, _TAD_NEED_TADTOOLS);
}
include_once XOOPS_ROOT_PATH . "/modules/tadtools/tad_function.php";

/********************* 自訂函數 *********************/

//以流水號取得某筆tad_tv_cate資料
function get_tad_tv_cate($tad_tv_cate_sn = '')
{
    global $xoopsDB;

    if (empty($tad_tv_cate_sn)) {
        return;
    }

    $sql = "select * from `" . $xoopsDB->prefix("tad_tv_cate") . "`
    where `tad_tv_cate_sn` = '{$tad_tv_cate_sn}'";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

//取得tad_tv_cate所有資料陣列
function get_tad_tv_cate_all()
{
    global $xoopsDB;
    $sql      = "select * from `" . $xoopsDB->prefix("tad_tv_cate") . "` order by tad_tv_cate_sort";
    $result   = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $data_arr = [];
    while ($data = $xoopsDB->fetchArray($result)) {
        $tad_tv_cate_sn            = $data['tad_tv_cate_sn'];
        $data_arr[$tad_tv_cate_sn] = $data;
    }
    return $data_arr;
}

//新增資料到tad_tv中
function insert_tad_tv()
{
    global $xoopsDB, $xoopsUser, $isAdmin;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    //XOOPS表單安全檢查
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode("<br />", $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header($_SERVER['PHP_SELF'], 3, $error);
    }

    $myts = MyTextSanitizer::getInstance();

    $tad_tv_sn      = (int)$_POST['tad_tv_sn'];
    $tad_tv_title   = $myts->addSlashes($_POST['tad_tv_title']);
    $tad_tv_url     = $myts->addSlashes($_POST['tad_tv_url']);
    $tad_tv_sort    = empty($_POST['tad_tv_sort']) ? tad_tv_max_sort() : (int)$_POST['tad_tv_sort'];
    $tad_tv_enable  = (int)$_POST['tad_tv_enable'];
    $tad_tv_cate_sn = $_POST['tad_tv_cate_sn'];
    $tad_tv_content = $myts->addSlashes($_POST['tad_tv_content']);
    $tad_tv_counter = (int)$_POST['tad_tv_counter'];

    $sql = "insert into `" . $xoopsDB->prefix("tad_tv") . "` (
        `tad_tv_title`,
        `tad_tv_url`,
        `tad_tv_sort`,
        `tad_tv_enable`,
        `tad_tv_cate_sn`,
        `tad_tv_content`,
        `tad_tv_counter`
    ) values(
        '{$tad_tv_title}',
        '{$tad_tv_url}',
        '{$tad_tv_sort}',
        '{$tad_tv_enable}',
        '{$tad_tv_cate_sn}',
        '{$tad_tv_content}',
        0
    )";
    $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

    //取得最後新增資料的流水編號
    $tad_tv_sn = $xoopsDB->getInsertId();

    return $tad_tv_sn;
}

//更新tad_tv某一筆資料
function update_tad_tv($tad_tv_sn = '')
{
    global $xoopsDB, $isAdmin, $xoopsUser;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    //XOOPS表單安全檢查
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode("<br />", $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header($_SERVER['PHP_SELF'], 3, $error);
    }

    $myts = MyTextSanitizer::getInstance();

    $tad_tv_sn      = (int)$_POST['tad_tv_sn'];
    $tad_tv_title   = $myts->addSlashes($_POST['tad_tv_title']);
    $tad_tv_url     = $myts->addSlashes($_POST['tad_tv_url']);
    $tad_tv_sort    = empty($_POST['tad_tv_sort']) ? tad_tv_max_sort() : (int)$_POST['tad_tv_sort'];
    $tad_tv_enable  = (int)$_POST['tad_tv_enable'];
    $tad_tv_cate_sn = $_POST['tad_tv_cate_sn'];
    $tad_tv_content = $myts->addSlashes($_POST['tad_tv_content']);
    $tad_tv_counter = (int)$_POST['tad_tv_counter'];

    $sql = "update `" . $xoopsDB->prefix("tad_tv") . "` set
       `tad_tv_title` = '{$tad_tv_title}',
       `tad_tv_url` = '{$tad_tv_url}',
       `tad_tv_sort` = '{$tad_tv_sort}',
       `tad_tv_enable` = '{$tad_tv_enable}',
       `tad_tv_cate_sn` = '{$tad_tv_cate_sn}',
       `tad_tv_content` = '{$tad_tv_content}'
    where `tad_tv_sn` = '$tad_tv_sn'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    return $tad_tv_sn;
}

//刪除tad_tv某筆資料資料
function delete_tad_tv($tad_tv_sn = '', $tad_tv_cate_sn = '')
{
    global $xoopsDB, $isAdmin;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    if (empty($tad_tv_sn)) {
        return;
    }
    if (!empty($tad_tv_cate_sn)) {
        $sql = "delete from `" . $xoopsDB->prefix("tad_tv") . "`
        where `tad_tv_cate_sn` = '{$tad_tv_cate_sn}'";
    } else {
        $sql = "delete from `" . $xoopsDB->prefix("tad_tv") . "`
        where `tad_tv_sn` = '{$tad_tv_sn}'";
    }
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
}

//自動取得tad_tv的最新排序
function tad_tv_max_sort()
{
    global $xoopsDB;
    $sql        = "select max(`tad_tv_sort`) from `" . $xoopsDB->prefix("tad_tv") . "`";
    $result     = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    list($sort) = $xoopsDB->fetchRow($result);
    return ++$sort;
}

//檢查系統80 port是否活著
function chkwwwsrv($addr = "")
{
    $ch = curl_init(); //要先初始化哦
    curl_setopt($ch, CURLOPT_URL, $addr);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

    $data = curl_exec($ch);
    curl_close($ch); //同上

    preg_match_all("/HTTP\/1\.[1|0]\s(\d{3})/", $data, $matches);
    $code = end($matches[1]);
    #echo $code.' = ';

    if (!$data) {
        //如果Url無法開啟
        //echo "網頁無法開啟";
        return false;
    } else {
        return $code;
    }
}

//檢查清單是否活著
function chkurl($addr = "")
{
    return fopen($addr, "r");
}

//改變直播源狀態
function change_tv_status($tad_tv_sn = "", $status = 1)
{
    global $xoopsDB, $isAdmin;
    if ($isAdmin) {
        $sql = "update " . $xoopsDB->prefix("tad_tv") . " set tad_tv_enable='{$status}' where tad_tv_sn = '{$tad_tv_sn}'";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    }
}
