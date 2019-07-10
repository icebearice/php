<?php
/*
 * 数据库 及 Redis 配置
 */
$DB_CONFIG = array(
    'read' => array(
        array(
            'dbms'     => 'mysql',
            'host'     => '192.168.6.111',
            'user'     => 'root',
            'password' => '123456',
            'port'     => 3306,
            'dbname'   => 'LLActivity',
            'charset'  => 'utf8'
        ),
    ),
    'write' => array(
        array(
            'dbms'     => 'mysql',
            'host'     => '192.168.6.111',
            'user'     => 'root',
            'password' => '123456',
            'port'     => 3306,
            'dbname'   => 'LLActivity',
            'charset'  => 'utf8'
        ),
    ),
    'llpay' => array(
        array(
            'dbms'     => 'mysql',
            'host'     => '192.168.6.114',
            'user'     => 'root',
            'password' => '0ad1b3ab9e6c164b',
            'port'     => 3306,
            'dbname'   => 'LLPay',
            'charset'  => 'utf8'
        ),
    ),
    'llbackend' => array(
        array(
            'dbms'     => 'mysql',
            'host'     => '192.168.6.111',
            'user'     => 'root',
            'password' => '123456',
            'port'     => 3306,
            'dbname'   => 'LLBackend',
            'charset'  => 'utf8'
        ),
    ),
    'lldaliytopic' => array(
        array(
            'dbms'     => 'mysql',
            'host'     => '192.168.6.111',
            'user'     => 'root',
            'password' => '123456',
            'port'     => 3306,
            'dbname'   => 'LLDaliyTopic',
            'charset'  => 'utf8'
        ),
    ),
    'llcommunity'=>array(
        array(
            'dbms'     => 'mysql',
            'host' => '192.168.6.111',
            'dbname' => 'LLGameCommunity',
            'user' => 'root',
            'password' => '123456',
            'port' => '3306',
            'charset' => 'utf8'
        ),
    ),
    'accountTrans'=>array(
        array(
            'dbms'     => 'mysql',
            'host' => '192.168.6.114',
            'dbname' => 'AccountTransactions',
            'user' => 'root',
            'password' => '0ad1b3ab9e6c164b',
            'port' => '3306',
            'charset' => 'utf8'
        ),
    ),
    'lldevicelog'=>array(
        array(
            'dbms'     => 'mysql',
            'host' => '192.168.6.114',
            'dbname' => 'LLUserDeviceLog',
            'user' => 'root',
            'password' => '0ad1b3ab9e6c164b',
            'port' => '3306',
            'charset' => 'utf8'
        ),
    ),
    'llmessagecenter' => array(
        array(
            'dbms'     => 'mysql',
            'host' => '192.168.6.111',
            'dbname' => 'LLUserMessageCenter',
            'user' => 'root',
            'password' => '123456',
            'port' => '3306',
            'charset' => 'utf8'
        ),        
    ),
    'gppay' => array(
        array(
            'dbms'     => 'mysql',
            'host' => '192.168.6.237',
            'dbname' => 'GPPay',
            'user' => 'root',
            'password' => '123456',
            'port' => '3306',
            'charset' => 'utf8'
        ),        
    ),
    'developer' => array(
        array(
            'dbms'     => 'mysql',
            'host' => '192.168.6.237',
            'dbname' => 'Developer',
            'user' => 'root',
            'password' => '123456',
            'port' => '3306',
            'charset' => 'utf8'
        ),        
    ),
    //多个从库
);//end DB_CONFIG

$REDIS_CONFIG = array(
    'write' => array(
        array(
            'host'  => '192.168.6.114',
            'port'  => '6379',
            'password' => '',
            'dbN' => 2,
        ),
    ),
    'read' => array(
        array(
            'host'  => '192.168.6.114',
            'port'  => '6379',
            'password' => '',
            'dbN' => 2,
        ),
    ),
);


$SESSION_CONFIG = array(
    'save_handler' => 'memcache',
    'save_path' => 'tcp://192.168.6.114:11210',
    //'save_handler' => 'redis',
    //'save_path' => 'tcp://127.0.0.1:6379?persistent=1&prefix=',
    'cache_expire' => 7200,
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
    'http://192.168.6.111:2379/v2/keys',
);    


if (!defined("GET_USER_BALANCE_URL")) {
    define("GET_USER_BALANCE_URL", "http://test.sqapi.guopan.cn/honeyBalances.php");
}


if (!defined("CACHESWITCH")) {
    define("CACHESWITCH", false);
}

if (!defined("PAYCENTER_LLICON_KEY")) {
    define("PAYCENTER_LLICON_KEY", "liuliu_activity");
}

if (!defined("PAYCENTER_LLICON_SECRET")) {
    define("PAYCENTER_LLICON_SECRET", "13173a83ab964f7cc842002107dc3c0c");
}

if (!defined("PAYCENTER_LLICON_URL")) {
    define("PAYCENTER_LLICON_URL", "http://test.paycenter.guopan.cn/recharge/balance");
}

if (!defined("PAYCENTER_LLICON_REBATE_URL")) {
    define("PAYCENTER_LLICON_REBATE_URL", "http://test.paycenter.guopan.cn/recharge/liu_rebate");
}

if (!defined("PAYCENTER_GPICON_KEY")) {
    define("PAYCENTER_GPICON_KEY", "guopan_app");
}

if (!defined("PAYCENTER_GPICON_SECRET")) {
    define("PAYCENTER_GPICON_SECRET", "682fd820536c183d10b333a1e7cb5f15");
}

if (!defined("PAYCENTER_GPICON_REBATE_URL")) {
    define("PAYCENTER_GPICON_REBATE_URL", "http://test.paycenter.guopan.cn/recharge/rebate");
}

if (!defined("SHAOSAN_VOUCHERID")) {
	define ("SHAOSAN_VOUCHERID", 2831);
}
global $ACCESS_KEY;
$ACCESS_KEY = [
    'shaosan_hanhua' => [
        'secret' => 'yinghuihenshuai',
        'desc' => '备注',
    ],
];

if (!defined("SHARE_URL_PRE")) {
	define ("SHARE_URL_PRE", 'http://h5.testing.66shouyou.cn/ll_activity/201903_InviteFriend/index.html');
}
