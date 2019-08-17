<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2019/7/26
 * Time: 20:33
 * 数据库数据操作
 */
namespace src;

class DbData extends Db
{
    /**
     * getMysqlVersion
     * @todo 取得mysql 版本号
     * @return array|string
     */
    public function getMysqlVersion()
    {
        $data=$this->getFieldValue("select VERSION() as version",'version');
        return $data;
    }

    /**
     * getTableStructure
     * @todo 取得表结构语句
     * @param $table_name
     * @return array|string
     */
    public function getTableStructure($table_name)
    {
        $sql="show create table `".$table_name."`";
         $data=$this->getFieldValue($sql,'Create Table');
        return $data.';';
    }

    /**
     * getTableData
     * @todo 查询表数据----根据表名查询表数据
     * @param $table_name
     * @return array
     */
    public function getTableData($table_name)
    {
        $sql="select * from `".$table_name."`";
        return $this->getAllData($sql,'assoc');
    }

    /**
     * getDatabaseChar
     * @todo 查询数据库编码字符
     * @return string
     */
   public function getDatabaseChar()
   {
        $sql="show create database ".$this->config['db'];
        $string=$this->getFieldValue($sql,'Create Database');
        $char=explode('CHARACTER SET ',$string);
        return trim(substr($char[1],0,-2));
   }

    /**
     * getDatabaseSize
     * @todo 查询数据库总的大小
     * @return int|array 返回数据字节单位
     */
    public function getDatabaseSize()
    {
        $sql="select sum(data_length) as data_size from information_schema.tables where table_schema='".$this->config['db']."';";
        return $this->getFieldValue($sql,'data_size');
    }

    /**
     * getTableSize
     * @todo 获取数据表大小
     * @param $table_name
     * @return array|string
     */
    public function getTableSize($table_name){
        $sql="select sum(data_length) as data_size from information_schema.tables where table_schema='".$this->config['db']."' and table_name='".$table_name."';";
        return $this->getFieldValue($sql,'data_size');
    }

    /**
     * getTableName
     * @todo 获取要操作的表名
     * @param null $table
     * @return array|null
     * 返回一维索引数组形式
        array(9) {
            [0]=>
            string(13) "w_admin_power"
            }
     */
    public function getTableName($table=null){
        $table_type=gettype($table);
        if(empty($table)){
            // 取得数据库中所有的表
            $sql="show tables";
        }else if($table_type=='string'){
            // 备份指定表前缀的数据表
            $sql="show tables like '$table%';";
        }else{
            return $table;
        }
        return $this->getColumn($sql);
    }

    /**
     * optimizeTable
     * @todo 优化表
     * @param null|string|array $table 表名
     * @return array
     */
    public function optimizeTable($table=null){
        $table_name=$this->getTableName($table);
        if(empty($table_name)){
            exit('要操作的数据表不存在数据库中或数据库中没有数据表');
        }
        $table_name = implode('`,`', $table_name);
        $sql="OPTIMIZE TABLE `{$table_name}`";
        return $this->getAllData($sql,'assoc');
    }

    /**
     * repairTable
     * @todo 修复表
     * @param null|string|array $table 表名
     * @return array
     */
    public function repairTable($table=null){
        $table_name=$this->getTableName($table);
        if(empty($table_name)){
            exit('要操作的数据表不存在数据库中或数据库中没有数据表');
        }
        $table_name = implode('`,`', $table_name);
        $sql="REPAIR TABLE `{$table_name}`";
        return $this->getAllData($sql,'assoc');
    }
}