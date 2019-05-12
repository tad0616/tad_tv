<?php
use XoopsModules\Tadtools\EasyResponsiveTabs;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tad_tv_adm_groupperm.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.php';
require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

//取得本模組編號
$module_id = $xoopsModule->getVar('mid');

//頁面標題
$perm_page_title = _MA_TADTV_PERM_TITLE;

//取得分類編號及標題
$item_list = [];
$sql = 'select `tad_tv_cate_sn`, `tad_tv_cate_title` from ' . $xoopsDB->prefix('tad_tv_cate');
$result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
while (list($tad_tv_cate_sn, $tad_tv_cate_title) = $xoopsDB->fetchRow($result)) {
    $item_list[$tad_tv_cate_sn] = $tad_tv_cate_title;
}

//觀看權限
$formi = new \XoopsGroupPermForm($perm_page_title, $module_id, 'perm_view', _MA_TADTV_PERM_VIEW);
foreach ($item_list as $item_id => $item_name) {
    $formi->addItem($item_id, $item_name);
}
$perm_view_form = $formi->render();
$xoopsTpl->assign('perm_view_form', $perm_view_form);

//產生頁籤語法
$EasyResponsiveTabs = new EasyResponsiveTabs('#groupPermTab', 'default');
$EasyResponsiveTabs->rander();

require_once __DIR__ . '/footer.php';
