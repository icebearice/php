<?php

// Class 目录定义
if(!defined("CLASS_DIR"))
    define("CLASS_DIR", dirname(dirname(__FILE__)) . "/class/" );
set_include_path(CLASS_DIR);
spl_autoload_extensions('.inc,.php,.class.php');
spl_autoload_register('my_autoload');


function my_autoload($classname) {
    /**
     * 文件存在的情况下 is_file比file_exists要快N倍
     * 文件不存在的情况下 is_file比file_exists要慢
     * 我们这种基本存在的啦
     */
    if(is_file($classname)){
        require_once ($classname);
    }   else {
        throw new Exception('class file' . $classname . 'not found');
    }
}

// 或者，自 PHP 5.3.0 起可以使用一个匿名函数
//spl_autoload_register(function ($class) {
//    include 'classes/' . $class . '.class.php';
//});