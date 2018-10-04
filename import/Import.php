<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-27
 * Time: 下午3:15
 * 还原导入数据父类
 * 当前操作用户对备份目录有创建删除权限 数据表有创建删除的权限
 */

namespace import;
use PDO;

class Import {
    protected  $conn; // mysql  连接对象
    protected  $db; //数据库名称
    protected  $host;// 主机
    protected  $dbuser;// 数据库用户名称
    protected  $dbpw;  // 数据库密码
    protected  $prot; // 数据库端口
    public     $charset;// 字符编码
	public	    $back_dir='../db_backup/'; //还原备份数据目录
    public     $import_table_fu=" ##* ";// 导入数据表用于取得表名的分隔符
    protected  $back_file;// 还原备份文件数组
    public     $back_file_fu="_v";
    protected  $log_dir="./log/";
    protected  $log_file="./log/import_log.txt"; //日志目录
	public	    $back_name;	// 要还原的备份名前缀

    public function __construct($back_name,$host,$db,$dbuser,$dbpw,$charset,$prot,$is_del)
    {
        if(empty($back_name))
        {
            exit("请填写要还原的sql 文件名前缀");
        }
        header("content-type:text/html;charset=$charset");
        set_time_limit(0);//无时间限制
        date_default_timezone_set('PRC');// 设置时区
        $this->db=$db;
        $this->host = $host;
        $this->dbuser = $dbuser;
        $this->dbpw = $dbpw;
        $this->back_name=$back_name;//如果文件名中存在 _v0 代表只有一卷（还原一个文件）， v1 代表多卷（还原多个文件）
        $this->charset = $charset;
        $this->prot= $prot;
        $this->del_file=$is_del; // 是否删除备份目录，默认导入数据成功后删除
        $this->back_file=$this->read_backdir(); // 备份文件数组
        $this->conn(); // 初始化调用连接数据库
        $this->log_dir();// 创建日志文件夹
    }

    // 取得要还原的备份文件名
    public function read_backdir()
    {
        if(!is_dir($this->back_dir))
        {
            exit("备份目录错误");
        }
        if(!is_writable($this->back_dir))
        {
            exit("您没有对备份目录的操作权限，请修改备份目录的读写执行权限");
        }
        $res=opendir($this->back_dir);
        if(!$res)
        {
            exit("打开目录失败");
        }
      //  $back_f_arr=[]; // php 5.4+
	    $back_f_arr=array();// php 5.4-
        while(($back_f=readdir($res))!==false)
        {
            if(preg_match("/".$this->back_name.$this->back_file_fu."(\d+)/",$back_f,$back_f))
            {
                $back_f_arr[]=$back_f[1];
            }
        }
        if(empty($back_f_arr))
        {
            exit("没有您指定的还原文件");
        }
        sort($back_f_arr);
        return $back_f_arr;
    }

    // 根据备份文件中的sql_table()函数，表结构 sql 语句分割符
    public function table_fu()
    {
        $create_table=PHP_EOL;
        $create_table.='-- '.str_repeat('--',30).PHP_EOL;
        $create_table.=PHP_EOL;
        return $create_table;
    }

    // 取得第一卷
    protected function  head_file()
    {
        //拼接 头文件判断文件是否存在 如果是 _v0 代表是只有一个文件 还原是只还原一个文件的
        if(in_array(0,$this->back_file))
        {
            return $this->back_name.$this->back_file_fu."0.sql";
        }else
        {
            // 还原多个文件
            if(in_array(1,$this->back_file))
            {
                return $this->back_name.$this->back_file_fu."1.sql";
            }else
            {
                exit("缺少要还原的头文件");
            }
        }
    }

    // 取得要还原的数据表名
    public function table_name()
    {
        // 从文件中取得数据表名--- 拼接第一卷备份文件名称
        $first_file= $this->head_file();
        $fileCont = file_get_contents($this->back_dir.$first_file);

        try{
            $table=explode($this->import_table_fu,$fileCont);
		     
			if(!isset($table[1]))
			{
				   exit("备份文件中缺少要还原的标注表名1");
			}
            $table_name=explode("|",$table[1]); // 备份是的数据表名格式是 以 | 连接的，这里要一致
			
        }catch (\Exception $e)
        {
            die("Error: " . $e->getMessage());
        }

        if(empty($table_name))
        {
            exit("备份文件中缺少要还原的标注表名2");
        }
        return $table_name;
    }


    // 判断要还原的是一卷 还是多卷
    public function is_files()
    {
        $head_file=$this->head_file();
        $file_id=explode($this->back_file_fu,$head_file);
        return $file_id;
    }

    // 创建日志文件夹
    public function log_dir()
    {
        if(!is_dir($this->log_dir))
        {
            $bool=mkdir($this->log_dir,0777);
            if(!$bool)
            {
                exit('创建日志目录失败');
            }
        }
        if(!is_writable($this->log_dir))
        {
            exit('日志目录不可写');
        }
    }

    // 删除备份文件 $is_del 没有返回值
    public function del_back_files()
    {
        // 先知道还原的哪个文件
        //然后执行删除
        $back_dir=$this->read_backdir();
        if(in_array(0,$back_dir)) // 如果是 _v0 代表是只有一个文件
        {
            $f_name=$this->back_dir.$this->back_name.$this->back_file_fu."0.sql";
            $bool=unlink($f_name);
            if(!$bool)
            {
                exit("数据还原成功，备份文件删除失败".$f_name);
            }
        }else
        {   // 如果没有 _v0 代表多个文件
            $error_info="";
            foreach($back_dir as $k=>$v)
            {
                $f_name=$this->back_dir.$this->back_name.$this->back_file_fu.$v.".sql";
                $bool=@unlink($f_name);
                if(!$bool)
                {
                    $error_info.=date("Y-m-d H:i:s",time())." | 备份文件 ".$f_name." 删除失败".PHP_EOL.PHP_EOL;
                    $f_len=@file_put_contents($this->log_file,$error_info,FILE_APPEND);
                }
            }
            if(!empty($error_info) && $f_len===false)
            {
                exit("数据还原成功，备份文件删除失败,写入日志文件失败");
            }
            if(!empty($error_info))
            {
                exit("数据还原成功，备份文件删除失败,错误信息请查看日志");
            }
        }
    }

} 