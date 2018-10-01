<?php
/**
 * 还原数据调用
*/
namespace import;

Class ImportData {
    function __construct($back_name,$host='127.0.0.1',$db,$dbuser='',$dbpw='',$charset='utf8',$prot=3306,$is_del=true)
    {
        if(extension_loaded("pdo"))
        {
            $backup=new PdoSql($back_name,$host,$db,$dbuser,$dbpw,$charset,$prot,$is_del);

        }else
        {
            $backup=new MySql($back_name,$host,$db,$dbuser,$dbpw,$charset,$prot,$is_del);
        }
        // sql_insert 是父类下的方法
        $backup->sql_insert(); // 执行写入操作【还原】
    }
}
