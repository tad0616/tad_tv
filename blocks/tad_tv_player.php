<?php

//區塊主函式 (tad_tv_player)
/*
use XoopsModules\Tadtools\Utility;
function tad_tv_player($options)
{
global $xoopsDB;

$live = false;

$myts        = \MyTextSanitizer::getInstance();
$i           = 0;
$all_content = array();
$sql         = "select tad_tv_cate_sn,tad_tv_cate_title from `" . $xoopsDB->prefix("tad_tv_cate") . "` where tad_tv_cate_enable='1' order by `tad_tv_cate_sort`";
$result      = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
while (list($tad_tv_cate_sn, $tad_tv_cate_title) = $xoopsDB->fetchRow($result)) {
//判斷目前使用者是否有：觀看權限
$perm_view = Utility::power_chk("perm_view", $tad_tv_cate_sn);
if (!$perm_view) {
continue;
}

$sql     = "select * from `" . $xoopsDB->prefix("tad_tv") . "` where tad_tv_cate_sn='{$tad_tv_cate_sn}' and tad_tv_enable='1' order by `tad_tv_sort`";
$result2 = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

while (false !== ($all = $xoopsDB->fetchArray($result2))) {
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

if ($isAdmin) {
//加入Token安全機制
require_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
$token      = new \XoopsFormHiddenToken();
$token_form = $token->render();
// $xoopsTpl->assign('token_form', $token_form);

//父分類
$sql                          = "select `tad_tv_cate_sn`, `tad_tv_cate_title` from `" . $xoopsDB->prefix("tad_tv_cate") . "` order by tad_tv_cate_sort";
$result                       = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
$i                            = 0;
$tad_tv_cate_sn_options_array = '';
while (list($tad_tv_cate_sn, $tad_tv_cate_title) = $xoopsDB->fetchRow($result)) {
$tad_tv_cate_sn_options_array[$i]['tad_tv_cate_sn']    = $tad_tv_cate_sn;
$tad_tv_cate_sn_options_array[$i]['tad_tv_cate_title'] = $tad_tv_cate_title;
$i++;
}
// $xoopsTpl->assign("tad_tv_cate_sn_options", $tad_tv_cate_sn_options_array);
}

//判斷目前使用者是否有：發布權限
$tad_tv_groupperm_1 = Utility::power_chk("tad_tv", 1);
// $xoopsTpl->assign('tad_tv_groupperm_1', $tad_tv_groupperm_1);
$block['content'] = $content;
return $block;
}
 */
//區塊編輯函式 (tad_tv_player_edit)
/*
function tad_tv_player_edit($options)
{

$form = "
<table>
</table>
";
return $form;
}
 */
