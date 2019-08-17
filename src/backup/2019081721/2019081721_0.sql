-- backrestor
-- version 3.0.0
-- github: https://github.com/xue-y
-- Host: 127.0.0.1
-- DateTime: 2019-08-17 20:05:35
-- MySql version: 5.5.53
-- PHP version: 7.0.12
-- Database: web

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO"; -- 自增列在只插入0 时不产生自增序列，就要提前设置mysql的sql_mode包括NO_AUTO_VALUE_ON_ZERO
SET UNIQUE_CHECKS=0;   -- 设置为1（默认值），则会执行InnoDB表中二级索引的唯一性检查。如果设置为0，则不进行唯一性检查
SET time_zone = "+00:00"; -- 设置时间
SET foreign_key_checks=0;  -- 关闭外键约束

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- --------------------------------------------------


-- table structure `w_admin_power`

DROP TABLE IF EXISTS `w_admin_power`;
CREATE TABLE `w_admin_power` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `mc_name` varchar(20) NOT NULL COMMENT '模块/控制器名称',
  `biaoshi_name` varchar(20) NOT NULL COMMENT '模块/控制器标识名',
  `pid` tinyint(3) unsigned NOT NULL COMMENT '权限父级',
  `icon` varchar(20) NOT NULL COMMENT '图标',
  `sort` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `is_sys` varchar(1) NOT NULL DEFAULT '0' COMMENT '是否系统内置;系统内置不可删除；1不删除,0可以删除',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mc` (`mc_name`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='管理员权限';

-- table data `w_admin_power`

LOCK TABLES `w_admin_power` WRITE; -- 锁表操作
/*!40000 ALTER TABLE `w_admin_power` DISABLE KEYS */;
INSERT INTO `w_admin_power` (`id`,`mc_name`,`biaoshi_name`,`pid`,`icon`,`sort`,`is_sys`) VALUES ('2', 'back', '后台‘’管理', '0', 'icon-home', '0', '1'),('3', 'sys', '系统\'管理', '0', 'icon-settings', '0', '1'),('5', 'log', '日志\"管理\"', '0', 'icon-calendar', '0', '1'),('6', 'admin/User', '管理员用户', '1', '', '0', '1'),('8', 'admin/Power', '管理员权限', '1', '', '0', '1'),('9', 'back/Index', '后台首页', '2', '', '0', '1'),('11', 'back/Login', '登陆记录', '2', '', '0', '1'),('12', 'back/Operate', '操作记录', '2', '', '0', '1'),('14', 'sys/Stype', '设置分类', '3', '', '0', '1'),('15', 'sys/Sset', '系统设置', '3', '', '0', '1'),('17', 'ai/Page', 'AI文档', '4', '', '0', '1'),('18', 'ai/Html', '静态文件', '4', '', '0', '1'),('20', 'log/Operate', '操作记录', '5', '', '0', '1');
/*!40000 ALTER TABLE `w_admin_power` ENABLE KEYS */;
UNLOCK TABLES;


-- --------------------------------------------------


-- table structure `w_admin_role`

DROP TABLE IF EXISTS `w_admin_role`;
CREATE TABLE `w_admin_role` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `r_n` varchar(20) NOT NULL COMMENT '角色名称',
  `r_d` varchar(20) NOT NULL COMMENT '角色描述',
  `powers` varchar(255) NOT NULL COMMENT '角色权限',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='管理员角色';


-- --------------------------------------------------


-- table structure `w_admin_user`

DROP TABLE IF EXISTS `w_admin_user`;
CREATE TABLE `w_admin_user` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '用户名',
  `pass` varchar(40) NOT NULL COMMENT '密码',
  `r_id` tinyint(3) unsigned NOT NULL COMMENT '角色',
  `email` varchar(40) NOT NULL COMMENT 'email',
  PRIMARY KEY (`id`),
  UNIQUE KEY `n` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='管理员用户';

-- table data `w_admin_user`

LOCK TABLES `w_admin_user` WRITE; -- 锁表操作
/*!40000 ALTER TABLE `w_admin_user` DISABLE KEYS */;
INSERT INTO `w_admin_user` (`id`,`name`,`pass`,`r_id`,`email`) VALUES ('1', 'admin', 'a160e10adc3949ba59abbe56e057f20f883edd60', '1', 'php.develop@qq.com');
/*!40000 ALTER TABLE `w_admin_user` ENABLE KEYS */;
UNLOCK TABLES;


-- --------------------------------------------------


-- table structure `w_ai_nav`

DROP TABLE IF EXISTS `w_ai_nav`;
CREATE TABLE `w_ai_nav` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nav_name` varchar(20) NOT NULL COMMENT '导航名称',
  `nav_biaoshi` varchar(20) NOT NULL COMMENT '导航标识名',
  `keyword` varchar(20) NOT NULL COMMENT 'keyword',
  `description` varchar(50) NOT NULL COMMENT 'description',
  `sort` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `is_show` varchar(1) NOT NULL DEFAULT '1' COMMENT '是否显示;1显示,0不显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='AI导航';


-- --------------------------------------------------


-- table structure `w_ai_page`

DROP TABLE IF EXISTS `w_ai_page`;
CREATE TABLE `w_ai_page` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `tit` varchar(20) NOT NULL COMMENT '标题',
  `keyword` varchar(20) NOT NULL COMMENT 'keyword',
  `description` varchar(50) NOT NULL COMMENT 'description',
  `t` datetime NOT NULL COMMENT '时间',
  `con` text NOT NULL COMMENT '时间',
  `sort` tinyint(3) unsigned NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='AI文档';


-- --------------------------------------------------
