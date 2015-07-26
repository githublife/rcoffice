<?php
/*

	项目名称：数据库操作类
	开发人	：小纪
	开发时间：2015-6-21

	重构时间：2015/7/23
	
	
*/

	class db{
		var $link;		//数据库连接资源

		private $dbhost;	//数据库主机地址
		private $dbuser;	//数据用户
		private $dbpasswd;	//数据库密码
		private $db;		//数据库名
		private $charset;	//编码

		var $result;	//结果集
		var $affected;	//影响条数
		var $field;		//字段名


		//构造函数
		function __construct(){
			//初始化
			$this->dbhost=DBHOST;
			$this->dbuser=DBUSER;
			$this->dbpasswd=DBPASSWD;
			$this->db=DB;
			$this->charset=CHARSET;
		}

		//连接数据库
		function conn(){
			if(!$this->link){
				$this->link=@mysql_connect($this->dbhost,$this->dbuser,$this->dbpasswd) or $this->error("连接数据库错误!<br>主机地址:$this->dbhost <br>数据库用户:$this->dbuser <br>数据库密码:$this->dbpasswd <br>");
				@mysql_select_db($this->db,$this->link) or $this->error("选择数据库错误!<br>数据库名称:$this->db <br>");
				@mysql_query('set names '.$this->charset) or $this->error("设置编码错误!<br>编码:$this->charset <br>");
				if($this->link)
					return $this->link;
				else
					return false;
			}
		}


		
		//设置主机地址
		function set_dbhost($dbhost=null){
			if($dbhost!=null)
				$this->dbhost=$dbhost;
		}

		//设置用户名
		function set_dbuser($dbuser=null){
			if($dbuser!=null)
				$this->dbuser=$dbuser;
		}

		//设置密码
		function set_dbpasswd($dbpasswd=null){
			if($dbpasswd!=null)
				$this->dbpasswd=$dbpasswd;
		}

		//设置数据库
		function set_db($db=null){
			if($db!=null)
				$this->db=$db;
		}

		//设置编码
		function set_charset($charset=null){
			if($charset!=null)
				$this->charset=$charset;
		}


		//执行SQL语句
		function query($sql){
			if(!$this->link)
				$this->conn();
			$sql=htmlspecialchars($sql);
			$this->result=mysql_query($sql,$this->link) or $this->error("执行SQL语句出错！<br>SQL: $sql");
			$this->affected=mysql_affected_rows();
			return $this->result;
		}
		
		//取得结果集数组
		function fetch_array(){
			for($i=0;$i<mysql_num_fields($this->result);$i++){
				$this->field[]=mysql_field_name($this->result,$i);
			}
			if($this->result){
				while($row=@mysql_fetch_array($this->result)){
					$return[]=$row;
				}
				return $return;
			}
			return false;
		}

		//取得结果集关联数组
		function fetch_assoc(){
			if($this->result){
				while($row=@mysql_fetch_assoc($this->result)){
					$return[]=$row;
				}
				return $return;
			}
			return false;
		}

		//取得结果集枚举数组
		function fetch_rows(){
			if($this->result){
				while($row=@mysql_fetch_rows($this->result)){
					$return[]=$row;
				}
				return $return;
			}
			return false;
		}
		
		//关闭数据库连接
		function close(){
			if($this->link){
				@mysql_free_result($this->result);
				@mysql_close($this->link);
			}
		}

		//错误处理
		function error($message=null){
			die($message);
		}

		//析构函数
		function __destruct(){
			$this->close();
		}

		//当方法不存在是调用该方法
		function __call($name,$arg){
			$this->error("该方法不存在！<br>方法名:$name <br>值：".implode(',',$arg)."");
		}
	}


?>