<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-27
 * Time: 下午3:19
 * pdo 还原、导入数据
 */
namespace import;
use PDO;

class PdoSql extends Import {

    protected  $pdo; // pdo 类对象

    // 连接数据库
    protected function conn()
    {
        try{
            $dsn='mysql:host='.$this->host.';port='.$this->prot.';dbname='.$this->db;
            $username=$this->dbuser;
            $passwd=$this->dbpw;
            $this->pdo=new PDO($dsn,$username,$passwd);
			$this->pdo->query("set names ".$this->charset);

        }catch (\Exception $e)
        {
            die("Error: " . $e->getMessage());
        }
    }

    // 取得数据库中所有的表
    public function old_table()
    {
        $sql="show tables";
        $table_result=$this->pdo->query($sql);
        $table_name=$table_result->fetchAll(PDO::FETCH_COLUMN);
        $table_result=null;
        return $table_name;
    }

    /** 执行修改原数据表名称
     * @parem $rename 修改表名的字符串
     * @return void
     * */
    public function temp_table_exec($rename)
    {
        try{
            $sql=" rename table ".substr($rename,0,-1);
            $table_num=$this->pdo->exec($sql); // 执行成功返回0
            $error=$this->pdo->errorInfo();
            if($error[0]!='0000')  // if($table_num==1 || $table_num)
            {
                exit("更改原数据表名为临时表名失败 ".$error[2]);
            }
        }catch (\Exception $e)
        {
            die("Error: " . $e->getMessage());
        }
    }

    /** 执行还原语句
     * @parem $sql_arr 备份文件中需要还原的sql 语句
     * @parem $table_name 执行本次还原的数据表
     * @parem $temp_table 更改的原表的临时表名
     * */
    public function sql_exec($sql_arr,$table_name,$temp_table)
    {
        // 使用 foreach 循环-- 防止一次执行太多sql 程序卡死
        foreach($sql_arr as $k=>$v)
        {
			//$v=$sql_arr;//如果每个分卷小于 2MB 可以去掉 foreach 循环
			
            // 如果 本券是以 转储表结构 开头，$v  为空
            if(empty($v))
            {
                continue;
            }
			
		//	$v = stripslashes($v); // 备份的时候转义了字符，转义回去  语句错误不转义数据也可以正常访问
            $bool=$this->pdo->exec($v); // 执行成功返回 0 ，失败返回1

            $error=$this->pdo->errorInfo();
            if($error[0]!='0000') //if($bool==1 || $bool)
            {
                // 如果存在还原前的数据表  更改的临时表名还原回去
                if(!empty($table_name) && !empty($temp_table))
                {
                    // 重新取得数据库中的所有表名  数据库中的原表名+ 已插入表名
                    $all_table=$this->old_table();
                    if(!empty($all_table))
                    {
                        // 取得已经插入的表名执行删除
                        $insert_table=array_intersect($all_table,$table_name);
                        $drop_table=" DROP TABLE `".implode("`,`",$insert_table)."`";
                        $this->pdo->exec($drop_table);
                        $error=$this->pdo->errorInfo();
                        if($error[0]!='0000')
                        {
                            exit("清理新插入的数据失败".PHP_EOL.$error[2]);
                        }
                    }

                    $rename="";
                    //取得原数据的临时表名 还原数据
                    foreach($temp_table as $kk=>$vv)
                    {
                        $new_v=str_replace("temp_","",$vv);
                        $rename.="`".$vv.'` to `'.$new_v."`,";
                    }
                    $reduction_table=" rename table ".substr($rename,0,-1);
                    $table_num=$this->pdo->exec($reduction_table); // 执行成功返回0
                    $error=$this->pdo->errorInfo();
                    if($error[0]!='0000')
                    {
                        exit("恢复原数据失败".PHP_EOL.$error[2]);
                    }
                }
                exit("错误sql语句 ".PHP_EOL.$error[2]);
                break;
            }//------------------------------------ 如果出现错误，如果sql 语法错误有时程序会直接停止运行，不会回滚数据

       } //---------------------------------------还原所有一个分卷中的数据
    }

    // 还原成功后删除 原数据临时表--如果存在临时表
    public function del_temp_table($temp_table)
    {
        //删除原数据的临时表名
        $drop_table=" DROP TABLE `".implode("`,`",$temp_table)."`";
        $table_num=$this->pdo->exec($drop_table); // 执行成功返回0
        $error=$this->pdo->errorInfo();
        if($error[0]!='0000')
        {
            exit("删除原数据失败 ".PHP_EOL.$error[2]);
        }
    }

    // 释放资源
    public function __destruct()
    {
        $this->pdo=null;
    }

}
