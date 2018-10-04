<?php
/**
* 备份调用示例
*/
namespace backup;

require "../vendor/autoload.php";


//$table_name=["dede_archives"]; php 5.4+
$table_name=array("ecs_account_log","ecs_ad");//php 5.4-

$back=new BackData();
//$host='127.0.0.1',$db,$dbuser='',$dbpw='',$table=array(),$charset='utf8',$prot=3306
$back->back_exec('127.0.0.1','shop','root','admin',$table_name);
