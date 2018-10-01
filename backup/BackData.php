<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-27
 * Time: 下午2:05
 * 备份数据调用
 */
namespace backup;

Class BackData{
    function __construct($host='127.0.0.1',$db,$dbuser='',$dbpw='',$table=array(),$charset='utf8',$prot=3306)
    {
        if(extension_loaded("pdo"))
        {
            $backup=new PdoSql($host,$db,$dbuser,$dbpw,$table,$charset,$prot);
        }else
        {
            $backup=new MySql($host,$db,$dbuser,$dbpw,$table,$charset,$prot);
        }
        // sql_insert 是父类下的方法
        $backup->sql_insert(); // 执行写入操作【备份】
    }
}




   

