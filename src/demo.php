<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2019/7/26
 * Time: 21:12
 */

namespace src;
require "../vendor/autoload.php";

// 配置项-----必传值
$config=[
      'db'  =>  'aa',           // 数据库名称
      'host'=>  '127.0.0.1',    // 主机
      'dbuser'=>'root',         // 数据库用户名称
      'dbpw'=>  'root',        // 数据库密码
      'charset' =>'utf8',      // 字符集
   //   'compress'=>true,		 // 是否压缩，默认不压缩
];

// 备份操作
/*$exec_sql=new Backup($config);
$table=[];
// 要备份的表名，备份整个数据库 null，备份指定前缀的数据表(参数是字符串)，备份指定表名的数据表(参数数组形式)
$exec_sql->wirteFile($table);*/

// 还原数据库
$import_sql=new Import($config);
$import_sql->readFile('201908172151');


/*$optimize_repair=new OptimizeRepair($config);
$table=[];
// 要操作的表名，操作整个数据库 null，操作指定前缀的数据表(参数是字符串)，操作指定表名的数据表(参数数组形式)
//$optimize_repair->optimize($table); // 优化表
$optimize_repair->repair($table);  // 修复表*/