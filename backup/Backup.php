<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-24
 * Time: 下午1:35
 * 备份数据库 父类  Backup export
 * 当前操作用户对备份目录有创建删除权限 数据表有创建删除的权限
 */
namespace backup;
use PDO;

class Backup{
    protected  $conn;
    public  $db; //数据库名称
    protected  $host;// 主机
    protected  $dbuser;// 数据库用户名称
    protected  $dbpw;  // 数据库密码
    protected  $prot; // 数据库端口
    protected  $charset;// 字符编码
    public  $back_dir='../db_backup/'; //备份目录
    public  $size=2097152; // 默认分卷大小2MB; 2097152
   

    public  function __construct($host,$db,$dbuser,$dbpw,$table,$charset,$prot)
    {
        header("content-type:text/html;charset=$charset");
        set_time_limit(0);//无时间限制
        date_default_timezone_set('PRC');// 设置时区
        $this->db=$db;
        $this->host = $host;
        $this->dbuser = $dbuser;
        $this->dbpw = $dbpw;
        $this->charset = $charset;
        $this->prot= $prot;
        $this->table=$table;
        $this->conn(); // 初始化调用连接数据库
    }
  
    // 创建备份文件夹
    public  function back_dir()
    {
        if(!is_dir($this->back_dir))
        {
            $bool=mkdir($this->back_dir,0777);
            if(!$bool)
            {
                exit('创建备份目录失败');
            }
        }
        if(!is_writable($this->back_dir))
        {
            exit('备份目录不可写');
        }
    }

    /**判断用户传入的数据表数据库中是否存在
     * @parem $table_all 数据库中所有的表
     * @return 用户传入的表 也可返回true  调用直接使用 $this->table
     * */
    protected function table_is($table_all)
    {
        $table_name=array_intersect($table_all,$this->table);
        if(count($table_name)!=count($this->table))
        {
            $table_no=array_diff($this->table,$table_name);
            exit("数据库中 ".implode(" , ",$table_no)." 数据表不存在");
        }
        return $table_name;
    }

}