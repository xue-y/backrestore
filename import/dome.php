<?php 
/**
 * 还原调用示例
*/
namespace import;

require "../vendor/autoload.php";
//$back_name,$host,$db,$dbuser='',$dbpw='',$charset='utf8',$prot=3306,$is_del=true
// 提示文字 在 Import.php 290 line
new ImportData("t",'127.0.0.1','test','root','admin');