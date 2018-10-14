### 原生 PHP 备份还原 MySql 数据库
    支持 MySql，PDO 两种方式备份还原
    php5.5 以上的版本建议开启pdo扩展，使用 pdo 备份还原数据
    备份文件夹 db_backup、import/log 文件要有读写权限

### 环境版本
	本人测试环境 php:5.5.38 /5.6.27-nts/7.0.12-nts; mysql: 5.5.53 ; apache: Apache/2.4.23 (Win32)
	集成环境 phpStudy，其他环境未测试

### 相比 2.0.0 版本，还原数据添加忽略标注表名
    如果使用其他工具导出的数据，第一卷的开头如果没有标注的表名，在调用 ImportData->import_exec() 时 $table_head 最后一个参数为false即可；
    如果要还原的数据与数据库中的表名同名，还原的数据表语句前又没有 drop table 语句，还原可能会失败
    第一卷开头的 sql 注释信息与下面的 sql 语句之间 也要使用 Import.php 下的 table_fu() 函数 分割符分割;

    忽略标注表名：如果数据还原失败，数据无法回滚；
    在1.0.1版本中忽略标注表名：如果数据还原失败，数据无法回滚；

### 修正错误
    修正以上版本 READNE.md 文件中 import 还原数据库(文件) 存放目录为 import 同级目录 db_backup 文件夹

### 备份/还原文件目录
    备份、还原数据文件存放 db_backup 文件夹
    备份文件夹可以通过 backup/Backup.php  属性 $back_dir 修改 备份文件存放位置
    还原导入数据文件夹可以通过 import/Import.php  属性 $back_dir 修改还原文件存放位置

### 示例 sql 文件：
    标注表名、表与表直接的分割示例    db_backup/xxx.sql 文件

### 备份还原数据文件说明
    |---备份文件大小可以通过 Backup.php 中 $size 设置，默认2MB,其他设置也可在 Backup.php 的属性中设置
    |---备份数据如果是一卷命名为：文件名_自定义标识+0.sql,例如 cms_v0.sql
    |---如果是多卷命名为：文件名_自定义标识+数字第几卷.sql,例如 cms_v1.sql，cms_v2.sql，依次类推
    |---还原数据时如果检测到有 cms_v0.sql 文件只会还原一卷
    |---如果没有检测到cms_v0.sql就查找 cms_v1.sql，如果检测到有cms_v1.sql文件，就会还原cms_v1.sql，cms_v2.sql…… 文件

### backup php 备份mysql  数据库
    |---当前操作用户对备份目录有创建删除权限 数据表有创建删除的权限
    |---备份文件名默认 数据库名为前缀，如果备份文件夹下存在相同文件名自动覆盖，备份前请查看备份文件是否存在相同前缀文件名
    |---如果想修改备份文件命名前缀 请在BackData.php // 备份文件名可以自定义  下2行处修改

#### backup_2 版本
	|---> 缺点：子类与父类中的函数属性调用调的地方都为 public 类型
	|---> 优点：类与类之间的耦合性降低了
	|---> 使用类调用

#### 文件说明   
	backup/    
	|---backup.php 调用父类【基类】
	|---PdoSql.php pdo类备份
	|---MySql.php  mysql类备份
	|---BackData.php 备份数据实例化类
	|---dome.php 调用测试文件

### import php 还原 mysql  数据库
    |---当前操作用户对备份目录、日志目录有创建删除权限 数据表有创建删除的权限
    |---还原数据库文件需放在 import 同级目录 db_backup/ 目录下,还原文件名(按照备份文件名格式)文件名_自定义标识+数字第几卷.sql,例如 cms_v0.sql
    |---如果数据还原失败，数据回滚到没有还原前，如果是sql语法致命错误，程序会直接停止运行，以上版本包括当前版本都无法回滚数据

**还原不是使用 备份类备份的 sql 数据需要注意几点**
<pre>
1： 备份文件名前缀+ 标示名+数字.sql
	<数字>0代表只还原这一个，数字从1依次还原多个
  	标示名可以自定义 默认 $back_file_fu="_v";

2: 备份文件的第一卷需要在 sql 文件的开头添加（标注）要还原的表名;
   如果sql文件开头没有要还原的表名,调用 ImportData->import_exec() 函数添加最后一个参数$table_name=false;
   如果想添加请在第一卷 注释 sql 的开头下面添加如下格式的表名：
	    格式 -- ##* 表名|表名|表名 ##* --
	    可以自定义格式  $import_table_fu=" ##* ";

3：使用其他工具导出的 sql 文件需要查看表与表之间的分割符是否 Import.php 下的 table_fu() 函数定义的一致，
   如果不一致，需要修改为一致，也可修改 table_fu() 函数的格式
	< 防止sql 语句过大程序卡死 >
	table_fu() 格式：
		$create_table=PHP_EOL;
		$create_table.='-- '.str_repeat('--',30).PHP_EOL;
		$create_table.=PHP_EOL;
</pre>

#### import_2 版本
	|---> 缺点：子类与父类中的函数属性调用调的地方都为 public 类型
	|---> 优点：类与类之间的耦合性降低了
	|---> 使用类调用

#### 文件说明
	import/    
	|---Import.php 调用父类【基类】
	|---PdoSql.php pdo类还原
	|---MySql.php  mysql类还原
	|---ImportData.php 还原数据实例化类
	|---dome.php 调用测试文件