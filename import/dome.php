<?php 
/**
 * 还原调用函数
 */
namespace import;

require "../vendor/autoload.php";

$import=new ImportData();
// 调用示例
//$back_name,$host='127.0.0.1',$db,$dbuser='',$dbpw='',$charset='utf8',$prot=3306,$is_del=true
$import->import_exec("shop",'127.0.0.1','test','root','admin','utf8',3306,false,false);