<?php
	/*
		
		项目名称：网站日志记录
		开发人	：小纪
		开发时间：2015/7/23


	*/

	class log{
		var $ip;		//IP地址
		var $sid;		//会话ID
		var $uid;		//用户ID
		var $time;		//时间
		var $url;		//访问的URL
		var $agent;		//浏览器信息

		var $addr;		//实际地址
		var $os;		//系统信息  
		var $browser;	//浏览器信息
		
		var $db;		//数据库操作类

		//构造方法，初始化数据
		function __construct(){
			$this->ip=$_SERVER['REMOTE_ADDR'];
			$this->sid=session_id();
			$this->uid=isset($_SESSION['uid'])?$_SESSION['uid']:'0';
			$this->time=time();
			$this->url='http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
			$this->agent=$_SERVER['HTTP_USER_AGENT'];
			$this->os=isset($_SESSION['os'])?$_SESSION['os']:$this->analysisos();
			$this->browser=isset($_SESSION['browser'])?$_SESSION['browser']:$this->analysisbrowser();
			$this->addr=isset($_SESSION['addr'])?$_SESSION['addr']:$this->get_addr();
			$this->db=new db();
		}

		//写入日志
		function write(){
			$sql="insert into weblog(id,uid,sid,url,addr,ip,agent,os,browser,time) values(null,'$this->uid','$this->sid','$this->url','$this->addr','$this->ip','$this->agent','$this->os','$this->browser','$this->time')";
			$sql=htmlspecialchars($sql);
			$this->db->query($sql);
		}

		//查看日志
		function view($model){
		}

		//分析操作系统
		function analysisos(){
			$agent=$this->agent;
			$agent=strtolower($agent);
			if(strpos($agent,'windows')){
				$win=substr($agent,strpos($agent,'windows'),14);
				switch($win){
					case 'windows nt 5.0':
						$os='Windows 2000';
						break;
					case 'windows nt 5.1':
						$os='Windows XP';
						break;
					case 'windows nt 5.2':
						$os='Windows Server 2003 or Windows XP 64bit';
						break;
					case 'windows nt 6.0':
						$os='Windows Vista or Windows Server 2008';
						break;
					case 'windows nt 6.1':
						$os='Windows 7';
						break;
					case 'windows nt 6.2':
						$os='Window 8 or Windows Server 2012';
						break;
					case 'windows nt 6.3':
						$os='Windows 8.1 or Windows Server 2012 R2';
						break;
					case 'windows nt 6.4':
						$os='Windows 10';
						break;
					case 'windows nt 10.0':
						$os='Windows 10';
						break;
					default:
						$os=$win;
						break;
				}
			}else if(strpos($agent,'android')){
				$os=substr($agent,strpos($agent,'android'),13);
			}else if(strpos($agent,'mac os')){
				$os=substr($agent,strpos($agent,'mac os'),15);
			}else if(strpos($agent,'windows phone')){
				$os=substr($agent,strpos($agent,'windows phone'),17);
			}else{
				$os='未知操作系统';
			}
			$os=strtoupper($os);
			$_SESSION['os']=$os;

			return $os;
		}


		//分析浏览器
		function analysisbrowser(){
			$agent=$this->agent;
			$agent=strtolower($agent);
			if(strpos($agent,'msie')){
				$browser=substr($agent,strpos($agent,'msie'),8);
			}else if(strpos($agent,'mqqbrowser')){
				$browser=substr($agent,strpos($agent,'mqqbrowser'),14);
			}else if(strpos($agent,'miuibrowser')){
				$browser=substr($agent,strpos($agent,'miuibrowser'),17);
			}else if(strpos($agent,'ucweb')){
				$browser=substr($agent,strpos($agent,'ucweb'),14);
			}else{
				$browser='未知浏览器';
			}
			$browser=strtoupper($browser);
			$_SESSION['browser']=$browser;

			return $browser;
		}

		//取得实际地址
		function get_addr(){
			//取得实际地址
			$url='http://wap.ip138.com/ip_search138.asp?ip='.$this->ip;
			$content=file_get_contents($url);
			$addr=explode('<br/><b>',$content);
			$addr=explode('</b><br/>',$addr[1]);
			$_SESSION['addr']=$addr[0];
			return $addr[0];
		}
	
	
	}


?>