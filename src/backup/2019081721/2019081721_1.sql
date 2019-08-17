

-- table structure `w_log_login`

DROP TABLE IF EXISTS `w_log_login`;
CREATE TABLE `w_log_login` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `uid` tinyint(3) unsigned NOT NULL COMMENT '登录用户ID',
  `t` datetime NOT NULL COMMENT '登录时间',
  `shebie` varchar(100) NOT NULL COMMENT '登录设备',
  `ip` varchar(20) NOT NULL COMMENT '登录IP',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='登陆记录';

-- table data `w_log_login`

LOCK TABLES `w_log_login` WRITE; -- 锁表操作
/*!40000 ALTER TABLE `w_log_login` DISABLE KEYS */;
INSERT INTO `w_log_login` (`id`,`uid`,`t`,`shebie`,`ip`) VALUES ('1', '1', '2018-12-31 15:49:10', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),('2', '1', '2018-12-31 15:50:19', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),('4', '1', '2019-01-01 09:48:12', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),('5', '1', '2019-01-01 19:45:09', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),('6', '1', '2019-01-01 19:48:29', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1');
/*!40000 ALTER TABLE `w_log_login` ENABLE KEYS */;
UNLOCK TABLES;


-- --------------------------------------------------


-- table structure `w_log_operate`

DROP TABLE IF EXISTS `w_log_operate`;
CREATE TABLE `w_log_operate` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `uid` tinyint(3) unsigned NOT NULL COMMENT '管理员ID',
  `t` datetime NOT NULL COMMENT '操作时间',
  `behavior` tinyint(4) unsigned NOT NULL COMMENT '操作行为',
  `details` varchar(255) NOT NULL COMMENT '操作详情',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='操作记录';


-- --------------------------------------------------


-- table structure `w_sys_sset`

DROP TABLE IF EXISTS `w_sys_sset`;
CREATE TABLE `w_sys_sset` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `systid` tinyint(3) unsigned NOT NULL COMMENT '设置项类型',
  `syskey` varchar(20) NOT NULL COMMENT '设置项名称',
  `sysval` varchar(255) NOT NULL COMMENT '设置项值;多个值中间用英文逗号分隔',
  `notes` varchar(50) DEFAULT NULL COMMENT '设置项说明',
  `is_sys` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否系统内置;系统内置不可删除；1不删除,0可以删除；添加后不可修改',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=114 DEFAULT CHARSET=utf8 COMMENT='系统设置';

-- table data `w_sys_sset`

LOCK TABLES `w_sys_sset` WRITE; -- 锁表操作
/*!40000 ALTER TABLE `w_sys_sset` DISABLE KEYS */;
INSERT INTO `w_sys_sset` (`id`,`systid`,`syskey`,`sysval`,`notes`,`is_sys`) VALUES ('1', '1', 'user_only_sign', '0', '是否允许同一账号多设备终端同时登陆1允许0不允许', '1'),('2', '1', 'back_top_nav', '10,13', '后台顶部导航ID', '1'),('6', '3', 'smtp_server', 'smtp.sina.com', '新浪SMTP邮箱服务器', '1'),('7', '3', 'smtp_server_port', '25', 'SMTP服务器端口', '1'),('8', '3', 'smtp_user_email', '', 'SMTP服务器的用户邮箱账号', '1'),('10', '3', 'smtp_pass', '', 'SMTP服务器的用户密码', '1'),('3', '1', 'pass_error_num', '5', '管理员登录密码错误次数，超过限制自动封锁，lock_t 时间后自动解锁', '1'),('17', '4', 'web_title', '智能小工具', 'AI 站点网站名称', '1'),('4', '2', 'email_interval_t', '120', '发送邮件时间间隔，时间单位秒', '1'),('19', '1', 'email_send_c', '50', 'email_t 时间内可发送的邮件次数', '1'),('20', '2', 'lock_t', '86400', '登录密码错误解封时间', '1'),('21', '2', 'email_t', '86400', '邮件发送超过最大限额多长时间后可以再次发送，单位是秒', '1'),('22', '2', 'email_activate_t', '1800', '发送邮件后邮件有效时间内激活，单位是秒', '1'),('18', '4', 'web_foot', '个人学习交流网站<br>有问题请联系 <a href=\"https://mail.qq.com\">php.develop@qq.com</a>', '网站底部', '1'),('27', '4', 'img_word_imgsize', '4194304', '图片提取文字上传图片的大小限制，单位字节', '1'),('28', '4', 'img_word_imgtype', '图片提取文字允许的图片类型', '', '1'),('111', '1', 'user_only_sign', '0', '是否允许同一账号多设备终端同时登陆1允许0不允许', '1'),('112', '2', '', '', '', '0'),('113', '2', '', '', '', '0');
/*!40000 ALTER TABLE `w_sys_sset` ENABLE KEYS */;
UNLOCK TABLES;


-- --------------------------------------------------


-- table structure `w_sys_stype`

DROP TABLE IF EXISTS `w_sys_stype`;
CREATE TABLE `w_sys_stype` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `systype` varchar(30) NOT NULL COMMENT '分类名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='设置分类';

-- table data `w_sys_stype`

LOCK TABLES `w_sys_stype` WRITE; -- 锁表操作
/*!40000 ALTER TABLE `w_sys_stype` DISABLE KEYS */;
INSERT INTO `w_sys_stype` (`id`,`systype`) VALUES ('1', '后端');
/*!40000 ALTER TABLE `w_sys_stype` ENABLE KEYS */;
UNLOCK TABLES;