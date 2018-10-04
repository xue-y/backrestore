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
    protected  $charset;// 字符编码
    protected  $back_dir='../db_backup/'; //还原备份数据目录
    protected  $import_table_fu=" ##* ";// 导入数据表用于取得表名的分隔符
    protected  $back_file;// 还原备份文件数组
    protected  $back_file_fu="_v";
    protected  $log_dir="./log/";
    protected  $log_file="./log/import_log.txt"; //日志目录---用于删除备份文件失败
    protected  $table_head;// 是否忽略标注的表名,默认true读取第一卷开头标注的表名，false 不读取

    public  function __construct($back_name,$host,$db,$dbuser,$dbpw,$charset,$prot,$is_del,$table_head)
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
        $this->back_name=$back_name;// 要还原的备份名前缀 ,如果文件名中存在 _v0 代表只有一卷（还原一个文件）， v1 代表多卷（还原多个文件）
        $this->charset = $charset;
        $this->prot= $prot;
        $this->del_file=$is_del; // 是否删除备份目录，默认导入数据成功后删除

        $this->back_file=$this->read_backdir(); // 备份文件数组

        $this->conn(); // 初始化调用连接数据库

        $this->log_dir();// 创建日志文件夹

        $this->table_head=$table_head; // 统计表名称

    }

    // 取得要还原的备份文件名
    protected   function read_backdir()
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
    protected function table_fu()
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
    protected  function table_name()
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

    /**需要还原的数据库表名如果与原数据库中的表名一致，更改原表名为 temp_表名，用于还原失败的时候还原回去【还原到没有执行还原之前的数据】
     * @parem $old_table取得现在数据库中的所有表名
     * @parem $new_table 取得要还原的数据表名
     * @return 更改后的临时表名
     * */
    protected function temp_table($old_table,$new_table)
    {
        // 取得相同的名称修改成临时名称
        if(empty($old_table))
        {
            return  false;
        }
        $temp_table=array_intersect($old_table,$new_table);
        if(empty($temp_table))
        {
            return  false;
        }
        $rename="";
        foreach($temp_table as $k=>$v)
        {
            $rename.="`".$v.'` to `temp_'.$v."`,";
            $temp_table2[]="temp_".$v;
        }
        $this->temp_table_exec($rename);  // -----------------------------调用的子类的方法
        return $temp_table2;

    }

    // 判断要还原的是一卷 还是多卷
    protected function is_files()
    {
        $head_file=$this->head_file();
        $file_id=explode($this->back_file_fu,$head_file);
        return $file_id;
    }

    // 创建日志文件夹
    protected  function log_dir()
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
    protected  function del_back_files()
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

    // 执行还原数据
    public function sql_insert()
    {
        // echo "开始还原";
		
        $is_files=$this->is_files(); //判断要还原的是一卷 还是多卷

        $table_fu=$this->table_fu(); // sql 文件表与表之间的分割符

        if($this->table_head) // 是否忽略标注的表名
        {
            $table_name=$this->table_name();   // 取得这次插入的表名
            $all_table=$this->old_table(); // 取得数据库中的所有表名----------------------调用的子类方法
            $temp_table=$this->temp_table($all_table,$table_name); // 取得数据库中的原表名的临时表名
        }else
        {
            $table_name=array();
            $temp_table=array();
        }
		
        if(intval($is_files[1])===0)
        {
            // 只有一卷
            $first_file=$this->back_name.$this->back_file_fu."0.sql";;
            $fileCont = file_get_contents($this->back_dir.$first_file);
            $sql_arr=explode($table_fu,$fileCont);

            // 去除备份文件头标识--- 一般为sql注释信息
             array_splice($sql_arr,0,1);

            // 执行还原
            $this->sql_exec($sql_arr,$table_name,$temp_table); //----------------------调用的子类方法

        }else{
            // 多卷
            $back_files=$this->read_backdir();

            foreach($back_files as $f_k=>$f_v)
            {
                $first_file=$this->back_name.$this->back_file_fu.$f_v.".sql";
                $fileCont = file_get_contents($this->back_dir.$first_file);
                $sql_arr=explode($table_fu,$fileCont);

                if($f_v==1)
                {
                    array_splice($sql_arr,0,1); // 去除备份文件头标识
                }
                //执行还原
                $this->sql_exec($sql_arr,$table_name,$temp_table); //----------------------调用的子类方法
            }
        }

        // 还原成功后删除 原数据临时表--如果存在临时表
       if($temp_table)
        {
            $this->del_temp_table($temp_table); //----------------------调用的子类方法
        }
        // 执行删除备份文件
        if($this->del_file)
        {
            $this->del_back_files();
        }
        echo("还原成功"); //  还原成功
    }

} 