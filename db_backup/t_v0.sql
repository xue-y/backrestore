-- 主机IP：localhost::1
-- Web服务器：Apache
-- PHP 运行方式：cgi-fcgi
-- PHP版本：7.0.12
-- MySql版本-pdo：5.5.53-log
-- 生成日期：2018-10-04 09:38:15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- 
-- 数据库: `t`
-- 

-- ##* dede_addonsoft|dede_admin ##* --

-- ------------------------------------------------------------

--
--  转储表结构 `dede_addonsoft`
--

DROP TABLE IF EXISTS `dede_addonsoft`;
CREATE TABLE `dede_addonsoft` (
  `aid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `typeid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `filetype` varchar(10) NOT NULL DEFAULT '',
  `language` varchar(10) NOT NULL DEFAULT '',
  `softtype` varchar(10) NOT NULL DEFAULT '',
  `accredit` varchar(10) NOT NULL DEFAULT '',
  `os` varchar(30) NOT NULL DEFAULT '',
  `softrank` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `officialUrl` varchar(30) NOT NULL DEFAULT '',
  `officialDemo` varchar(50) NOT NULL DEFAULT '',
  `softsize` varchar(10) NOT NULL DEFAULT '',
  `softlinks` text,
  `introduce` text,
  `daccess` smallint(5) NOT NULL DEFAULT '0',
  `needmoney` smallint(5) NOT NULL DEFAULT '0',
  `templet` varchar(30) NOT NULL DEFAULT '',
  `userip` char(15) NOT NULL DEFAULT '',
  `redirecturl` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`aid`),
  KEY `softMain` (`typeid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

--
--  转储表数据 `dede_addonsoft`
--


-- ------------------------------------------------------------

--
--  转储表结构 `dede_admin`
--

DROP TABLE IF EXISTS `dede_admin`;
CREATE TABLE `dede_admin` (
  `id` int(10) unsigned NOT NULL,
  `usertype` float unsigned DEFAULT '0',
  `userid` char(30) NOT NULL DEFAULT '',
  `pwd` char(32) NOT NULL DEFAULT '',
  `uname` char(20) NOT NULL DEFAULT '',
  `tname` char(30) NOT NULL DEFAULT '',
  `email` char(30) NOT NULL DEFAULT '',
  `typeid` text,
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  `loginip` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

--
--  转储表数据 `dede_admin`
--

INSERT INTO `dede_admin`(`id`, `usertype`, `userid`, `pwd`, `uname`, `tname`, `email`, `typeid`, `logintime`, `loginip`) VALUES (1,'10','admin','04894ca0a93ffcb0a885','admin','','','',1496034862,'58.56.31.91'),
(2,'10','cbsdandan','bea4357c42748a66140d','cbsdandan','','','',1502328574,'58.56.31.91'),
(6,'5','wangy','f9e0337921d020ab9432','wy','','','',1496814210,'58.56.31.91');
