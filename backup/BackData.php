<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-27
 * Time: 下午2:05
 * 备份数据调用类
 */
namespace backup;

Class BackData{

    /** 执行备份数据
     * @parem $host        主机
     * @parem $db          要还原的数据库
     * @parem $dbuser      数据库用户名
     * @parem $dbpw        数据密码
     * @parem $table       指定要还原的数据表，数组形式，默认为空
     * @paerm $charset     数据库字符集
     * @parem $prot        数据库端口
     * */
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




   

