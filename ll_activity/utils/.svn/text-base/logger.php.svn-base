<?php

class FlamingoLogger {
    private static $_instance;
    private $file_name;
    private function __construct() {
        $this->file_name = sprintf("/tmp/%s_logger.log",date("Y-m-d"));
    }
    public function setLoggerFileName($name) {
        $this->file_name = $name;
    }
    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public function Logln() {
        $stack = debug_backtrace();
        $args = func_get_args();
        $data = "";
        if (count($stack) > 1) {
            $data = sprintf("%s %s %s %d ", date("Y-m-d H:i:s"), $stack[1]["file"], $stack[1]["function"], $stack[0]["line"]);
        }else {
            $data = sprintf("%s %s %s %d ", date("Y-m-d H:i:s"), $stack[0]["file"], $stack[0]["function"], $stack[0]["line"]);
        }
        @file_put_contents($this->file_name, $data. json_encode($args, JSON_UNESCAPED_UNICODE). "\r\n", FILE_APPEND);
    }
}

class Logger1{

    static function loghere($file,$content){
        @file_put_contents($file, "[". strftime("%Y%m%d%H%M%S",time())."]". var_export($content,true)."\n", FILE_APPEND );
    }
    static function loghere1($content){
        @file_put_contents('/tmp/log1.txt', "[". strftime("%Y%m%d%H%M%S",time())."]". $content."\n", FILE_APPEND );
    }
}
