<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2019/7/27
 * Time: 21:22
 * 数据备份导入父类
 */

namespace src;


class Common
{
    protected $config=[
        'file_delimiter'          => '_',     // 分卷分割符,如果修改其他符号，文件名匹配正则需要修改
        'file_extension'          => "sql",  // 备份文件后缀名
        'compress_file_extension' => 'zip',// 压缩文件后缀
        'import_lock_file'        => 'import_lock_file.txt',// 导入锁文件名
        'backup_lock_file'        => 'backup_lock_file.txt',//备份锁文件
        'back_dir'                => './backup/',     // 备份文件路径，备份
    ];
    protected $db; // 数据库操作

    /**
     * Common constructor.
     * @param array $config 用户必填数据库配置
     */
    public function __construct($config=array())
    {
        // 取得数据库配置 -- 用户必填数据库参数
        $db_config=array_diff($config,$this->config);
        // 连接数据库
        $this->db=new DbData($db_config);
        // 取得用户其他配置
        $this->config=array_merge($this->config,$config);
    }

    /**
     * getTableDelimiter
     * @todo 获取表与表之间的分割符
     * @return string
     */
    protected function getTableDelimiter()
    {
        return PHP_EOL.PHP_EOL.PHP_EOL.'-- '.str_repeat('-',50).PHP_EOL;
    }

    /**
     * getLogFileName
     * @todo 获取日志文件名
     * @param string $back_dir
     * @return string
     */
    protected function getLogFileName(string $back_dir)
    {
        return $back_dir.$this->dir_file_name.'.'.$this->config['log_extension'];
    }

    /**
     * isFile
     * @todo 判断数据锁文件是否存在
     * @param string $dir
     * @return boolean|string
     */
    protected function isFile(string $dir)
    {
        $import_lock_file=$dir.$this->config['import_lock_file'];
        if(is_file($import_lock_file)){
            exit('当前正在执行还原操作请稍后操作,或手动删除'.$import_lock_file);
            //  return 'import_lock_file';
        }
        $backup_lock_file=$dir.$this->config['backup_lock_file'];
        if(is_file($backup_lock_file)){
          exit('当前正在执行备份操作请稍后操作,或手动删除'.$backup_lock_file);
            //    return 'backup_lock_file';
        }
        return true;
    }

    /**
     * isPathDir
     * @todo 判断目录路径是否完整
     * @param string $dir
     * @return string
     */
    protected function setPathDir(string $dir){
        if(substr($dir,-1)!='/'){
            $dir.='/';
        }
        $this->config['back_dir']=$dir;
    }
}