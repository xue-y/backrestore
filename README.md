### 环境版本
	本人测试环境 php:5.538 /5.6.27-nts/7.0.12-nts; mysql: 5.5.53 ; apache: Apache/2.4.23 (Win32)
	集成环境phpStudy，其他环境未测试

### backup php 备份mysql  数据库
 > 当前操作用户对备份目录有创建删除权限 数据表有创建删除的权限
 > 备份文件名默认 数据库名为前缀，如果备份文件夹下存在相同文件名自动覆盖，备份前请查看备份文件是否存在相同前缀文件名
 > 如果想修改备份文件命名前缀 请在Backup.php 打开147注释、注释掉148行代码       

#### backup_1 版本
	|---> 缺点：子类与父类之间有互相调用有耦合
	|---> 优点：子类与父类中的函数属性都为 protected 类型
	|---> 使用函数调用

#### 文件说明
	|---backup.php 调用父类【基类】
	|---PdoSql.php pdo类备份
	|---MySql.php  mysql类备份
	|---BackData.php 备份数据实例化类
	|---BackExec.php 执行调用文件

### import php 还原 mysql  数据库
 > 当前操作用户对备份目录、日志目录有创建删除权限 数据表有创建删除的权限
 > 还原数据库需放在  import/backup/ 目录下,还原文件名(按照备份文件名格式)文件名_自定义标识+数字第几卷.sql,例如 cms_v0.sql
 > 如果数据还原失败，数据回滚到没有还原前

**还原不是使用 备份类备份的 sql 数据需要注意4点**
<pre>
1： 备份文件名前缀+ 标示名+数字.sql
	<数字>0代表只还原这一个，数字从1依次还原多个
  	标示名可以自定义 默认 $back_file_fu="_v";

2: 备份文件的第一卷需要在 sql 文件的开头添加（标注）要还原的表名，如果sql文件开头没有要还原的表名，请查看1.0.1版本
	格式 -- ##* 表名|表名|表名 ##* --
	可以自定义格式  $import_table_fu=" ##* ";

3：使用其他工具导出的 sql 文件需要查看表与表之间的分割符是否 Import.php 下的 table_fu()函数定义的一致，
   如果不一致，需要修改为一致，也可修改table_fu() 函数的格式
	< 防止sql 语句过大程序卡死 >
	table_fu() 格式：
		$create_table=PHP_EOL;
		$create_table.='-- '.str_repeat('--',30).PHP_EOL;
		$create_table.=PHP_EOL;
</pre>

#### import_1 版本
	|---> 缺点：子类与父类之间有互相调用有耦合
	|---> 优点：子类与父类中的函数属性都为 protected 类型
	|---> 使用函数调用

#### 文件说明
	|---import.php 调用父类【基类】
	|---PdoSql.php pdo类还原
	|---MySql.php  mysql类还原
	|---BackData.php 还原数据实例化类
	|---BackExec.php 执行调用文件