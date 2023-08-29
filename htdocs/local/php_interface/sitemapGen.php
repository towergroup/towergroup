<?php
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../..");
 
$pass = "12345678";
 
if($pass == "12345678" && !is_cli()) die('Защита от дурака, переменная $pass не установлена.');
$access = false;
 
function is_cli()
{
	if( defined('STDIN'))return true;
	if( empty($_SERVER['REMOTE_ADDR']) and !isset($_SERVER['HTTP_USER_AGENT']) and count($_SERVER['argv']) > 0) return true;
	return false;
}
 
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
include_once($_SERVER["DOCUMENT_ROOT"]. "/bitrix/modules/main/include/prolog_before.php");
set_time_limit(0);
 
if(is_cli() || $_GET["PWD"] == $pass)
	$access = true; 
 
CModule::IncludeModule("solverweb.sitemap");
 
if($access)
	if(SWSitemapGenerate::Generate() == NULL) echo "OK";
 else
	include $_SERVER["DOCUMENT_ROOT"]."/404.php";
?>