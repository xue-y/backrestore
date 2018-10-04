-- 主机IP：localhost::1
-- Web服务器：Apache
-- PHP 运行方式：apache2handler
-- PHP版本：5.5.38
-- MySql版本-pdo：5.5.53-log
-- 生成日期：2018-10-04 14:33:32

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- 
-- 数据库: `shop`
-- 


-- ------------------------------------------------------------

--
--  转储表结构 `ecs_account_log`
--

DROP TABLE IF EXISTS `ecs_account_log3`;
CREATE TABLE `ecs_account_log3` (
  `log_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `user_money` decimal(10,2) NOT NULL,
  `frozen_money` decimal(10,2) NOT NULL,
  `rank_points` mediumint(9) NOT NULL,
  `pay_points` mediumint(9) NOT NULL,
  `change_time` int(10) unsigned NOT NULL,
  `change_desc` varchar(255) NOT NULL,
  `change_type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

--
--  转储表数据 `ecs_account_log`
--

INSERT INTO `ecs_account_log3`(`log_id`, `user_id`, `user_money`, `frozen_money`, `rank_points`, `pay_points`, `change_time`, `change_desc`, `change_type`) VALUES (1,2,'1100000.00','0.00',0,0,1242140736,'11',2),
(2,1,'400000.00','0.00',0,0,1242140752,'21312',2),
(3,2,'300000.00','0.00',0,0,1242140775,'300000',2),
(4,1,'50000.00','0.00',0,0,1242140811,'50',2),
(5,2,'0.00','10000.00',0,0,1242140853,'32',2),
(6,1,'-400.00','0.00',0,0,1242142274,'支付订单 2009051298180',99),
(7,1,'-975.00','0.00',0,0,1242142324,'支付订单 2009051255518',99),
(8,1,'0.00','0.00',960,960,1242142390,'订单 2009051255518 赠送的积分',99),
(9,1,'0.00','0.00',385,385,1242142432,'订单 2009051298180 赠送的积分',99),
(10,1,'-2310.00','0.00',0,0,1242142549,'支付订单 2009051267570',99),
(11,1,'0.00','0.00',2300,2300,1242142589,'订单 2009051267570 赠送的积分',99),
(12,1,'-5989.00','0.00',0,0,1242142681,'支付订单 2009051230249',99),
(13,1,'-8610.00','0.00',0,0,1242142808,'支付订单 2009051276258',99),
(14,1,'0.00','0.00',0,-1,1242142910,'参加夺宝奇兵夺宝奇兵之夏新N7 ',99),
(15,1,'0.00','0.00',0,-1,1242142935,'参加夺宝奇兵夺宝奇兵之诺基亚N96 ',99),
(16,1,'0.00','0.00',0,100000,1242143867,'奖励',2),
(17,1,'-10.00','0.00',0,0,1242143920,'支付订单 2009051268194',99),
(18,1,'0.00','0.00',0,-17000,1242143920,'支付订单 2009051268194',99),
(19,1,'0.00','0.00',-960,-960,1242144185,'由于退货或未发货操作，退回订单 2009051255518 赠送的积分',99),
(20,1,'975.00','0.00',0,0,1242144185,'由于取消、无效或退货操作，退回支付订单 2009051255518 时使用的预付款',99),
(21,1,'0.00','0.00',960,960,1242576445,'订单 2009051719232 赠送的积分',99),
(22,1,'-1000.00','0.00',0,0,1242973612,'追加使用余额支付订单：2009051227085',99),
(23,1,'-13806.60','0.00',0,0,1242976699,'支付订单 2009052224892',99),
(24,1,'0.00','0.00',14045,14045,1242976740,'订单 2009052224892 赠送的积分',99),
(25,1,'0.00','0.00',-2300,-2300,1245045334,'由于退货或未发货操作，退回订单 2009051267570 赠送的积分',99),
(26,1,'2310.00','0.00',0,0,1245045334,'由于取消、无效或退货操作，退回支付订单 2009051267570 时使用的预付款',99),
(27,1,'0.00','0.00',17044,17044,1245045443,'订单 2009061585887 赠送的积分',99),
(28,1,'17054.00','0.00',0,0,1245045515,'1',99),
(29,1,'0.00','0.00',-17044,-17044,1245045515,'由于退货或未发货操作，退回订单 2009061585887 赠送的积分',99),
(30,1,'-3196.30','0.00',0,0,1245045672,'支付订单 2009061525429',99),
(31,1,'-1910.00','0.00',0,0,1245047978,'支付订单 2009061503335',99),
(32,1,'0.00','0.00',1900,1900,1245048189,'订单 2009061503335 赠送的积分',99),
(33,1,'0.00','0.00',-1900,-1900,1245048212,'由于退货或未发货操作，退回订单 2009061503335 赠送的积分',99),
(34,1,'1910.00','0.00',0,0,1245048212,'由于取消、无效或退货操作，退回支付订单 2009061503335 时使用的预付款',99),
(35,1,'-500.00','0.00',0,0,1245048585,'支付订单 2009061510313',99);

-- ------------------------------------------------------------

--
--  转储表结构 `ecs_ad`
--

DROP TABLE IF EXISTS `ecs_ad3`;
CREATE TABLE `ecs_ad3` (
  `ad_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `position_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `media_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ad_name` varchar(60) NOT NULL DEFAULT '',
  `ad_link` varchar(255) NOT NULL DEFAULT '',
  `ad_code` text NOT NULL,
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `link_man` varchar(60) NOT NULL DEFAULT '',
  `link_email` varchar(60) NOT NULL DEFAULT '',
  `link_phone` varchar(60) NOT NULL DEFAULT '',
  `click_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`ad_id`),
  KEY `position_id` (`position_id`),
  KEY `enabled` (`enabled`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
--  转储表数据 `ecs_ad`
--

INSERT INTO `ecs_ad3`(`ad_id`, `position_id`, `media_type`, `ad_name`, `ad_link`, `ad_code`, `start_time`, `end_time`, `link_man`, `link_email`, `link_phone`, `click_count`, `enabled`) VALUES (1,1,0,'测试广告2','','1462958213922967180.jpg',1462003200,1527667200,'','','',4,1),
(2,2,0,'测试广告2','','1462958236149500402.jpg',1462608000,1496736000,'','','',6,1),
(3,3,0,'测试广告3','','1462958248231413208.jpg',1462608000,1465200000,'','','',2,1),
(4,5,0,'首页广告1','#','1462847593436706583.jpg',1462780800,1465372800,'','','',1,1),
(5,6,0,'首页广告2','','1462847610270410022.jpg',1462780800,1465372800,'','','',1,1),
(6,7,0,'首页广告3','','1462847623202947787.jpg',1462780800,1465372800,'','','',0,1),
(7,8,0,'首页广告4','','1462847641920447649.jpg',1462780800,1465372800,'','','',0,1),
(8,4,0,'团购广告','','1462847712105834896.jpg',1462780800,1465372800,'','','',2,1),
(9,9,0,'1层左侧广告1','','1462847928058332752.jpg',1462780800,1465372800,'','','',1,1),
(10,10,0,'1层左侧广告2','','1462847949795308026.jpg',1462780800,1465372800,'','','',0,1),
(11,11,0,'1层左侧广告3','','1462848017200363691.jpg',1462694400,1465286400,'','','',0,1),
(12,12,0,'1层左侧广告4','','1462847997830622897.jpg',1462694400,1465286400,'','','',0,1),
(13,13,0,'2层左侧广告1','','1462850262891884765.jpg',1462694400,1465286400,'','','',0,1),
(14,14,0,'2层左侧广告2','','1462850292418967275.jpg',1462694400,1465286400,'','','',0,1),
(15,15,0,'3层左侧广告','','1462848133177102814.jpg',1462780800,1465372800,'','','',0,1);
