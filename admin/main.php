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
$isAdmin = true;
$xoopsOption['template_main'] = 'tad_tv_adm_main.tpl';
include_once 'header.php';
include_once '../function.php';

/*-----------功能函數區--------------*/

//列出所有tad_tv資料
function list_tad_tv($tad_tv_cate_sn = '')
{
    global $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsTpl, $g2p;

    $cate = get_tad_tv_cate($tad_tv_cate_sn);

    $where_tad_tv_cate_sn = !empty($tad_tv_cate_sn) ? "where a.tad_tv_cate_sn='{$tad_tv_cate_sn}'" : '';

    $sql = 'select a.*, b.tad_tv_cate_title from ' . $xoopsDB->prefix('tad_tv') . ' as a left join ' . $xoopsDB->prefix('tad_tv_cate') . " as b on a.tad_tv_cate_sn=b.tad_tv_cate_sn {$where_tad_tv_cate_sn} order by a.tad_tv_sort";

    //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = getPageBar($sql, 10, 10);
    $bar = $PageBar['bar'];
    $sql = $PageBar['sql'];
    $total = $PageBar['total'];

    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

    $i = 0;

    $all_content = [];
    while ($all = $xoopsDB->fetchArray($result)) {
        // $live = chkurl($all['tad_tv_url']);

        $all_content[$i] = $all;
        $all_content[$i]['tad_tv_url2'] = urlencode($all['tad_tv_url']);
        $i++;
    }
    get_jquery(true);

    if (!file_exists(XOOPS_ROOT_PATH . '/modules/tadtools/sweet_alert.php')) {
        redirect_header('index.php', 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . '/modules/tadtools/sweet_alert.php';
    $sweet_alert = new sweet_alert();
    $sweet_alert->render('delete_tad_tv_cate_func', 'main.php?op=delete_tad_tv_cate&tad_tv_cate_sn=', 'tad_tv_cate_sn');
    $sweet_alert2 = new sweet_alert();
    $sweet_alert2->render('delete_tad_tv_func', "main.php?op=delete_tad_tv&tad_tv_cate_sn=$tad_tv_cate_sn&g2p=$g2p&tad_tv_sn=", 'tad_tv_sn');

    $xoopsTpl->assign('now_op', 'list_tad_tv');
    $xoopsTpl->assign('tad_tv_cate_sn', $tad_tv_cate_sn);
    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('cate', $cate);
    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('cates', get_tad_tv_cate_all());
}

//列出所有tad_tv_cate資料
function list_tad_tv_cate_tree($def_tad_tv_cate_sn = '')
{
    global $xoopsDB, $xoopsTpl;

    $sql = 'select count(*),tad_tv_cate_sn from ' . $xoopsDB->prefix('tad_tv') . ' group by tad_tv_cate_sn';
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    while (list($count, $tad_tv_cate_sn) = $xoopsDB->fetchRow($result)) {
        $cate_count[$tad_tv_cate_sn] = $count;
    }

    $path = get_tad_tv_cate_path($def_tad_tv_cate_sn);
    $path_arr = array_keys($path);
    $data[] = "{ id:0, pId:0, name:'All', url:'main.php', target:'_self', open:true}";

    $sql = 'select tad_tv_cate_sn, tad_tv_cate_parent_sn, tad_tv_cate_title from ' . $xoopsDB->prefix('tad_tv_cate') . ' order by tad_tv_cate_sort';
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    while (list($tad_tv_cate_sn, $tad_tv_cate_parent_sn, $tad_tv_cate_title) = $xoopsDB->fetchRow($result)) {
        $font_style = $def_tad_tv_cate_sn == $tad_tv_cate_sn ? ", font:{'background-color':'yellow', 'color':'black'}" : '';
        $open = in_array($tad_tv_cate_sn, $path_arr, true) ? 'true' : 'false';
        $display_counter = empty($cate_count[$tad_tv_cate_sn]) ? '' : " ({$cate_count[$tad_tv_cate_sn]})";
        $data[] = "{ id:{$tad_tv_cate_sn}, pId:{$tad_tv_cate_parent_sn}, name:'{$tad_tv_cate_title}{$display_counter}', url:'main.php?tad_tv_cate_sn={$tad_tv_cate_sn}', open: {$open} ,target:'_self' {$font_style}}";
    }

    $json = implode(",\n", $data);

    if (!file_exists(XOOPS_ROOT_PATH . '/modules/tadtools/ztree.php')) {
        redirect_header('index.php', 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . '/modules/tadtools/ztree.php';
    $ztree = new ztree('cate_tree', $json, 'tad_tv_cate_save_drag.php', 'tad_tv_cate_save_sort.php', 'tad_tv_cate_parent_sn', 'tad_tv_cate_sn');
    $ztree_code = $ztree->render();
    $xoopsTpl->assign('ztree_code', $ztree_code);
    $xoopsTpl->assign('cate_count', $cate_count);

    return $data;
}

//取得路徑
function get_tad_tv_cate_path($the_tad_tv_cate_sn = '', $include_self = true)
{
    global $xoopsDB;

    $arr[0]['tad_tv_cate_sn'] = '0';
    $arr[0]['tad_tv_cate_title'] = "<i class='fa fa-home'></i>";
    $arr[0]['sub'] = get_tad_tv_cate_sub(0);
    if (!empty($the_tad_tv_cate_sn)) {
        $tbl = $xoopsDB->prefix('tad_tv_cate');
        $sql = "SELECT t1.tad_tv_cate_sn AS lev1, t2.tad_tv_cate_sn as lev2, t3.tad_tv_cate_sn as lev3, t4.tad_tv_cate_sn as lev4, t5.tad_tv_cate_sn as lev5, t6.tad_tv_cate_sn as lev6, t7.tad_tv_cate_sn as lev7
            FROM `{$tbl}` t1
            LEFT JOIN `{$tbl}` t2 ON t2.tad_tv_cate_parent_sn = t1.tad_tv_cate_sn
            LEFT JOIN `{$tbl}` t3 ON t3.tad_tv_cate_parent_sn = t2.tad_tv_cate_sn
            LEFT JOIN `{$tbl}` t4 ON t4.tad_tv_cate_parent_sn = t3.tad_tv_cate_sn
            LEFT JOIN `{$tbl}` t5 ON t5.tad_tv_cate_parent_sn = t4.tad_tv_cate_sn
            LEFT JOIN `{$tbl}` t6 ON t6.tad_tv_cate_parent_sn = t5.tad_tv_cate_sn
            LEFT JOIN `{$tbl}` t7 ON t7.tad_tv_cate_parent_sn = t6.tad_tv_cate_sn
            WHERE t1.tad_tv_cate_parent_sn = '0'";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        while ($all = $xoopsDB->fetchArray($result)) {
            if (in_array($the_tad_tv_cate_sn, $all, true)) {
                //$main.="-";
                foreach ($all as $tad_tv_cate_sn) {
                    if (!empty($tad_tv_cate_sn)) {
                        if (!$include_self and $tad_tv_cate_sn == $the_tad_tv_cate_sn) {
                            break;
                        }
                        $arr[$tad_tv_cate_sn] = get_tad_tv_cate($tad_tv_cate_sn);
                        $arr[$tad_tv_cate_sn]['sub'] = get_tad_tv_cate_sub($tad_tv_cate_sn);
                        if ($tad_tv_cate_sn == $the_tad_tv_cate_sn) {
                            break;
                        }
                    }
                }
                //$main.="<br>";
                break;
            }
        }
    }

    return $arr;
}

function get_tad_tv_cate_sub($tad_tv_cate_sn = '0')
{
    global $xoopsDB;
    $sql = 'select tad_tv_cate_sn,tad_tv_cate_title from ' . $xoopsDB->prefix('tad_tv_cate') . " where tad_tv_cate_parent_sn='{$tad_tv_cate_sn}'";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $tad_tv_cate_sn_arr = [];
    while (list($tad_tv_cate_sn, $tad_tv_cate_title) = $xoopsDB->fetchRow($result)) {
        $tad_tv_cate_sn_arr[$tad_tv_cate_sn] = $tad_tv_cate_title;
    }

    return $tad_tv_cate_sn_arr;
}

//取得所有tad_tv_cate分類選單的選項（模式 = edit or show,目前分類編號,目前分類的所屬編號）
function get_tad_tv_cate_options($page = '', $mode = 'edit', $default_tad_tv_cate_sn = '0', $default_tad_tv_cate_parent_sn = '0', $unselect_level = '', $start_search_sn = '0', $level = 0)
{
    global $xoopsDB, $xoopsModule, $isAdmin;

    $post_cate_arr = chk_cate_power('tad_tv_post');

    // $mod_id             = $xoopsModule->getVar('mid');
    // $moduleperm_handler = xoops_gethandler('groupperm');
    $count = tad_tv_cate_count();

    $sql = 'select tad_tv_cate_sn,tad_tv_cate_title from ' . $xoopsDB->prefix('tad_tv_cate') . " where tad_tv_cate_parent_sn='{$start_search_sn}' order by tad_tv_cate_sort";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

    $prefix = str_repeat('&nbsp;&nbsp;', $level);
    $level++;

    $unselect = explode(',', $unselect_level);

    $main = '';
    while (list($tad_tv_cate_sn, $tad_tv_cate_title) = $xoopsDB->fetchRow($result)) {
        // $tad_tv_post = $moduleperm_handler->getGroupIds("tad_tv_post", $tad_tv_cate_sn, $mod_id);
        if (!$isAdmin and !in_array($tad_tv_cate_sn, $post_cate_arr, true)) {
            continue;
        }

        if ('edit' === $mode) {
            $selected = ($tad_tv_cate_sn == $default_tad_tv_cate_parent_sn) ? 'selected=selected' : '';
            $selected .= ($tad_tv_cate_sn == $default_tad_tv_cate_sn) ? 'disabled=disabled' : '';
            $selected .= (in_array($level, $unselect, true)) ? 'disabled=disabled' : '';
        } else {
            if (is_array($default_tad_tv_cate_sn)) {
                $selected = in_array($tad_tv_cate_sn, $default_tad_tv_cate_sn, true) ? 'selected=selected' : '';
            } else {
                $selected = ($tad_tv_cate_sn == $default_tad_tv_cate_sn) ? 'selected=selected' : '';
            }
            $selected .= (in_array($level, $unselect, true)) ? 'disabled=disabled' : '';
        }
        if ('none' === $page or empty($count[$tad_tv_cate_sn])) {
            $counter = '';
        } else {
            $w = ('admin' === $page) ? _MA_TADLINK_CATE_COUNT : _MD_TADLINK_CATE_COUNT;
            $counter = ' (' . sprintf($w, $count[$tad_tv_cate_sn]) . ') ';
        }
        $main .= "<option value=$tad_tv_cate_sn $selected>{$prefix}{$tad_tv_cate_title}{$counter}</option>";
        $main .= get_tad_tv_cate_options($page, $mode, $default_tad_tv_cate_sn, $default_tad_tv_cate_parent_sn, $unselect_level, $tad_tv_cate_sn, $level);
    }

    return $main;
}

//更新排序
function update_tad_tv_sort()
{
    global $xoopsDB;
    $sort = 1;
    foreach ($_POST['tr'] as $tad_tv_sn) {
        $sql = 'update ' . $xoopsDB->prefix('tad_tv') . " set `tad_tv_sort`='{$sort}' where `tad_tv_sn`='{$tad_tv_sn}'";
        $xoopsDB->queryF($sql) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')');
        $sort++;
    }

    return _TAD_SORTED . ' (' . date('Y-m-d H:i:s') . ')';
}

//tad_tv編輯表單
function tad_tv_form($tad_tv_sn = '', $tad_tv_cate_sn = '')
{
    global $xoopsDB, $xoopsTpl, $xoopsUser, $isAdmin;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    //抓取預設值
    if (!empty($tad_tv_sn)) {
        $DBV = get_tad_tv($tad_tv_sn);
    } else {
        $DBV = [];
    }

    //預設值設定

    //設定 tad_tv_sn 欄位的預設值
    $tad_tv_sn = !isset($DBV['tad_tv_sn']) ? $tad_tv_sn : $DBV['tad_tv_sn'];
    $xoopsTpl->assign('tad_tv_sn', $tad_tv_sn);
    //設定 tad_tv_title 欄位的預設值
    $tad_tv_title = !isset($DBV['tad_tv_title']) ? '' : $DBV['tad_tv_title'];
    $xoopsTpl->assign('tad_tv_title', $tad_tv_title);
    //設定 tad_tv_url 欄位的預設值
    $tad_tv_url = !isset($DBV['tad_tv_url']) ? '' : $DBV['tad_tv_url'];
    $xoopsTpl->assign('tad_tv_url', $tad_tv_url);
    //設定 tad_tv_sort 欄位的預設值
    $tad_tv_sort = !isset($DBV['tad_tv_sort']) ? tad_tv_max_sort() : $DBV['tad_tv_sort'];
    $xoopsTpl->assign('tad_tv_sort', $tad_tv_sort);
    //設定 tad_tv_enable 欄位的預設值
    $tad_tv_enable = !isset($DBV['tad_tv_enable']) ? '1' : $DBV['tad_tv_enable'];
    $xoopsTpl->assign('tad_tv_enable', $tad_tv_enable);
    //設定 tad_tv_cate_sn 欄位的預設值
    $tad_tv_cate_sn = !isset($DBV['tad_tv_cate_sn']) ? $tad_tv_cate_sn : $DBV['tad_tv_cate_sn'];
    $xoopsTpl->assign('tad_tv_cate_sn', $tad_tv_cate_sn);
    //設定 tad_tv_content 欄位的預設值
    $tad_tv_content = !isset($DBV['tad_tv_content']) ? '' : $DBV['tad_tv_content'];
    $xoopsTpl->assign('tad_tv_content', $tad_tv_content);
    //設定 tad_tv_counter 欄位的預設值
    $tad_tv_counter = !isset($DBV['tad_tv_counter']) ? '' : $DBV['tad_tv_counter'];
    $xoopsTpl->assign('tad_tv_counter', $tad_tv_counter);

    $op = empty($tad_tv_sn) ? 'insert_tad_tv' : 'update_tad_tv';
    //$op = "replace_tad_tv";

    //套用formValidator驗證機制
    if (!file_exists(TADTOOLS_PATH . '/formValidator.php')) {
        redirect_header('index.php', 3, _TAD_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . '/formValidator.php';
    $formValidator = new formValidator('#myForm', true);
    $formValidator_code = $formValidator->render();

    //所屬類別
    $sql = 'select `tad_tv_cate_sn`, `tad_tv_cate_title` from `' . $xoopsDB->prefix('tad_tv_cate') . '` order by tad_tv_cate_sort';
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $i = 0;
    $tad_tv_cate_sn_options_array = [];
    while (list($tad_tv_cate_sn, $tad_tv_cate_title) = $xoopsDB->fetchRow($result)) {
        $tad_tv_cate_sn_options_array[$i]['tad_tv_cate_sn'] = $tad_tv_cate_sn;
        $tad_tv_cate_sn_options_array[$i]['tad_tv_cate_title'] = $tad_tv_cate_title;
        $i++;
    }
    $xoopsTpl->assign('tad_tv_cate_sn_options', $tad_tv_cate_sn_options_array);

    //加入Token安全機制
    include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $token = new XoopsFormHiddenToken();
    $token_form = $token->render();
    $xoopsTpl->assign('token_form', $token_form);
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('formValidator_code', $formValidator_code);
    $xoopsTpl->assign('now_op', 'tad_tv_form');
    $xoopsTpl->assign('next_op', $op);
}

//以流水號取得某筆tad_tv資料
function get_tad_tv($tad_tv_sn = '')
{
    global $xoopsDB;

    if (empty($tad_tv_sn)) {
        return;
    }

    $sql = 'select * from `' . $xoopsDB->prefix('tad_tv') . "`
    where `tad_tv_sn` = '{$tad_tv_sn}'";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $data = $xoopsDB->fetchArray($result);

    return $data;
}

//tad_tv_cate編輯表單
function tad_tv_cate_form($tad_tv_cate_sn = '')
{
    global $xoopsDB, $xoopsTpl, $xoopsUser, $isAdmin;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    //抓取預設值
    if (!empty($tad_tv_cate_sn)) {
        $DBV = get_tad_tv_cate($tad_tv_cate_sn);
    } else {
        $DBV = [];
    }

    //預設值設定

    //設定 tad_tv_cate_sn 欄位的預設值
    $tad_tv_cate_sn = !isset($DBV['tad_tv_cate_sn']) ? $tad_tv_cate_sn : $DBV['tad_tv_cate_sn'];
    $xoopsTpl->assign('tad_tv_cate_sn', $tad_tv_cate_sn);
    //設定 tad_tv_cate_parent_sn 欄位的預設值
    $tad_tv_cate_parent_sn = !isset($DBV['tad_tv_cate_parent_sn']) ? '' : $DBV['tad_tv_cate_parent_sn'];
    $xoopsTpl->assign('tad_tv_cate_parent_sn', $tad_tv_cate_parent_sn);
    //設定 tad_tv_cate_title 欄位的預設值
    $tad_tv_cate_title = !isset($DBV['tad_tv_cate_title']) ? '' : $DBV['tad_tv_cate_title'];
    $xoopsTpl->assign('tad_tv_cate_title', $tad_tv_cate_title);
    //設定 tad_tv_cate_desc 欄位的預設值
    $tad_tv_cate_desc = !isset($DBV['tad_tv_cate_desc']) ? '' : $DBV['tad_tv_cate_desc'];
    $xoopsTpl->assign('tad_tv_cate_desc', $tad_tv_cate_desc);
    //設定 tad_tv_cate_sort 欄位的預設值
    $tad_tv_cate_sort = !isset($DBV['tad_tv_cate_sort']) ? tad_tv_cate_max_sort() : $DBV['tad_tv_cate_sort'];
    $xoopsTpl->assign('tad_tv_cate_sort', $tad_tv_cate_sort);
    //設定 tad_tv_cate_enable 欄位的預設值
    $tad_tv_cate_enable = !isset($DBV['tad_tv_cate_enable']) ? '1' : $DBV['tad_tv_cate_enable'];
    $xoopsTpl->assign('tad_tv_cate_enable', $tad_tv_cate_enable);

    $op = empty($tad_tv_cate_sn) ? 'insert_tad_tv_cate' : 'update_tad_tv_cate';
    //$op = "replace_tad_tv_cate";

    //套用formValidator驗證機制
    if (!file_exists(TADTOOLS_PATH . '/formValidator.php')) {
        redirect_header('index.php', 3, _TAD_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . '/formValidator.php';
    $formValidator = new formValidator('#myForm', true);
    $formValidator_code = $formValidator->render();

    //父分類
    $sql = 'select `tad_tv_cate_sn`, `tad_tv_cate_title` from `' . $xoopsDB->prefix('tad_tv_cate') . '` order by tad_tv_cate_sort';
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $i = 0;
    $tad_tv_cate_sn_options_array = [];
    while (list($tad_tv_cate_sn, $tad_tv_cate_title) = $xoopsDB->fetchRow($result)) {
        $tad_tv_cate_sn_options_array[$i]['tad_tv_cate_sn'] = $tad_tv_cate_sn;
        $tad_tv_cate_sn_options_array[$i]['tad_tv_cate_title'] = $tad_tv_cate_title;
        $i++;
    }
    $xoopsTpl->assign('tad_tv_cate_sn_options', $tad_tv_cate_sn_options_array);

    //加入Token安全機制
    include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $token = new XoopsFormHiddenToken();
    $token_form = $token->render();
    $xoopsTpl->assign('token_form', $token_form);
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('formValidator_code', $formValidator_code);
    $xoopsTpl->assign('now_op', 'tad_tv_cate_form');
    $xoopsTpl->assign('next_op', $op);
}

//自動取得tad_tv_cate的最新排序
function tad_tv_cate_max_sort()
{
    global $xoopsDB;
    $sql = 'select max(`tad_tv_cate_sort`) from `' . $xoopsDB->prefix('tad_tv_cate') . '`';
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    list($sort) = $xoopsDB->fetchRow($result);

    return ++$sort;
}

//新增資料到tad_tv_cate中
function insert_tad_tv_cate()
{
    global $xoopsDB, $xoopsUser, $isAdmin;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    //XOOPS表單安全檢查
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode('<br />', $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header($_SERVER['PHP_SELF'], 3, $error);
    }

    $myts = MyTextSanitizer::getInstance();

    $tad_tv_cate_sn = (int) $_POST['tad_tv_cate_sn'];
    $tad_tv_cate_parent_sn = (int) $_POST['tad_tv_cate_parent_sn'];
    $tad_tv_cate_title = $myts->addSlashes($_POST['tad_tv_cate_title']);
    $tad_tv_cate_desc = $myts->addSlashes($_POST['tad_tv_cate_desc']);
    $tad_tv_cate_sort = (int) $_POST['tad_tv_cate_sort'];
    $tad_tv_cate_enable = (int) $_POST['tad_tv_cate_enable'];

    $sql = 'insert into `' . $xoopsDB->prefix('tad_tv_cate') . "` (
        `tad_tv_cate_parent_sn`,
        `tad_tv_cate_title`,
        `tad_tv_cate_desc`,
        `tad_tv_cate_sort`,
        `tad_tv_cate_enable`
    ) values(
        '{$tad_tv_cate_parent_sn}',
        '{$tad_tv_cate_title}',
        '{$tad_tv_cate_desc}',
        '{$tad_tv_cate_sort}',
        '{$tad_tv_cate_enable}'
    )";
    $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

    //取得最後新增資料的流水編號
    $tad_tv_cate_sn = $xoopsDB->getInsertId();

    return $tad_tv_cate_sn;
}

//更新tad_tv_cate某一筆資料
function update_tad_tv_cate($tad_tv_cate_sn = '')
{
    global $xoopsDB, $isAdmin, $xoopsUser;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    //XOOPS表單安全檢查
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode('<br />', $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header($_SERVER['PHP_SELF'], 3, $error);
    }

    $myts = MyTextSanitizer::getInstance();

    $tad_tv_cate_sn = (int) $_POST['tad_tv_cate_sn'];
    $tad_tv_cate_parent_sn = (int) $_POST['tad_tv_cate_parent_sn'];
    $tad_tv_cate_title = $myts->addSlashes($_POST['tad_tv_cate_title']);
    $tad_tv_cate_desc = $myts->addSlashes($_POST['tad_tv_cate_desc']);
    $tad_tv_cate_sort = (int) $_POST['tad_tv_cate_sort'];
    $tad_tv_cate_enable = (int) $_POST['tad_tv_cate_enable'];

    $sql = 'update `' . $xoopsDB->prefix('tad_tv_cate') . "` set
       `tad_tv_cate_parent_sn` = '{$tad_tv_cate_parent_sn}',
       `tad_tv_cate_title` = '{$tad_tv_cate_title}',
       `tad_tv_cate_desc` = '{$tad_tv_cate_desc}',
       `tad_tv_cate_sort` = '{$tad_tv_cate_sort}',
       `tad_tv_cate_enable` = '{$tad_tv_cate_enable}'
    where `tad_tv_cate_sn` = '$tad_tv_cate_sn'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    return $tad_tv_cate_sn;
}

//刪除tad_tv_cate某筆資料資料
function delete_tad_tv_cate($tad_tv_cate_sn = '')
{
    global $xoopsDB, $isAdmin;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    if (empty($tad_tv_cate_sn)) {
        return;
    }

    $sql = 'delete from `' . $xoopsDB->prefix('tad_tv_cate') . "`
    where `tad_tv_cate_sn` = '{$tad_tv_cate_sn}'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    delete_tad_tv(null, $tad_tv_cate_sn);
}

//匯入CSV
function import_csv($tad_tv_cate_sn = '')
{
    global $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    $handle = fopen($_FILES['userfile']['tmp_name'], 'rb') or die('無法開啟');
    while (false !== ($data = fgetcsv($handle, 4096))) {
        $data[0] = mb_convert_encoding($data[0], 'UTF-8', 'Big5');
        $data[1] = mb_convert_encoding($data[1], 'UTF-8', 'Big5');
        $title = $myts->addSlashes(trim($data[0]));
        $url = $myts->addSlashes(trim($data[1]));

        if (!empty($title) and empty($url)) {
            //建立目錄
            $tad_tv_cate_sort = tad_tv_cate_max_sort();

            $sql = 'insert into `' . $xoopsDB->prefix('tad_tv_cate') . "` ( `tad_tv_cate_parent_sn`, `tad_tv_cate_title`, `tad_tv_cate_desc`, `tad_tv_cate_sort`, `tad_tv_cate_enable`) values( '0', '{$title}', '{$title}', '{$tad_tv_cate_sort}', '1')";
            $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
            //取得最後新增資料的流水編號
            $tad_tv_cate_sn = $xoopsDB->getInsertId();
        } elseif (!empty($title) and !empty($url)) {
            if (false !== mb_strpos($url, '#')) {
                // echo "<div>$url</div>";
                $urls = explode('#', $url);
                // die(var_export($urls));
                foreach ($urls as $url) {
                    if ('http' === mb_substr($url, 0, 4)) {
                        $tad_tv_sort = tad_tv_max_sort();

                        $sql = 'insert into `' . $xoopsDB->prefix('tad_tv') . "` ( `tad_tv_title`, `tad_tv_url`, `tad_tv_sort`, `tad_tv_enable`, `tad_tv_cate_sn`, `tad_tv_content`) values( '{$title}', '{$url}', '{$tad_tv_sort}', '1', '{$tad_tv_cate_sn}', '{$title}')";
                        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
                    }
                }
            } else {
                if ('http' === mb_substr($url, 0, 4)) {
                    $tad_tv_sort = tad_tv_max_sort();

                    $sql = 'insert into `' . $xoopsDB->prefix('tad_tv') . "` ( `tad_tv_title`, `tad_tv_url`, `tad_tv_sort`, `tad_tv_enable`, `tad_tv_cate_sn`, `tad_tv_content`) values( '{$title}', '{$url}', '{$tad_tv_sort}', '1', '{$tad_tv_cate_sn}', '{$title}')";
                    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
                }
            }
        }
    }
    fclose($handle);
}

//匯入 m3u
function import_m3u($tad_tv_cate_sn = 0)
{
    global $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    $handle = fopen($_FILES['userfile']['tmp_name'], 'rb') or die('無法開啟');
    $data = '';
    $i = $j = 0;
    if ($handle) {
        while (!feof($handle)) {
            $data = fgets($handle);
            $data = $myts->addSlashes(trim($data));
            if (0 == $i and false === mb_strpos($data, '#EXTM3U')) {
                redirect_header($_SERVER['PHP_SELF'], 3, _MA_TADTV_FORMAT_ERROR);
            } elseif (false !== mb_strpos($data, '#EXTINF')) {
                list($info, $title) = explode(',', $data);
                $m3u[$j]['title'] = $title;
            } elseif (false !== mb_strpos($data, 'http')) {
                $m3u[$j]['url'] = $data;
                $j++;
            }
            $i++;
        }

        foreach ($m3u as $key => $data) {
            $tad_tv_sort = tad_tv_max_sort();
            $sql = 'insert into `' . $xoopsDB->prefix('tad_tv') . "` ( `tad_tv_title`, `tad_tv_url`, `tad_tv_sort`, `tad_tv_enable`, `tad_tv_cate_sn`, `tad_tv_content`) values( '{$data['title']}', '{$data['url']}', '{$tad_tv_sort}', '1', '{$tad_tv_cate_sn}', '{$data['title']}')";
            $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
        }
        fclose($handle);
    }
}

//檢查同主機的其他源頭
function chk_url($tad_tv_url = '')
{
    global $xoopsDB, $xoopsTpl;
    $u = parse_url($tad_tv_url);
    // die(var_export($u));
    $port = $u['port'] ? ":{$u['port']}" : '';
    $url = "{$u['scheme']}://{$u['host']}{$port}";
    $chkwwwsrv = chkwwwsrv($url);
    $sql = 'select * from ' . $xoopsDB->prefix('tad_tv') . " where tad_tv_url like '{$url}%'";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $i = 0;
    while ($all = $xoopsDB->fetchArray($result)) {
        $chk_url[$i] = $all;
        $i++;
    }
    $xoopsTpl->assign('testurl', $url);
    $xoopsTpl->assign('chkwwwsrv', $chkwwwsrv);
    $xoopsTpl->assign('chk_url', $chk_url);
    $xoopsTpl->assign('now_op', 'chk_url');
    $xoopsTpl->assign('total', $i);

    if (!file_exists(XOOPS_ROOT_PATH . '/modules/tadtools/sweet_alert.php')) {
        redirect_header('index.php', 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . '/modules/tadtools/sweet_alert.php';
    $sweet_alert = new sweet_alert();
    $sweet_alert->render('delete_tad_tv_cate_func', 'main.php?op=delete_tad_tv_cate&tad_tv_cate_sn=', 'tad_tv_cate_sn');
    $sweet_alert2 = new sweet_alert();
    $sweet_alert2->render('delete_tad_tv_func', "main.php?op=delete_tad_tv&tad_tv_cate_sn=$tad_tv_cate_sn&g2p=$g2p&tad_tv_sn=", 'tad_tv_sn');
}

//刪除指定網址
function del_urls($del_urls = '')
{
    foreach ($del_urls as $tad_tv_sn) {
        delete_tad_tv($tad_tv_sn);
    }
}

//關閉指定網址
function unable_urls($del_urls = '')
{
    foreach ($del_urls as $tad_tv_sn) {
        change_tv_status($tad_tv_sn, 0);
    }
}

//搬移
function move_tv($del_urls = '', $tad_tv_cate_sn = '')
{
    global $xoopsDB;
    foreach ($del_urls as $tad_tv_sn) {
        $sql = 'update ' . $xoopsDB->prefix('tad_tv') . " set tad_tv_cate_sn='{$tad_tv_cate_sn}' where tad_tv_sn = '{$tad_tv_sn}'";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    }
}

//檢查重複直播源
function chk_repeat()
{
    global $xoopsDB, $xoopsTpl;
    $sql = 'select `tad_tv_url`, count(*) as c from ' . $xoopsDB->prefix('tad_tv') . ' group by `tad_tv_url` having c > 1';
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    while (list($url, $counter) = $xoopsDB->fetchRow($result)) {
        $repeat[$url] = $counter;
    }

    $cate = get_tad_tv_cate_all();

    $all_content = [];
    $i = 0;
    foreach ($repeat as $url => $counter) {
        $sql = 'select * from ' . $xoopsDB->prefix('tad_tv') . " where `tad_tv_url` = '{$url}'";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        while ($all = $xoopsDB->fetchArray($result)) {
            $all_content[$i] = $all;

            $all_content[$i]['rowspan'] = $counter;
            $all_content[$i]['cate'] = $cate[$all['tad_tv_cate_sn']];
            $i++;
        }
    }

    // die(var_dump($repeat));
    $xoopsTpl->assign('repeat', $repeat);
    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('now_op', 'chk_repeat');

    if (!file_exists(XOOPS_ROOT_PATH . '/modules/tadtools/sweet_alert.php')) {
        redirect_header('index.php', 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . '/modules/tadtools/sweet_alert.php';
    $sweet_alert = new sweet_alert();
    $sweet_alert->render('delete_tad_tv_cate_func', 'main.php?op=delete_tad_tv_cate&tad_tv_cate_sn=', 'tad_tv_cate_sn');
    $sweet_alert2 = new sweet_alert();
    $sweet_alert2->render('delete_tad_tv_func', "main.php?op=delete_tad_tv&tad_tv_cate_sn=$tad_tv_cate_sn&g2p=$g2p&tad_tv_sn=", 'tad_tv_sn');
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$tad_tv_sn = system_CleanVars($_REQUEST, 'tad_tv_sn', 0, 'int');
$tad_tv_cate_sn = system_CleanVars($_REQUEST, 'tad_tv_cate_sn', 0, 'int');
$tad_tv_url = system_CleanVars($_REQUEST, 'tad_tv_url', '', 'string');
$del_urls = system_CleanVars($_REQUEST, 'del_urls', '', 'array');
$to_tad_tv_cate_sn = system_CleanVars($_REQUEST, 'to_tad_tv_cate_sn', 0, 'int');

switch ($op) {
    /*---判斷動作請貼在下方---*/

    case 'tad_tv_form':
        list_tad_tv_cate_tree($tad_tv_cate_sn);
        tad_tv_form($tad_tv_sn, $tad_tv_cate_sn);
        break;
    case 'delete_tad_tv':
        delete_tad_tv($tad_tv_sn);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case 'tad_tv_cate_form':
        list_tad_tv_cate_tree($tad_tv_cate_sn);
        tad_tv_cate_form($tad_tv_cate_sn);
        break;
    case 'delete_tad_tv_cate':
        delete_tad_tv_cate($tad_tv_cate_sn);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //新增資料
    case 'insert_tad_tv':
        $tad_tv_sn = insert_tad_tv();
        header("location: {$_SERVER['PHP_SELF']}?tad_tv_sn=$tad_tv_sn");
        exit;

    //更新資料
    case 'update_tad_tv':
        update_tad_tv($tad_tv_sn);
        header("location: {$_SERVER['PHP_SELF']}?tad_tv_sn=$tad_tv_sn");
        exit;

    //新增資料
    case 'insert_tad_tv_cate':
        $tad_tv_cate_sn = insert_tad_tv_cate();
        header("location: {$_SERVER['PHP_SELF']}?tad_tv_cate_sn=$tad_tv_cate_sn");
        exit;

    //更新資料
    case 'update_tad_tv_cate':
        update_tad_tv_cate($tad_tv_cate_sn);
        header("location: {$_SERVER['PHP_SELF']}?tad_tv_cate_sn=$tad_tv_cate_sn");
        exit;

    //匯入資料
    case 'import_csv':
        import_csv($tad_tv_cate_sn);
        header("location: {$_SERVER['PHP_SELF']}?tad_tv_cate_sn=$tad_tv_cate_sn");
        exit;
        break;
    //匯入資料
    case 'import_m3u':
        import_m3u($tad_tv_cate_sn);
        header("location: {$_SERVER['PHP_SELF']}?tad_tv_cate_sn=$tad_tv_cate_sn");
        exit;
        break;
    //檢查同主機的其他源頭
    case 'chk_url':
        chk_url($tad_tv_url);
        break;
    //檢查重複直播源
    case 'chk_repeat':
        chk_repeat();
        break;
    //刪除指定源頭
    case 'del_urls':
        del_urls($del_urls);
        header("location: {$_SERVER['HTTP_REFERER']}");
        exit;
        break;
    //關閉指定源頭
    case 'unable_urls':
        unable_urls($del_urls);
        header("location: {$_SERVER['HTTP_REFERER']}");
        exit;
        break;
    //搬移
    case 'move_tv':
        move_tv($del_urls, $to_tad_tv_cate_sn);
        header("location: {$_SERVER['HTTP_REFERER']}");
        exit;
        break;
    //啟用
    case 'enable_tv':
        change_tv_status($tad_tv_sn, 1);
        header("location: {$_SERVER['HTTP_REFERER']}");
        exit;
        break;
    //關閉
    case 'unable_tv':
        change_tv_status($tad_tv_sn, 0);
        header("location: {$_SERVER['HTTP_REFERER']}");
        exit;
        break;
    default:
        list_tad_tv_cate_tree($tad_tv_cate_sn);
        list_tad_tv($tad_tv_cate_sn);
        break;
        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('isAdmin', true);
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tadtools/css/xoops_adm3.css');
include_once 'footer.php';
