<?php
include "../../../include/cp_header.php";
include "../function.php";

$tad_tv_sn        = (int)$_POST['tad_tv_sn'];
$sql              = "select tad_tv_url from " . $xoopsDB->prefix("tad_tv") . " where `tad_tv_sn`='{$tad_tv_sn}'";
$result           = $xoopsDB->queryF($sql) or die("查詢錯誤");
list($tad_tv_url) = $xoopsDB->fetchRow($result);
echo chkurl($tad_tv_url);
