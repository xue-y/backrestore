<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2019/7/27
 * Time: 22:21
 * 还原数据库
 */

namespace src;

use ZipArchive;
use SplFileObject;

class Import extends Common
{
    private $import_config=[
		'del_file'=>false,	 // 还原完数据库是否删除文件夹以及文件夹中所有文件,默认不删除
        'read_size'=>8192       // 一次读取文件字节个数
	];
    private $read_file_size=0; // 总的已读文件字节数
    private $total_file_size=0;// 总文件大小
    private $last_data; // 最后一次没有执行sql的数据
    private $info_out; // 还原文件进度信息输出

    /**
     * Backup constructor.
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        parent::__construct($config);
        $this->config=array_merge($this->import_config,$this->config);
    }

    //TODO 判断还原/备份路径权限
    private function isDirRead($file_dir){
        if(!is_dir($file_dir)){
            exit('不存在'.$file_dir.'目录');
        }
        if(!is_readable($file_dir) && (!chmod($file_dir,755))){
            exit($file_dir.'目录不可读');
        }
    }

    //TODO 读取还原/备份路径取得备份文件名
    private function getPathFile(){

        $file_dir=$this->config['back_dir'];
        if(!is_dir($file_dir)){
            exit($file_dir.'不是个目录');
        }

        $dir_handle = opendir($file_dir);
        if(!$dir_handle){
            exit('还原数据文件夹读取失败');
        }
        $file_item = [];
        $file_name=basename($file_dir);
        $biaoshifu=$this->config['file_delimiter'];
        $extension=$this->config['file_extension'];

        // 取得目录下所有的文件名，只遍历一级
        while(($file = readdir($dir_handle)) !== false) {
            $newPath = $file_dir.$file;
            if(is_file($newPath) && (preg_match("/{$file_name}({$biaoshifu}([\d]+))?\.([{$extension}|zip]+)/",$file,$arr))) {
                // $arr  Array ( [0] => 201909098989_1.sql [1] => _1 [2] => 1 [3] => sql )
                $index=empty($arr[2])?0:$arr[2];
                $file_item[$arr[3]][$index]=$newPath;
                // 如果还原的是普通文件计算总的文件大小
                if($arr[3]==$extension){
                    $this->total_file_size+=filesize($newPath);
                }
            }
        }
        @closedir($dir_handle);
        if(empty($file_name)){
            exit($file_dir.'文件夹下没有要还原的文件');
        }
        return $file_item;
    }

    /**
     * readOneSqlFile
     * @todo 读取单个普通文件
     * @param string $file_name 文件名
     */
    private function readOneSqlFile($file_name)
    {
        $file = new SplFileObject($file_name, "r");
        if(!$file){
            exit('打开'.$file_name.'文件失败');
        }
        while (!$file->eof()) {
            $content = $file->fread($this->config['read_size']);
            $pos = strrpos($content,$this->getTableDelimiter());
            if($pos){
                $sql =$this->last_data.substr($content,0,$pos);
                $this->last_data=substr($content,$pos);
            }else{
                $sql =$this->last_data.$content;
                $this->last_data='';
            }
            // 执行sql 计算百分比
            $this->read_file_size+=strlen($sql);
            $this->db->exec($sql);
            $this->importProgress();
        }
        // $file 官方文档中不需要关闭文件
    }

    /**
     * readOneZipFile
     * @todo 读取单个压缩文件
     * @param resource $zip 压缩包资源
     * @param string $file_name 压缩包中要还原的文件名
     * @param string $zip_file_name 压缩包名
     */
    private function readOneZipFile($zip,$file_name,$zip_file_name){
        $file = $zip->getStream($file_name);
        if(!$file){
            exit('打开压缩包中的'.$file_name.'文件失败');
        }
        while (!feof($file)) {
            $content = fread($file,$this->config['read_size']);
            $pos=strrpos($content,$this->getTableDelimiter());
            if($pos){
                $sql =$this->last_data.substr($content,0,$pos);
                $this->last_data=substr($content,$pos);
            }else{
                $sql =$this->last_data.$content;
                $this->last_data='';
            }
            // 执行sql 计算百分比
            $this->read_file_size+=strlen($sql);
            $this->db->exec($sql);
            $this->importProgress($zip_file_name);
        }
        fclose($file);
    }

    //TODO 读取压缩文件
    private function readCompressFile($zip_file_name){
        $zip = new ZipArchive();
        if(!$zip->open($zip_file_name)){
            exit($zip_file_name.'文件打开失败');
        }
        $file_name=pathinfo($zip_file_name,PATHINFO_FILENAME);
        $biaoshifu=$this->config['file_delimiter'];
        $extension=$this->config['file_extension'];
        $sql_file=[];
        // 压缩文件单独计算文件大小
        $this->total_file_size=0;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $file_info=$zip->statIndex($i);
            // 匹配文件名
            if(preg_match("/{$file_name}({$biaoshifu}([\d]+))?\.([$extension]+)/",$file_info["name"],$arr)){
                // $arr  Array ( [0] => 201909098989_1.sql [1] => _1 [2] => 1 [3] => sql )
                $index=empty($arr[2])?0:$arr[2];
                $sql_file[$index]=$file_info["name"];
                $this->total_file_size+=$file_info["size"];
            }
        }
        if(empty($sql_file)){
            $zip->close();
            exit('压缩包中没有要还原的文件');
        }
        // 读取压缩包中的文件
        $out_zip_file_name=basename($zip_file_name);
        foreach ($sql_file as $v){
            $this->readOneZipFile($zip,$v,$out_zip_file_name);
        }
        $zip->close();
    }

    //TODO 还原进度
    private function importProgress($file_name=''){
        $this->info_out=$file_name.'还原 '.round($this->read_file_size/$this->total_file_size*100);
        echo $this->info_out.'%<br/>';
    }

    //TODO 读取文件
    public function readFile($file_dir)
    {
        // 设置还原文件目录
        $this->setPathDir($this->config['back_dir'].$file_dir);

        // 判断目录权限
        $this->isDirRead($this->config['back_dir']);

        // 判断用户是否正在操作数据库
        $this->isFile($this->config['back_dir']);

        // 创建还原数据锁文件
        $import_lock_file=$this->config['back_dir'].$this->config['import_lock_file'];
        if(!file_put_contents($import_lock_file,'lock')) {
            exit('创建锁文件失败,请手动创建'.$import_lock_file);
        }

        // 读取要还原的文件名
        $file_name=$this->getPathFile();
        // 输出还原信息
        ob_flush();
        flush();
        $this->info_out='开始还原'.'<br/>';
        echo $this->info_out;

        // 还原sql 文件
        if(isset($file_name[$this->config['file_extension']])){
            $file_arr=$file_name[$this->config['file_extension']];
            ksort($file_arr);
            foreach ($file_arr as $v){
                $this->readOneSqlFile($v);
            }
        }else if($file_name['zip']){
            $file_arr=$file_name['zip'];
            ksort($file_arr);
            foreach ($file_arr as $v){
                $this->readCompressFile($v);
            }
        }
        // 每次截取指定字符后，最后一段 sql 语句
        if(!empty($this->last_data)){
            $this->read_file_size+=strlen($this->last_data);
            $this->db->exec($this->last_data);
            $this->importProgress();
        }

        // 删除锁文件
        if(!@unlink($import_lock_file)){
            exit('删除还原锁文件失败,请手动删除');
        }

        $this->info_out='还原完成<br/>';
        echo $this->info_out;

        // 删除还原文件夹
        if($this->config['del_file']==true){
            $info=$this->delFile();
            if(!empty($info)){
                echo $info;
            }
        }
    }

    //TODO 删除还原文件夹
    private function delFile(){
        $dir_handle=opendir($this->config['back_dir']);
        $error='';
        while (($file = readdir($dir_handle)) !== false)
        {
            if($file!="." && $file!="..")
            {
                $file_path=$this->config['back_dir'].$file;
               if(!@unlink($file_path)){
                   $error.=$file_path.'文件删除失败'.PHP_EOL;
                   continue;
               }
            }
        }
        closedir($dir_handle);
        //删除当前文件夹：
        if(!@rmdir($this->config['back_dir'])) {
            $error.=$this->config['back_dir'].'文件夹删除失败';
        }
        return $error;
    }
}