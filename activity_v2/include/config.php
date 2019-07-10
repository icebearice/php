<?php
/*
 * 配置文件
 */

// 定义常量
if(!defined("SYSDIR_ROOT"))
    define("SYSDIR_ROOT", dirname(dirname(__FILE__)) );
if(!defined("SYSDIR_INCLUDE"))
    define("SYSDIR_INCLUDE", SYSDIR_ROOT . "/include" );
if(!defined("SYSDIR_UTILS"))
    define("SYSDIR_UTILS", SYSDIR_ROOT . "/utils" );
if(!defined("SYSDIR_API"))
    define("SYSDIR_API",   SYSDIR_ROOT . "/api" );
if(!defined("SYSDIR_CACHE"))
    define("SYSDIR_CACHE",   SYSDIR_ROOT . "/cache" );

if (!defined("SYSDIR_PROTOCOLS")) 
    define("SYSDIR_PROTOCOLS", SYSDIR_ROOT . "/protocols");
if(!defined("CACHESWITCH")){     //缓存开关 true开 false 关
    define("CACHESWITCH", FALSE);
}


if(!defined("INCLUDE_CONFIG_PHP_FILE"))
{
    define("INCLUDE_CONFIG_PHP_FILE", TRUE );
    // 数据库 及 redis 配置文件
    include_once SYSDIR_INCLUDE . "/config.inc.php";
}

if(!defined('PAYCENTER_KEY')){
    define('PAYCENTER_KEY', 'liuliu_app');
}
if(!defined('PAYCENTER_SECRET')){
    define('PAYCENTER_SECRET', '6b4dbf965e7c9d5ebc57b0df6775a000');
}
define("LL_GRANT_VOUCHER_API", "http://test.paycenter.guopan.cn/voucher/llsend");

/*
// 控制台命令行执行时,转换命令行参数
if (empty($_SERVER["REQUEST_URI"]) && $_SERVER["argc"]>=1)
{   //is command line to run this phpfile 
    $argv = $_SERVER["argv"][1];
    parse_str($argv, $_REQUEST);
    parse_str($argv, $_GET);
    define("CRLF", "\r\n");
}
else
{
    //is web(ie/chrome/firefox) to run this phpfile
    define("CRLF", "<BR>\r\n");
}
*/
/*
if(isset($SESSION_CONFIG)){
    session_set_cookie_params( 0, '/', '.66shouyou.cn' );
    session_save_path( $SESSION_CONFIG['save_path'] );
    session_cache_expire( $SESSION_CONFIG['cache_expire'] );
    session_name( $SESSION_CONFIG['session_name'] );
    //if( $_COOKIE['gpsessionid'] ) @session_id(  $_COOKIE['gpsessionid'] );
    session_start();
}
if( file_exists( SYSDIR_ROOT . "/360safe/360webscan.php" ) ){
    if ( ! function_exists('webscan_cheack') ){
        require_once SYSDIR_ROOT . "/360safe/360webscan.php";
    }
}
*/
session_set_cookie_params( 0, '/', '.66shouyou.cn' );
session_name('llusersessionid');
session_start();

function __autoload($classname) {
    $classpath = SYSDIR_UTILS . "/" . $classname . '.class.php';
    if(file_exists($classpath)){
        require_once($classpath);
    }   else {
        throw new Exception('class file' . $classpath . 'not found');
    }
}