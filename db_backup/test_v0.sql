-- 主机IP：localhost::1
-- Web服务器：Apache
-- PHP 运行方式：apache2handler
-- PHP版本：5.5.38
-- MySql版本-pdo：5.5.53-log
-- 生成日期：2018-10-03 15:43:00

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- 
-- 数据库: `test`
-- 

-- ##* dede_addonarticle|dede_addonshop|self_limit|self_limit_1|self_limit_old|self_problem|self_role|self_task|self_user ##* --

-- ------------------------------------------------------------

--
--  转储表结构 `dede_addonarticle`
--

DROP TABLE IF EXISTS `dede_addonarticle`;
CREATE TABLE `dede_addonarticle` (
  `aid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `typeid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `body` mediumtext,
  `redirecturl` varchar(255) NOT NULL DEFAULT '',
  `templet` varchar(30) NOT NULL DEFAULT '',
  `userip` char(15) NOT NULL DEFAULT '',
  `diary` varchar(250) NOT NULL DEFAULT '',
  `jiuyi` varchar(250) NOT NULL DEFAULT '',
  `pay` varchar(250) NOT NULL DEFAULT '',
  `xmmc` varchar(250) NOT NULL DEFAULT '',
  `xmfs` varchar(250) NOT NULL DEFAULT '',
  `newimages` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`aid`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

--
--  转储表数据 `dede_addonarticle`
--


-- ------------------------------------------------------------

--
--  转储表结构 `dede_addonshop`
--

DROP TABLE IF EXISTS `dede_addonshop`;
CREATE TABLE `dede_addonshop` (
  `aid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `typeid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `body` mediumtext,
  `price` float NOT NULL DEFAULT '0',
  `trueprice` float NOT NULL DEFAULT '0',
  `brand` varchar(250) NOT NULL DEFAULT '',
  `units` varchar(250) NOT NULL DEFAULT '',
  `templet` varchar(30) NOT NULL,
  `userip` char(15) NOT NULL,
  `redirecturl` varchar(255) NOT NULL,
  `vocation` char(20) NOT NULL DEFAULT '0',
  `infotype` char(20) NOT NULL DEFAULT '0',
  `uptime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

--
--  转储表数据 `dede_addonshop`
--


-- ------------------------------------------------------------

--
--  转储表结构 `self_limit`
--

DROP TABLE IF EXISTS `self_limit`;
CREATE TABLE `self_limit` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限ID',
  `n` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限名称',
  `execs` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限的类与方法',
  `pid` tinyint(4) unsigned NOT NULL COMMENT '权限分组',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='权限表';

--
--  转储表数据 `self_limit`
--

INSERT INTO `self_limit`(`id`, `n`, `execs`, `pid`) VALUES (1,'系统管理','',1),
(2,'管理员管理','',2),
(3,'任务管理','',3),
(4,'系统设置','sysset',1),
(5,'系统设置首页','sysset-index',1),
(6,'清理文件','sysset-oldf',1),
(7,'用户管理','user',2),
(8,'用户列表','user-index',2),
(9,'添加用户','user-add',2),
(10,'添加用户','user-execAdd',2),
(11,'修改用户','user-update',2),
(12,'修改用户','user-execUate',2),
(13,'删除用户','user-del',2),
(14,'角色管理','role',2),
(15,'角色列表','role-index',2),
(16,'添加角色','role-add',2),
(17,'添加角色','role-execAdd',2),
(18,'修改角色','role-update',2),
(19,'修改角色','role-execUate',2),
(20,'删除角色','role-del',2),
(21,'权限管理','limit',2),
(22,'权限列表','limit-index',2),
(23,'用户信息','personal',2),
(24,'个人资料','personal-index',2),
(25,'任务管理','task',3),
(26,'任务列表','task-index',3),
(27,'添加任务','task-add',3),
(28,'添加任务','task-execAdd',3),
(29,'修改任务','task-update',3),
(30,'修改任务','task-execUate',3),
(31,'删除任务','task-del',3),
(32,'执行任务','task-ute',3),
(33,'执行任务','task-execUte',3),
(34,'任务统计','task-count',3),
(35,'回收站','recovery',3),
(36,'回收站列表','recovery-index',3),
(37,'任务还原','recovery-restore',3),
(38,'任务删除','recovery-del',3);

-- ------------------------------------------------------------

--
--  转储表结构 `self_limit_1`
--

DROP TABLE IF EXISTS `self_limit_1`;
CREATE TABLE `self_limit_1` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限ID',
  `n` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限名称',
  `execs` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限的类与方法',
  `pid` tinyint(4) unsigned NOT NULL COMMENT '权限分组',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='权限表';

--
--  转储表数据 `self_limit_1`
--

INSERT INTO `self_limit_1`(`id`, `n`, `execs`, `pid`) VALUES (1,'系统管理','',1),
(2,'管理员管理','',2),
(3,'任务管理','',3),
(4,'系统设置','sysset',1),
(5,'系统设置首页','sysset-index',1),
(6,'清理文件','sysset-oldf',1),
(7,'用户管理','user',2),
(8,'用户列表','user-index',2),
(9,'添加用户','user-add',2),
(10,'添加用户','user-execAdd',2),
(11,'修改用户','user-update',2),
(12,'修改用户','user-execUate',2),
(13,'删除用户','user-del',2),
(14,'角色管理','role',2),
(15,'角色列表','role-index',2),
(16,'添加角色','role-add',2),
(17,'添加角色','role-execAdd',2),
(18,'修改角色','role-update',2),
(19,'修改角色','role-execUate',2),
(20,'删除角色','role-del',2),
(21,'权限管理','limit',2),
(22,'权限列表','limit-index',2),
(23,'用户信息','personal',2),
(24,'个人资料','personal-index',2),
(25,'任务管理','task',3),
(26,'任务列表','task-index',3),
(27,'添加任务','task-add',3),
(28,'添加任务','task-execAdd',3),
(29,'修改任务','task-update',3),
(30,'修改任务','task-execUate',3),
(31,'删除任务','task-del',3),
(32,'执行任务','task-ute',3),
(33,'执行任务','task-execUte',3),
(34,'任务统计','task-count',3),
(35,'回收站','recovery',3),
(36,'回收站列表','recovery-index',3),
(37,'任务还原','recovery-restore',3),
(38,'任务删除','recovery-del',3);

-- ------------------------------------------------------------

--
--  转储表结构 `self_limit_old`
--

DROP TABLE IF EXISTS `self_limit_old`;
CREATE TABLE `self_limit_old` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限ID',
  `n` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限名称',
  `execs` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限的类与方法',
  `pid` tinyint(4) unsigned NOT NULL COMMENT '权限分组',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='权限表';

--
--  转储表数据 `self_limit_old`
--

INSERT INTO `self_limit_old`(`id`, `n`, `execs`, `pid`) VALUES (1,'系统管理','',1),
(2,'管理员管理','',2),
(3,'任务管理','',3),
(4,'系统设置','sysset',1),
(5,'系统设置首页','sysset-index',1),
(6,'清理文件','sysset-oldf',1),
(7,'用户管理','user',2),
(8,'用户列表','user-index',2),
(9,'添加用户','user-add',2),
(10,'添加用户','user-execAdd',2),
(11,'修改用户','user-update',2),
(12,'修改用户','user-execUate',2),
(13,'删除用户','user-del',2),
(14,'角色管理','role',2),
(15,'角色列表','role-index',2),
(16,'添加角色','role-add',2),
(17,'添加角色','role-execAdd',2),
(18,'修改角色','role-update',2),
(19,'修改角色','role-execUate',2),
(20,'删除角色','role-del',2),
(21,'权限管理','limit',2),
(22,'权限列表','limit-index',2),
(23,'用户信息','personal',2),
(24,'个人资料','personal-index',2),
(25,'任务管理','task',3),
(26,'任务列表','task-index',3),
(27,'添加任务','task-add',3),
(28,'添加任务','task-execAdd',3),
(29,'修改任务','task-update',3),
(30,'修改任务','task-execUate',3),
(31,'删除任务','task-del',3),
(32,'执行任务','task-ute',3),
(33,'执行任务','task-execUte',3),
(34,'任务统计','task-count',3),
(35,'回收站','recovery',3),
(36,'回收站列表','recovery-index',3),
(37,'任务还原','recovery-restore',3),
(38,'任务删除','recovery-del',3);

-- ------------------------------------------------------------

--
--  转储表结构 `self_problem`
--

DROP TABLE IF EXISTS `self_problem`;
CREATE TABLE `self_problem` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '问题ID',
  `tit` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '问题名称',
  `descr` text COLLATE utf8_unicode_ci NOT NULL COMMENT '问题描述',
  `u_id` smallint(5) unsigned zerofill NOT NULL COMMENT '提问题人员ID',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '问题是否解决,0未解决,1解决',
  `isdel` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '问题是否删除,0未删除,1删除',
  `times` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '添加任务时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='问题表';

--
--  转储表数据 `self_problem`
--

INSERT INTO `self_problem`(`id`, `tit`, `descr`, `u_id`, `state`, `isdel`, `times`) VALUES (1,'dsfdsfds','<p>
	<img src=\\\"/attached/image/201808/1533623204.png\\\" alt=\\\"\\\" /><span style=\\\"background-color:#99BB00;\\\">dfdsfdsfsdfs &nbsp;&nbsp;</span>
</p>
<p>
	<br />
</p>',00001,1,0,'1533623264'),
(2,'87887','sddsfdsfds',00001,0,0,'1533623854'),
(4,'ziduan','字段验证是否可以',00002,0,0,'1533691899'),
(3,'图片任务','<img src=\\\"/Public/kindeditor-4.1.10/attached/image/201808/1533628109.png\\\" alt=\\\"\\\" />',00001,1,0,'1533628110');

-- ------------------------------------------------------------

--
--  转储表结构 `self_role`
--

DROP TABLE IF EXISTS `self_role`;
CREATE TABLE `self_role` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `n` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `descr` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色描述',
  `limit_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色表';

--
--  转储表数据 `self_role`
--

INSERT INTO `self_role`(`id`, `n`, `descr`, `limit_id`) VALUES (1,'超级管理员','拥有所有权限','-1'),
(2,'游客','','2,7,8,14,15,21,22,23,24,3,25,26,27,28,29,30,31,32,33,34,35,36,37,38');

-- ------------------------------------------------------------

--
--  转储表结构 `self_task`
--

DROP TABLE IF EXISTS `self_task`;
CREATE TABLE `self_task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '任务表ID',
  `p_id` int(11) unsigned NOT NULL COMMENT '问题ID',
  `u_id` smallint(5) unsigned zerofill NOT NULL COMMENT '执行任务人员编号',
  `times` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '执行任务时间',
  `state` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否验证,0未验证,1通过验证,2未通过',
  `plan` text COLLATE utf8_unicode_ci NOT NULL COMMENT '解决方法',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='执行任务表';

--
--  转储表数据 `self_task`
--

INSERT INTO `self_task`(`id`, `p_id`, `u_id`, `times`, `state`, `plan`) VALUES (1,1,00002,'1533623325','0','<span style=\\\"line-height:2;\\\">sdfsssssssssssssssssssssssssssssssssssssss</span><span style=\\\"line-height:2;\\\"></span>'),
(2,1,00002,'1533623345','0','<span style=\\\"background-color:#FFE500;\\\">sdffffffffffffffffffffffffffffffffffffffff</span>'),
(3,1,00002,'1533623473','1','sdfdsfdsfds<img src=\\\"http://renwu.com/Public/kindeditor-4.1.10/plugins/emoticons/images/13.gif\\\" border=\\\"0\\\" alt=\\\"\\\" />'),
(4,3,00002,'1533692257','1','执行任务');

-- ------------------------------------------------------------

--
--  转储表结构 `self_user`
--

DROP TABLE IF EXISTS `self_user`;
CREATE TABLE `self_user` (
  `id` smallint(5) unsigned zerofill NOT NULL AUTO_INCREMENT COMMENT '用户编号',
  `u_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名称',
  `u_pass` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户密码',
  `bumen` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '人员所在部门',
  `role_id` tinyint(2) unsigned NOT NULL COMMENT '用户角色ID',
  `times` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '登录时间',
  `found` smallint(5) unsigned zerofill NOT NULL COMMENT '用户创建人,部位管理人员',
  `mail` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '用于忘记密码时用户找回密码',
  `is_jihuo` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '邮箱是否激活',
  `meg` smallint(5) unsigned DEFAULT NULL COMMENT '用户信息提醒个数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户表';

--
--  转储表数据 `self_user`
--

INSERT INTO `self_user`(`id`, `u_name`, `u_pass`, `bumen`, `role_id`, `times`, `found`, `mail`, `is_jihuo`, `meg`) VALUES (00001,'admin','be33d98a0d9f0dfc2d4b52f64a4603a2','不受限',1,'1533692298',00001,'1922527784@qq.com','1',2),
(00002,'1111','5f36b39b38ad69b822a97760283569dd','不受限',2,'1533692691',00001,'1922527784@qq.com','1',0);
