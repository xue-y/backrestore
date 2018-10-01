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
    protected  $db; //数据库名称
    protected  $host;// 主机
    protected  $dbuser;// 数据库用户名称
    protected  $dbpw;  // 数据库密码
    protected  $prot; // 数据库端口
    protected  $charset;// 字符编码
    protected  $back_dir='../db_backup/'; //备份目录
    protected  $size=2097152; // 默认分卷大小2MB; 2097152
	protected  $import_table_fu=" ##* ";// 导入数据表用于取得表名的分隔符
    protected  $back_file_fu="_v";

    public function __construct($host='127.0.0.1',$db,$dbuser='',$dbpw='',$table=array(),$charset='utf8',$prot=3306)
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

    // 分卷标识头部
    protected function sql_head()
    {
		/* 使用phpMyAdmin 导出数据库格式
		-- phpMyAdmin SQL Dump
		-- version 3.4.10.1
		-- http://www.phpmyadmin.net
		--
		-- 主机: localhost
		-- 生成日期: 2018 年 04 月 25 日 12:19
		-- 服务器版本: 5.5.20
		-- PHP 版本: 5.3.10
		/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT *///;
        /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS *///;
        /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION *///;
        /*!40101 SET NAMES utf8 *///;

		$sql_head="-- 主机IP：".$_SERVER['SERVER_NAME'].$_SERVER['SERVER_ADDR'].PHP_EOL;
        $sql_head.="-- Web服务器：".$_SERVER['SERVER_SOFTWARE'].PHP_EOL;
        $sql_head.="-- PHP 运行方式：".PHP_SAPI.PHP_EOL;
        $sql_head.="-- PHP版本：".PHP_VERSION.PHP_EOL;
		
		$sql_head.=$this->mysql_v();  //---------------------------调用子类的方法
       
        $sql_head.="-- 生成日期：".date("Y-m-d H:i:s",time()).PHP_EOL.PHP_EOL;
        $sql_head.='SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";'.PHP_EOL;
        $sql_head.='SET time_zone = "+00:00";'.PHP_EOL.PHP_EOL;
        $sql_head.='-- '.PHP_EOL;
        $sql_head.='-- 数据库: `'.$this->db.'`'.PHP_EOL;
        $sql_head.='-- '.PHP_EOL;
        /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT *///;
        /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS *///;
        /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION *///;
        return $sql_head;
    }

    // 创建备份文件夹
    protected function back_dir()
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
	
    // 每个表结构sql 语句--- 根据表名查询 表结构语句
    protected function sql_table($table_name)
    {
        $row=$this->table_structure($table_name); // 查询表结构语句---------------------子类方法

        $create_table=PHP_EOL;
        $create_table.='-- '.str_repeat('--',30).PHP_EOL;
        $create_table.=PHP_EOL;
		
        $create_table.='--'.PHP_EOL;
        $create_table.='--  转储表结构 `'.$table_name.'`'.PHP_EOL;
        $create_table.='--'.PHP_EOL.PHP_EOL;
        $create_table.="DROP TABLE IF EXISTS `".$table_name."`;".PHP_EOL;
        $create_table.=$row.';'.PHP_EOL.PHP_EOL;
        $create_table.='--'.PHP_EOL;
        $create_table.='--  转储表数据 `'.$table_name.'`'.PHP_EOL;
        $create_table.='--'.PHP_EOL.PHP_EOL;
        return $create_table;
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
	
	//表插入语句
    public function sql_insert()
    {
      //  echo "开始备份";

        $sql='';
        $strlen=0; // 记录本次sql 语句长度
        $table_name=$this->table_name(); //---------------------------------------------调用子类方法
		
        $this->back_dir();
		
        $p=1;// 分卷标识
        // 标记 备份的表名 用于还原
        $table_name2=PHP_EOL."--".$this->import_table_fu.implode("|",$table_name).$this->import_table_fu."--".PHP_EOL;
        $sql_head=$this->sql_head().$table_name2; // 文件标识 只在第一分卷有

        // 备份文件名可以自定义
       // $back_file=$this->back_dir.$this->db.'_'.date('Ymd',time()).$this->back_file_fu;
        $back_file=$this->back_dir.$this->db.$this->back_file_fu;
        $value_str=$value_str3='';
        $last_str_num=strlen(PHP_EOL)+1;

        foreach($table_name as $k=>$v) // 循环所有的表
        {
            //   echo " 开始备份".$v."表".PHP_EOL;

            $lock_re=$this->lock_table($v); // 锁表  --------------------------------------子类方法

            $table_field_type=$this->field_type($v); // 字段类型 --------------------------------------子类方法

            $sql_table=$this->sql_table($v);    // 表结构sql 语句

            $strlen=strlen($sql_table); // 记录sql 长度

            $insert=$this->select_insert($v); // 表数据  --------------------------------------子类方法

            $insert_c=count($insert)-1; // 总的记录数用于判断是不是最后一条插入语句

            if(!empty($insert))     //如果表中有数据 循环表中的数据
            {
                $key='`'.implode("`, `",array_keys($insert[0])).'`';

                $cur_table_data=0;// 记录当前表的数据的分卷个数

                foreach($insert as $kk=>$vv)  // 循环表中所有数据
                {
                    $value=array_values($vv);
                    foreach($value as $kkk=>$vvv)  // 循环一条数据的字段---判断值是否加 引号
                    {
                        if(preg_match('/int/',$table_field_type[$kkk]))
                        {
                            $value_str.=$vvv.',';
                        }else
                        {
                            $value_str.="'".addslashes($vvv)."',";
                        }
                    }
                    // 判断是不是最后一条插入语句-- 是否加逗号 是否换行
                    if($kk==$insert_c)
                    {
                        $value_str2="(".substr($value_str,0,-1).")";
                    }else
                    {
                        $value_str2="(".substr($value_str,0,-1)."),".PHP_EOL;
                    }

                    $value_str=''; // 用于拼接value 值，用完清空
                    $value_str3.=$value_str2;
                    // 标识 插入语句是否需要分卷
                    $is_insert_fenjuan=false;

                    // 插入数据最大容许字节---防止一个表中的数据条数过多
                    //如果一条插入 语句超出了你设置的分卷最大限度，那分卷的大小为这条insert 语句的大小
                    // 如果是分卷第一卷 加上 表结构语句
                    if($p==1 && ((strlen($sql_head)+strlen($sql_table)+strlen($value_str3)) >= $this->size-strlen($value_str2)))
                    {
                        $write_sql=$sql_head.$sql_table.$sql;
                        $is_insert_fenjuan=true;
                    }else if(strlen($value_str3) >= $this->size-strlen($value_str2))
                    {
                        $write_sql='';
                        $is_insert_fenjuan=true;
                    }
                    if($is_insert_fenjuan==true)
                    {
                        $value_str3=substr($value_str3,0,-$last_str_num);
                        $cur_table_data++;
                        if($cur_table_data==1 && $p!=1) // 不是第一卷的时候
                        {
                            $write_sql.=$sql_table."INSERT INTO `".$v."`(".$key.") VALUES ".$value_str3.";".PHP_EOL;
                        }else
                        {
                            $write_sql.="INSERT INTO `".$v."`(".$key.") VALUES ".$value_str3.";".PHP_EOL;
                        }

                        $write_sql_file=file_put_contents($back_file.$p.'.sql',$write_sql);
                        $p++;
                        if($write_sql_file!=strlen($write_sql))
                        {
                            exit("写入备份文件失败1");
                        }
                        $value_str3=$write_sql='';
                    }
                }//-----------------------------------------------------循环每条数据

            } //-------如果表中有数据 循环表中的数据

            if(!empty($value_str3))  // 有数据时 --- 一个表中的数据不足限制的大小时
            {
                $cur_sql="INSERT INTO `".$v."`(".$key.") VALUES ".$value_str3.";".PHP_EOL;

                if($cur_table_data>=1)  // 如果上一个 插入数据没有写完，剩下一部分不够一个分卷时
                {
                    $sql=$cur_sql.$sql;
                }else
                {
                    $sql.=$sql_table.$cur_sql;
                }
                $strlen=strlen($cur_sql);
                $value_str3='';
            }
            else                   // 如果没有数据，添加存储表结构
            {
                $sql.=$sql_table;
            }

            // 标识 表结构 数据语句是否需要分卷
            $is_insert_fenjuan2=false;
            if($p==1 && ((strlen($sql_head)+strlen($sql))>=$this->size-$strlen))
            {
                $write_sql=$sql_head.$sql;
                $is_insert_fenjuan2=true;

            }else if(strlen($sql)>=$this->size-$strlen)
            {
                $write_sql=$sql;
                $is_insert_fenjuan2=true;
            }
            if($is_insert_fenjuan2==true)
            {
                $write_sql_file=file_put_contents($back_file.$p.'.sql',$write_sql);
                $p++;
                if($write_sql_file!=strlen($write_sql))
                {
                    exit("写入备份文件失败2");
                }
                $sql=$write_sql='';
            }

            $this->unlock_table($lock_re);  // 解表  --------------------------------------子类方法

            //    echo "备份".$v."表结束".PHP_EOL;
        }// ------------------------------循环所有的表
        $write_sql="";
        if($p==1) //如果最后总的数据大小没有到达 2MB
        {
            $write_sql=$sql_head.$sql;
            $write_sql_file=file_put_contents($back_file.'0.sql',$write_sql);

        }else   // 最后一卷
        {
            $write_sql=$sql;
            $write_sql_file=file_put_contents($back_file.$p.'.sql',$write_sql);
        }
        if($write_sql_file!=strlen($write_sql))
        {
            exit("写入备份文件失败3");
        }else
        {
            echo("备份完成");
        }
    }
}