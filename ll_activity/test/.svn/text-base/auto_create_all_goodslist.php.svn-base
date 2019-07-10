<?php
$hostname = gethostname();
if (substr($hostname, 0, 1) == 'd') {
    $_SERVER['RUN_MODE'] = 'development';
}else if (substr($hostname, 0, 1) == 'p'){
    $_SERVER['RUN_MODE'] = 'production';
}
require_once dirname(dirname(__FILE__)) . '/include/config.php';
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/DB.php";
require_once SYSDIR_UTILS ."/XXRequestBase.php";
require_once SYSDIR_UTILS ."/REDIS.php";
require_once SYSDIR_UTILS . "/GPGoodsManager.class.php";

function getAllGoodsInfo() {
    $sql = "select distinct(good_id) from h5_mall_list where status > 0";
    $db = new Db();
    $db->use_db("h5gamecenter_read");
    $data = $db->query($sql);
    foreach ($data as $key => $value) {
        $result[] = $value['good_id'];
    }
    return $result;
}

function sortGoodsByHoney($data) {
    $tmp = array();
    $length = count($data);
    for ($i = 0; $i < $length; $i ++) {
        $max = $i;
        for ($j = $i; $j < $length; $j++) {
            if (isset($data[$max]['price']) && isset($data[$j]['price']) && $data[$max]['price'] < $data[$j]['price']) {
                $max = $j;
            }
        }
        if ($max != $i) {
                $tmp = $data[$max];
                $data[$max] = $data[$i];
                $data[$i] = $tmp;
        
        }
    }
    return $data;
}

function main() {
    $ids = getAllGoodsInfo();
    $manager = new GPGoodsManager();
    $infos = $manager->getGoodsInfo($ids);
    $infos = sortGoodsByHoney($infos);
    $redis = new myRedis();
    $redis->use_redis("write");
    $key = "all_goods_info_sort_by_price";
    $redis->redis->set($key, json_encode($infos), 3600);
    json_encode($infos);
}
main();
