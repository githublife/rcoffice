<meta http-equiv='content-Type' content='text/html;charset=utf-8' />
<?php
/*
	项目名称：锐诚科技工作管理系统(MVC模式)
	开发人	：小纪
	开发时间：2015-6-21

	MVC模式开发

	Model	：数据模型(数据存储)
	View	：视图(页面布局)
	Controller：控制器(用户登录，用户交互等等..)


*/

//加载配置文件
include 'public/config.php';

//加载数据库操作类
include 'public/db.php';

$db=new db();

//加载网站日志类
include 'public/log.php';
$log=new log();

$log->write();
echo '<pre>';
print_r($log);
echo '</pre>';

//入口文件

$class=isset($_GET['class'])?$_GET['class']:'welcome';
$fun=isset($_GET['fun'])?$_GET['fun']:'index';

//包含控制器
if(is_file('control/'.$class.'.php'))include('control/'.$class.'.php');

//实例化控制器
if(class_exists($class))$control=new $class();


//执行方法
if(method_exists($control,$fun))$control->$fun();









?>