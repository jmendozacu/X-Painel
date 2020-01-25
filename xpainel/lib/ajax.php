<?
require_once('../class/config.php');
$ajax = new A();
if(! isset($_GET['function']))
{
	die('No Function, Thanks');
}
if(! method_exists($ajax,$_GET['function']))
{
	die($_GET['function'].'-No Thanks');
}
die($ajax->$_GET['function']());
