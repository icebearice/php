<?php
/*
 * 数据库 及 Redis 配置
 */
$DB_CONFIG = array(
   'read' => array(
        array('dbms'     => 'mysql',
        'host'     => '10.8.18.2381',
        'user'     => 'llbackend_ruser',
        'password' => 'yq70e2gv647mj1tm',
        'port'     => 3306,
        'dbname'   => 'LLBackend',
        'charset'  => 'utf8'
        ),
    ),
    'write' => array(
        array('dbms'     => 'mysql',
        'host'     => '10.8.18.238',
        'user'     => 'llbackend_wuser',
        'password' => 'i72gur75shb0leqw',
        'port'     => 3306,
        'dbname'   => 'LLBackend',
        'charset'  => 'utf8'
        ),
    ),
    'llmessagecenter' => array(
        array(
            'dbms'     => 'mysql',
            'host' => '10.8.18.229',
            'dbname' => 'LLUserMessageCenter',
            'user' => 'llmessage_read',
            'password' => 'hnuwmcs5lytolef5',
            'port' => '3306',
            'charset' => 'utf8'
        ),
    ),
    'lldaliytopic' => array(
         array(
            'dbms'     => 'mysql',
            'host' => '10.8.18.229',
            'dbname' => 'LLDaliyTopic',
            'user' => 'lldaliytopic_r',
            'password' => 'qd9twv1tbl4gu6ex',
            'port' => '3306',
            'charset' => 'utf8'
        ),
    ),
        //多个从库
);//end DB_CONFIG

$REDIS_CONFIG = array(
    'write' => array(
        array(
            'host'  => '10.8.8.79',
            'port'  => '6383',
            'password' => '',
            'dbN' => 2,
        ),
    ),
    'read' => array(
        array(
            'host'  => '10.8.8.79',
            'port'  => '6383',
            'password' => '',
            'dbN' => 2,
        ),
    ),

);


$SESSION_CONFIG = array(
    'save_handler' => 'memcache',
    'save_path' => 'tcp://pmemc02.rmz.flamingo-inc.com:11216',
    'cache_expire' => 2678400,//86400*31
    'session_name' => 'gpsessionid',
);

//缓存配置,  use_config 为0表示使用第一项配置,为1表示使用第2项
$MEM_SESS_CONFIG = array(
    'use_config' => 'memcache' ,
    'memcache'   => 
    array('type'     => 'memcache',
        'server'   => array(
            array('host' =>'127.0.0.1', 'port' => '11210', 'weight' => '10')
        ),
        'ttl'      => 7200,
        'compress' => false,
    ),
);

$XX_KEY = array(
    'decrypt' => '#%$*)&*M<><vance',
    'encrypt' => '#%$*)&*M<><vance',
);
$KEY = $XX_KEY['decrypt'];

$ETCD_SERVER_ARR = array(
    'http://10.8.8.208:2379/v2/keys',
);    


if (!defined("GET_USER_BALANCE_URL")) {
    define("GET_USER_BALANCE_URL", "http://test.sqapi.guopan.cn/honeyBalances.php");
}


if (!defined("CACHESWITCH")) {
    define("CACHESWITCH", false);
}
