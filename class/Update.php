<?php

namespace XoopsModules\Tad_tv;

/*
Update Class Definition

You may not change or alter any portion of this comment or credits of
supporting developers from this source code or any supporting source code
which is considered copyrighted (c) material of the original comment or credit
authors.

This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       Mamba <mambax7@gmail.com>
 */

/**
 * Class Update
 */
class Update
{
    //檢查某欄位是否存在
    public static function chk_chk1()
    {
        global $xoopsDB;
        $sql = 'select count(`欄位`) from ' . $xoopsDB->prefix('資料表');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    //執行更新
    public static function go_update1()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('資料表') . ' ADD `欄位` smallint(5) NOT NULL';
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL, 3, $xoopsDB->error());

        return true;
    }
}
