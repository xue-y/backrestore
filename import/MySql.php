<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-28
 * Time: 下午5:14
 * mysql 还原、导入数据
 */
namespace import;

class MySql extends  Import {

    // 连接数据库
    protected function conn()
    {
        $this->conn = @mysql_connect ( $this->host, $this->dbuser, $this->dbpw ) or die(mysql_error());
        // 选择使用哪个数据库
        mysql_select_db ( $this->db, $this->conn) or die(mysql_error());
        // 数据库编码方式
        mysql_query ( 'SET NAMES ' .$this->charset, $this->conn );
    }

    // 取得数据库中所有的表
    public function old_table()
    {
        $sql="show tables";
        $table_result=mysql_query($sql);
        $table_c=mysql_num_rows($table_result);

        if($table_c < 1)
        {
          return false;
        }
        //$table_name=[]; //php 5.4+
		$table_name=array();
        for($i=0; $i<$table_c; $i++)
        {
            array_push($table_name,mysql_tablename($table_result,$i));
        }
        // 释放资源集
        mysql_free_result($table_result);
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
            $temp_table=mysql_query($sql);
            if(!$temp_table)
            {
                exit("更改原数据表名为临时表名失败 ".mysql_error());
            }
        }catch (\Exception $e)
        {
            die("Error: " . $e->getMessage());
        }
        $temp_table=null;
    }
		
	/**还原失败时
	 * @parem $table_name 本次要还原的表名
	 * @parem $temp_table  更改的临时表名
	 */
	protected function sql_error($table_name,$temp_table)
	{
		// 重新取得数据库中的所有表名
		$all_table=$this->old_table();
		if($all_table)
		{
			// 取得已经插入的表名执行删除
			$insert_table=array_intersect($all_table,$table_name);
			$drop_table=" DROP TABLE `".implode("`,`",$insert_table)."`";
			mysql_query($drop_table) or die(mysql_error());
		}

		if($temp_table) // 如果存在还原前的数据表  更改的临时表名还原回去
		{
			$rename="";
			//取得原数据的临时表名 还原数据
			foreach($temp_table as $kk=>$vv)
			{
				$new_v=str_replace("temp_","",$vv);
				$rename.="`".$vv.'` to `'.$new_v."`,";
			}
			$reduction_table=" rename table ".substr($rename,0,-1);
			mysql_query($reduction_table) or die("更改原数据表名为临时表名失败".PHP_EOL.mysql_error());
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
			//$v=$sql_arr; //如果每个分卷小于 2MB 可以去掉 foreach 循环
			
            // 如果 本券是以 转储表结构 开头，$v  为空
            if(empty($v))
            {
                continue;
            }
			
			//$v = stripslashes($v); // 备份的时候转义了字符，转义回去 还原sql 语句失败，不转义数据也可以正常访问
			
            /*
			mysql_query 一次只能执行一条 sql 语句
			分割sql 语句     这里使用 ; 换回符分割-----如果sql 插入语句也有 ;换回符 的数据 可能会出现错误
			可以在备份文件中的 sql_table() 函数中 删除 创建 插入 sql语句前后 自定义其他的注释 标示符 
			*/
			$sql_fu=";".PHP_EOL;
			$new_v=explode($sql_fu,$v);
			$new_v=array_filter($new_v);
			
			if(!empty($new_v))
			{
				foreach($new_v as $kk=>$vv)
				{
					// /*! 开头 */结尾的跳过 默认phpmyAdmin 导出数据时的顶部 底部跳过

					/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */  //;
					/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */ //;
					/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */ //;
					
					if(preg_match("/\/\*\![\s\S]+\*\//",$vv))
					{
						continue;
					}
					$new_vv=$vv.$sql_fu;
					
					$bool=mysql_query($new_vv) or die(mysql_error());  // 失败返回false  执行失败
						
					if(!$bool || $bool==false)
					{ 
					  $this->sql_error($table_name,$temp_table); 
					}
				}
			}else
			{
				$bool=mysql_query($v);  // 失败返回false  执行失败
				if(!$bool || $bool==false)
				{ 
					$this->sql_error($table_name,$temp_table); 
				}
			}
        }//---------------------------------------还原所有一个分卷中的数据
			
    }
	
    // 还原成功后删除 原数据临时表--如果存在临时表
    public function del_temp_table($temp_table)
    {
        //删除原数据的临时表名
        $drop_table=" DROP TABLE `".implode("`,`",$temp_table)."`";
        $table_num=mysql_query($drop_table);
		
        if(!$table_num || $table_num==false)
        {
            exit("删除原数据失败 ".PHP_EOL.mysql_error());
        }
    }

    // 释放资源
    public function __destruct()
    {
        mysql_close($this->conn);
    }

}

