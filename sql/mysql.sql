CREATE TABLE `tad_tv` (
  `tad_tv_sn` smallint(6) unsigned NOT NULL auto_increment COMMENT '編號',
  `tad_tv_title` varchar(255) NOT NULL default '' COMMENT '名稱',
  `tad_tv_url` varchar(255) NOT NULL default '' COMMENT '網址',
  `tad_tv_sort` smallint(6) unsigned NOT NULL default '0' COMMENT '順序',
  `tad_tv_enable` enum('1','0') NOT NULL COMMENT '是否啟用',
  `tad_tv_cate_sn` smallint(6) unsigned NOT NULL default '0' COMMENT '所屬類別',
  `tad_tv_content` text NOT NULL COMMENT '說明',
  `tad_tv_counter` mediumint(9) unsigned NOT NULL default '0' COMMENT '人氣',
PRIMARY KEY  (`tad_tv_sn`)
) ENGINE=MyISAM;

CREATE TABLE `tad_tv_cate` (
  `tad_tv_cate_sn` smallint(5) unsigned NOT NULL auto_increment COMMENT '分類編號',
  `tad_tv_cate_parent_sn` smallint(5) unsigned NOT NULL default '0' COMMENT '父分類',
  `tad_tv_cate_title` varchar(255) NOT NULL default '' COMMENT '分類標題',
  `tad_tv_cate_desc` varchar(255) NOT NULL default '' COMMENT '分類說明',
  `tad_tv_cate_sort` smallint(5) unsigned NOT NULL default '0' COMMENT '分類排序',
  `tad_tv_cate_enable` enum('1','0') NOT NULL default '1' COMMENT '狀態',
PRIMARY KEY  (`tad_tv_cate_sn`)
) ENGINE=MyISAM;

CREATE TABLE `tad_tv_menu` (
  `tad_tv_sn` smallint(6) unsigned NOT NULL auto_increment COMMENT '編號',
  `tad_tv_player` varchar(50) NOT NULL default '' COMMENT '播放器',
  `tad_tv_enable` enum('1','0') NOT NULL COMMENT '是否可用',
  `tad_tv_proxy` enum('1','0') NOT NULL COMMENT '是否使用代理',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT 'uid',
  `log_time` datetime NOT NULL COMMENT '紀錄時間',
PRIMARY KEY  (`tad_tv_sn`,`tad_tv_player`,`uid`)
) ENGINE=MyISAM;