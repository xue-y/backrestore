<?php
/**
 * 还原数据调用类
*/
namespace import;

Class ImportData {

    /** 执行还原数据
     * @parem $back_name   还原的sql 文件名前缀 例如还原数据文件 test_v0 ,直接写test
     * @parem $host        主机
     * @parem $db          要还原的数据库
     * @parem $dbuser      数据库用户名
     * @parem $dbpw        数据密码
     * @paerm $charset     数据库字符集
     * @parem $prot        数据库端口
     * @parem $is_del      还原完成后是否删除备份文件，默认true 删除
     * @parem $table_head  是否忽略还原文件标注的表名
     * */
    function __construct($back_name,$host='127.0.0.1',$db,$dbuser='',$dbpw='',$charset='utf8',$prot=3306,$is_del=true,$table_head=true)
    {
        if(extension_loaded("pdo"))
        {
            $backup=new PdoSql($back_name,$host,$db,$dbuser,$dbpw,$charset,$prot,$is_del,$table_head);

        }else
        {
            $backup=new MySql($back_name,$host,$db,$dbuser,$dbpw,$charset,$prot,$is_del,$table_head);
        }
        // sql_insert 是父类下的方法
        $backup->sql_insert(); // 执行写入操作【还原】
    }
}
