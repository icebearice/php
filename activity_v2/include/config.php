<?php

// Class 目录定义
if(!defined("CLASS_DIR"))
    define("CLASS_DIR", dirname(dirname(__FILE__)) . "/class/" );
if(!defined("PHP_DIR"))
    define("PHP_DIR", dirname(dirname(__FILE__)) . "/php/" );
//spl_autoload_extensions('.inc,.php,.class.php'); no use
spl_autoload_register('autoload_php');
spl_autoload_register('autoload_class');


function autoload_php($classname) {
    $classpath = PHP_DIR . $classname . ".php";
    if(is_file($classpath)){
        require_once ($classpath);
    }   else {
        throw new Exception("not exist" . $classname . " php");
    }
    /**
     * 文件存在的情况下 is_file比file_exists要快N倍
     * 文件不存在的情况下 is_file比file_exists要慢
     * 我们这种基本存在的啦
     */
}

function autoload_class($classname) {
    $classpath = CLASS_DIR . $classname . ".class.php";
    if(is_file($classpath)){
        require_once ($classpath);
    }   else {
        throw new Exception("not exist" . $classname . " class");
    }
}

// 或者，自 PHP 5.3.0 起可以使用一个匿名函数
//spl_autoload_register(function ($class) {
//    include 'classes/' . $class . '.class.php';
//});