<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2019/7/26
 * Time: 23:28
 * 封装 sql 语句
 */

namespace src;
use PDO;
use PDOException;

class Db
{
    private $pdo; // pdo 类对象
    protected $config = [
        'db'       => '',       // 数据库名称
        'host'     => '',       // 主机
        'dbuser'   => 'root',  // 数据库用户名称
        'dbpw'     => '',      // 数据库密码
        'charset'  => 'utf8', // 字符集
        'timezone' => 'PRC',
        'prot'     => 3306,
    ];

    public function __construct($config=array())
    {
        set_time_limit(0);//无时间限制
        $this->config=array_merge($this->config,$config);
        header("content-type:text/html;charset=".$this->config['charset']);
        date_default_timezone_set($this->config['timezone']);// 设置时区
        $this->conn(); // 初始化调用连接数据库
    }

    //TODO 获取配置信息
    public function __get($name)
    {
        if(isset($this->config[$name]))
        {
            return $this->config[$name];
        }else
        {
            exit("No $name variable exists,Unable to obtain");
        }
    }

    // TODO 设置配置信息
    public function __set($name,$value)
    {
        if(isset($this->config[$name]))
        {
            $this->config[$name] = $value;
        }else
        {
            exit("No $name variable exists,Unable to set up");
        }
    }

    // TODO 判断变量是否存在
    public function __isset($name)
    {
        return isset($this->config[$name]);
    }

    //连接数据库
    private function conn()
    {
        try{
            $dsn='mysql:host='.$this->config['host'].';port='.$this->config['prot'].';dbname='.$this->config['db'];
            $username=$this->config['dbuser'];
            $passwd=$this->config['dbpw'];
            $this->pdo=new PDO($dsn,$username,$passwd,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->pdo->query("set names ".$this->config['charset']);
        }catch (\Exception $e)
        {
            die("Error: " . $e->getMessage());
        }
    }

    /**
     * getOneData
     * @param string $sql
     * @param string  $type num 索引数组,assoc关联数组
     * @return array
     */
    protected function getOneData($sql,$type='num'){
        return $this->query($sql,$type);
    }

    /**
     * getAllData
     * @param string $sql
     * @param string  $type num 索引数组,assoc关联数组
     * @return array
     */
    protected function getAllData($sql,$type="num"){
        return $this->query($sql,$type,true);
    }

    /**
     * getValue
     * @todo 一维数组返回指定值
     * @param string $sql
     * @param string $key 数组键值
     * @return array|string
     */
    protected function getFieldValue($sql,$key){
        $data=$this->getOneData($sql,'assoc',$key);
        return isset($data[$key])?$data[$key]:$data;
    }

    /**
     * getColum
     * @todo 多维数组获取指定行数据
     * @param $sql
     * @param int $column_num 如果获取指定行数据，第几列，默认0
     * @return array
     */
    protected function getColumn($sql,$column_num=0){
        return $this->query($sql,'column',true,$column_num);
    }

    /**
     * query
     * @param string $sql
     * @param string  $type num 索引数组,assoc关联数组,column 获取指定行
     * @param bool $multi 查询条数，true 查询多条数据,false 查询多条数据
     * @param int $column_num 如果获取指定行数据，第几列，默认0
     * @return array
     * 索引数组 array(4) {
            [0]=>
            string(1) "1"
            [1]=>
            string(15) "超级管理员"
            [2]=>
            string(33) "超级管理员拥有全部权限"
            [3]=>
            string(7) "["all"]"
          }
     * 关联数组 array(4) {
            ["id"]=>
            string(1) "1"
            ["r_n"]=>
            string(15) "超级管理员"
            ["r_d"]=>
            string(33) "超级管理员拥有全部权限"
            ["powers"]=>
            string(7) "["all"]"
         }
     */
    protected function query($sql,$type='num',$multi=false,$column_num=null)
    {
        try{
            $resource=$this->pdo->query($sql);
            switch ($type){
                case 'assoc':
                    $data_type=PDO::FETCH_ASSOC;
                    break;
                case 'column':
                    $data_type=PDO::FETCH_COLUMN;
                    break;
                default:
                    $data_type=PDO::FETCH_NUM;
                    break;
            }

            if($column_num==null){
                $data=$multi==true?$resource->fetchAll($data_type):$resource->fetch($data_type);
            }else{
                $data=$multi==true?$resource->fetchAll($data_type,$column_num):$resource->fetch($data_type);
            }
            $resource=null;
            return $data;
        }catch (PDOException $exception){
            $error='错误信息：'.$exception->getMessage().'<br/>';
            $error.='错误行号：'.$exception->getLine().'<br/>';
            $error.='错误文件：'.$exception->getFile().'<br/>';
            exit($error);
        }
    }

    // 执行sql
    public function exec(string $sql){
        try{
            $this->pdo->exec($sql);
        }catch (PDOException $exception){
            $error='错误信息：'.$exception->getMessage().'<br/>';
            $error.='错误行号：'.$exception->getLine().'<br/>';
            $error.='错误文件：'.$exception->getFile().'<br/>';
            exit($error);
        }
    }

    // 释放资源
    public function __destruct()
    {
        $this->pdo=null;
    }

}