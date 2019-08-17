LOCK TABLES `w_sys_stype` WRITE; -- 锁表操作
/*!40000 ALTER TABLE `w_sys_stype` DISABLE KEYS */;
INSERT INTO `w_sys_stype` (`id`,`systype`) VALUES ('2', '时间设置'),('3', '邮件服务器配置'),('4', 'AI前端');
/*!40000 ALTER TABLE `w_sys_stype` ENABLE KEYS */;
UNLOCK TABLES;


-- --------------------------------------------------

SET foreign_key_checks=1;  -- 开启外键约束

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;