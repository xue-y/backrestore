<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2019/7/27
 * Time: 20:11
 * 备份数据库
 */

namespace src;
use ZipArchive;
use SplFileObject;

class Backup extends Common
{
    private $back_config=[
        'subsection'     => 5,             // 分卷大小，单位MB
        'min_subsection' => 2,       // 最小分卷数
        'max_subsection' => 50,       // 最大分卷数
        'compress'       => false,         // 是否压缩，默认不压缩
        'data_limit'     => 10,           // 表数据每10 条统计一次是否达到分卷字数，如果为0表数据中不判断
        'charset'        => 'utf8',      // 字符集
    ];

    private $total_file_size=0; // 已写入文件的数据表数据字节数
    private $part=0; // 当前写入第几卷
    private $last_size=0;// 最后一卷还有多少可写入的字节个数
    private $last_data;// 最后一卷的数据
    private $dir_file_name; // 文件夹,文件名称
    private $info_out;// 备份进度信息输出

    /**
     * Backup constructor.
     * @param array $config 用户传入的配置信息
     */
    public function __construct(array $config = array())
    {
        parent::__construct($config);
        $this->config=array_merge($this->back_config,$this->config);
        // 设置文件存放文件夹
        $this->setPathDir($this->config['back_dir']);
        // 文件名
        $this->dir_file_name=$this->dirFileName();
        // 分卷大小
        $this->setFileSize();
    }

    //TODO 获取备份文件夹
    private  function getBackDir()
    {
        $back_dir=$this->config['back_dir'].$this->dir_file_name;
        if(!is_dir($back_dir))
        {
            $bool=@mkdir($back_dir,0755,true);
            if(!$bool || !is_writable($this->config['back_dir']))
            {
                exit('创建备份目录失败或备份目录不可写');
            }
        }
        return $back_dir.'/';
    }

    //TODO 设置分卷大小字节单位
    private function setFileSize(){
        $this->config['min_subsection']=intval($this->config['min_subsection']);
        $this->config['subsection']=max($this->config['min_subsection'],$this->config['subsection']);
        $this->config['subsection']=min($this->config['max_subsection'],$this->config['subsection']);
        $this->config['subsection']*=1024*1024;
    }

    /**
     * dirFileName
     * @todo 备份文件夹,文件名
     * @return false|string
     */
    private function dirFileName()
    {
        return date('YmdH',time());
    }


    /**
     * getCurrentWriteFileName
     * @todo 当前备份写入的文件名
     * @param string $extension
     * @return string
     */
    private function getCurrentWriteFileName(int $part)
    {
        return  $this->dir_file_name.$this->config['file_delimiter'].$part.'.'.$this->config['file_extension'];
    }

    //TODO 拼接头部语句
    private function getHead()
    {
        $sql='-- backrestor
-- version 3.0.0
-- github: https://github.com/xue-y
-- Host: '.$this->config['host'].'
-- DateTime: '.date('Y-m-d H:i:s').'
-- MySql version: '.$this->db->getMysqlVersion().'
-- PHP version: '.PHP_VERSION.'
-- Database: '.$this->config['db'].PHP_EOL.'
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO"; -- 自增列在只插入0 时不产生自增序列，就要提前设置mysql的sql_mode包括NO_AUTO_VALUE_ON_ZERO
SET UNIQUE_CHECKS=0;   -- 设置为1（默认值），则会执行InnoDB表中二级索引的唯一性检查。如果设置为0，则不进行唯一性检查
SET time_zone = "+00:00"; -- 设置时间
SET foreign_key_checks=0;  -- 关闭外键约束

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES '.$this->config['charset'].' */;'.$this->getTableDelimiter();
         return $sql;
    }

    /**
     * getTableNotes
     * @todo 数据表结构/数据注释
     * @param string $table_notes 表注释说明
     * @return string
     */
    private function getTableNotes(string $table_notes)
    {
        return PHP_EOL.PHP_EOL.'-- '.$table_notes.PHP_EOL.PHP_EOL;
    }

    /**
     * getLockTable
     * @todo 获取锁表解锁注释
     * @param string $table 表名
     * @param int $lock 1 锁表操作，2 解表操作
     * @return string
     */
    private function getLockTable(string $table,int $lock){
        if($lock==1){
            return "LOCK TABLES `{$table}` WRITE; -- 锁表操作".PHP_EOL."/*!40000 ALTER TABLE `{$table}` DISABLE KEYS */;".PHP_EOL;
        }else{
           return PHP_EOL."/*!40000 ALTER TABLE `{$table}` ENABLE KEYS */;".PHP_EOL."UNLOCK TABLES;";
        }
    }

    /**
     * getTableData
     * @todo 获取一个数据表的所有数据
     * @param string $table 当前操作的表名
     * @param int $size 当前这卷还剩余多少字节
     * @param int $part 第几卷
     * @return mixed
     */
    private function getTableData(string $table,int $part)
    {
        $size=$this->config['subsection'];
        $data[$part]=$this->last_data.$this->getTableNotes("table structure `{$table}`");
        $data[$part].="DROP TABLE IF EXISTS `{$table}`;".PHP_EOL;
        $data[$part].=$this->db->getTableStructure($table);
        $table_data=$this->db->getTableData($table);
        if(empty($table_data)){
            $data[$part].=$this->getTableDelimiter();
            // 判断是否达到分卷界限
            if($size<=(strlen($data[$part]))){
                $part++;
                $data[$part]='';
            }
            $this->last_size=strlen($data[$part]);
            return $data;
        }
        // 表数据注释
        $data[$part].=$this->getTableNotes("table data `{$table}`");
        // 锁表操作
        $data[$part].=$this->getLockTable($table,1);
        // 表数据
        $field_arr=array_keys($table_data[0]);
        $field_name=implode('`,`',$field_arr);
        $data[$part].="INSERT INTO `$table` (`$field_name`) VALUES ";
        $table_data_c=count($table_data)-1;
        foreach ($table_data as $k=>$v){
            $v = array_map('addslashes', $v);
            $v=str_replace(array("\r", "\n"), array('\\r', '\\n'), implode("', '", $v));
            $val="('".$v."')";
            // 判断是到达分卷界限
            if(($this->config['data_limit']>0)  && (($k%$this->config['data_limit'])<1))
            {
                if($size<=(strlen($val)+strlen($data[$part]))){
                    $data[$part].=$val.";";
                    $data[$part].=$this->getLockTable($table,2); // 表解锁
                    $part++;
                    // 写完最后一条数据恰巧分卷
                    if($k!=$table_data_c){
                        // 锁表操作
                        $data[$part]=$this->getLockTable($table,1);
                        $data[$part].= "INSERT INTO `$table` (`$field_name`) VALUES ";
                    }else{
                        $data[$part]='';
                    }
                }else{
                    $data[$part] .=$val.',';
                }
             }else{
                $data[$part] .=$val.',';
            }
        }//-------------------表数据全部循环完

        if(!empty($data[$part])){
            $data[$part]=rtrim($data[$part],',').";";
        }

        $data[$part].=$this->getLockTable($table,2).$this->getTableDelimiter();

        //记录最后一卷字节数
        $this->last_size=strlen($data[$part]);
        return $data;
    }

    //TODO 获取数据库文件底部语句
    private function getFoot()
    {
        $sql="
SET foreign_key_checks=1;  -- 开启外键约束

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
        return $sql;
    }

    //TODO 写入头部底部文件
    private function writeHeadFoot(string $c_w_f_n,string $data)
    {
        $file = fopen($c_w_f_n, "w");
        if(!$file){
            exit('文件创建失败');
        }
        $write= fwrite($file,$data);
        if($write<1){
            exit('文件写入失败');
        }
        fclose($file);
    }

    // TODO 写入备份文件
    public function wirteFile($table=null)
    {
        // 定义变量
        $table_name=$this->db->getTableName($table);

        if(empty($table_name)){
            exit('要备份的数据表不存在数据库中或数据库中没有数据表');
        }

        $back_dir=$this->getBackDir();
        $database_size=$this->db->getDatabaseSize();

        // 判断锁文件是否存在
        $this->isFile($back_dir);
        $backup_lock_file=$back_dir.$this->config['backup_lock_file'];
        if(!file_put_contents($backup_lock_file,'lock')) {
            exit('创建锁文件失败,请手动创建'.$backup_lock_file);
        }

        ob_flush();
        flush();
        $this->info_out='准备备份'.'<br/>';
        echo $this->info_out;

        // 获取头部数据
        $this->last_data=$this->getHead();
        $this->last_size=strlen($this->last_data);

        if($this->config['compress']){
            $zip_file_name=$back_dir.$this->dir_file_name.'.'.$this->config['compress_file_extension'];
            $zip=new ZipArchive();
            if(!$zip->open($zip_file_name,ZipArchive::CREATE || ZipArchive::OVERWRITE)) {
                exit('创建压缩包失败');
            }
        }

        $this->info_out='开始备份'.'<br/>';
        echo $this->info_out;

        // 循环表-->表循环数据
        foreach ($table_name as $k=>$v)
        {
            // 获取数据表所有的数据
            $data=$this->getTableData($v,$this->part);

            // 如果最后一卷没有到分卷字节个数先不写入文件
            if($this->last_size<$this->config['subsection']){
                $this->last_data=array_pop($data);
            }else{
                $this->last_data=null;
            }
            // 记录分卷卷标，下次循环使用
            $this->part+=count($data);
            foreach ($data as $kk=>$vv)
            {
                $c_w_f_n=$back_dir.$this->getCurrentWriteFileName($kk);
                // 如果压缩记录压缩的文件名
                if($this->config['compress']){
                    $zip->addFromString($this->getCurrentWriteFileName($kk),$vv);
                }else{
                    $file = new SplFileObject($c_w_f_n, "w");
                    $write= $file->fwrite($vv);
                    if($write<1){
                        exit('文件写入失败');
                    }
                }
            }
            // 备份进度
            $this->total_file_size+=$this->db->getTableSize($v);
            $progress=round($this->total_file_size/$database_size*100);
            $this->info_out='备份 '.$v.' 表,进度 '.$progress.'%'.'<br/>';
            echo $this->info_out;
        }

        // 写入底部数据
        $foot_data=$this->last_data.$this->getFoot();

        // 取得要压缩的文件名
        if($this->config['compress']){
            $zip->addFromString($this->getCurrentWriteFileName($this->part),$foot_data);
        }else{
            $c_w_f_n=$back_dir.$this->getCurrentWriteFileName($this->part);
            $this->writeHeadFoot($c_w_f_n,$foot_data);
        }

        // 删除锁文件
        if(!@unlink($backup_lock_file)){
            exit('删除备份锁文件失败,请手动删除');
        }

        $this->info_out='备份完成<br/>';
        echo $this->info_out;
    }
}