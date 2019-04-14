<?php
require dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require dirname(__DIR__) . '/function.php';

require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$tad_tv_cate_sn = system_CleanVars($_REQUEST, 'tad_tv_cate_sn', '', 'int');
$contents = "#EXTM3U\n";
if ($tad_tv_cate_sn) {
    $contents = get_url($tad_tv_cate_sn);
    $filename = "tad_tv_{$tad_tv_cate_sn}";
} else {
    $cates = get_tad_tv_cate_all();
    foreach ($cates as $tad_tv_cate_sn => $cate) {
        $contents .= get_url($tad_tv_cate_sn);
    }
    $filename = 'tad_tv_all';
}

function get_url($tad_tv_cate_sn = '')
{
    global $xoopsDB;

    $cate = get_tad_tv_cate($tad_tv_cate_sn);

    $sql = 'select tad_tv_title, tad_tv_url from ' . $xoopsDB->prefix('tad_tv') . "  where `tad_tv_cate_sn` ='{$tad_tv_cate_sn}' and `tad_tv_enable` ='1' order by `tad_tv_sort`";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

    $contents = '';
    while (false !== (list($tad_tv_title, $tad_tv_url) = $xoopsDB->fetchRow($result))) {
        $contents .= "#EXTINF:-1 group-title=\"{$cate['tad_tv_cate_title']}\",{$tad_tv_title}\n{$tad_tv_url}\n";
    }

    return $contents;
}

header('Content-type: application/x-winamp-playlist');
header("Content-Disposition: attachment; filename={$filename}.m3u");
echo $contents;
exit;
