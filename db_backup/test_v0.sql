-- 主机IP：localhost::1
-- Web服务器：Apache
-- PHP 运行方式：apache2handler
-- PHP版本：5.5.38
-- MySql版本-pdo：5.5.53-log
-- 生成日期：2018-10-02 11:08:08

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- 
-- 数据库: `test`
-- 

-- ##* self_limit ##* --

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
