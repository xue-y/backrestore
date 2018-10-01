<?php
/**
* 备份调用示例
*/
namespace backup;

require "../vendor/autoload.php";

//$table_name=["dede_archives"];  // php 5.4+
$table_name=array("self_limit"); // php 5.4- 备份指定表
// 提示文字 在 Backup.php 300 line
//$host='127.0.0.1',$db,$dbuser='',$dbpw='',$table=array(),$charset='utf8',$prot=3306
new BackData('127.0.0.1','test','root','admin',$table_name);