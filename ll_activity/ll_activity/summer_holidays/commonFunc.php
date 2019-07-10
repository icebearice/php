<?php
require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/GPUserAuthServer.class.php";

function checkLogin() {
    $uin = isset($_REQUEST['uin'])&&is_numeric($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
    $login_key = isset($_REQUEST['login_Key']) ? $_REQUEST['login_Key'] : '';
    $uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : '';
    $productID = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 136;
    $platform = isset($_REQUEST['platformType']) ? $_REQUEST['platformType'] : 102;
    $appid = isset($_REQUEST['appID']) ? $_REQUEST['appID'] : 0;
    if (!$uin) {
        return 0;
    }
    $systemType = getSystemType();
    if (!$systemType) {
        return 0;
    }
    if ($systemType == 1) {
        $auth = new GPUserAuthServer();
    } else {
        $auth = new LLUserAuthServer();
    }
    $res = $auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid);
    if (!$res) {
        return 0;
    }
    return $uin;
}

function getSystemType() {
    $productID = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 136;
    if ($productID==136 || $productID==137) {
        return 2;
    } 
    if ($productID==109 || $productID==104) {
        return 1;
    }
    return 0;
}

function isActivityTime($time = 0) {
    $startTime = getStartTime();
    $endTime = getEndTime();
    if (!$time) {
        $time = time();
    }
    if ($time < strtotime($startTime)) {
        return 1; //未开始
    }
    if ($time > strtotime($endTime)) {
        return -1; //已结束
    }
    return 0;
}

function getStartTime() {
    return '2019-07-08 00:00:00';
}

function getEndTime() {
    return '2019-08-04 23:59:59';
}

function getSignInfo() {
    return array(
        1 => array('id'=>1, 'name'=>'海岛', 'showData'=>'7.26', 'signTime'=>'20190726', 'num'=>15,),
        2 => array('id'=>2, 'name'=>'峡谷', 'showData'=>'7.27', 'signTime'=>'20190727', 'num'=>18,),
        3 => array('id'=>3, 'name'=>'沙漠', 'showData'=>'7.28', 'signTime'=>'20190728', 'num'=>30,),
        4 => array('id'=>4, 'name'=>'草原', 'showData'=>'7.29', 'signTime'=>'20190729', 'num'=>20,),
        5 => array('id'=>5, 'name'=>'湖泊', 'showData'=>'7.30', 'signTime'=>'20190730', 'num'=>22,),
        6 => array('id'=>6, 'name'=>'火山', 'showData'=>'7.31', 'signTime'=>'20190731', 'num'=>24,),
        7 => array('id'=>7, 'name'=>'雨林', 'showData'=>'8.1', 'signTime'=>'20190801', 'num'=>35,),
        8 => array('id'=>8, 'name'=>'洞穴', 'showData'=>'8.2', 'signTime'=>'20190802', 'num'=>26,),
        9 => array('id'=>9, 'name'=>'雪山', 'showData'=>'8.3', 'signTime'=>'20190803', 'num'=>28,),
        10 => array('id'=>10, 'name'=>'极地', 'showData'=>'8.4', 'signTime'=>'20190804', 'num'=>40,),
    );
}

function getFightInfo($systemType, $id=0) {
    $arr =  array(
        array('id'=>1, 'name'=>'满30减8代金券', 'spendNum'=>10, 'limitVip'=>0, 'tips'=>'', 'num'=>5134, 'type'=>2, 'hitNum'=>80, 'systemType'=>2,),
        array('id'=>2, 'name'=>'满68减20代金券', 'spendNum'=>15, 'limitVip'=>0, 'tips'=>'', 'num'=>5136, 'type'=>2, 'hitNum'=>30, 'systemType'=>2,),
        array('id'=>3, 'name'=>'6平台币', 'spendNum'=>15, 'limitVip'=>1, 'tips'=>'VIP0以上用户专享', 'num'=>6, 'type'=>4, 'hitNum'=>30, 'systemType'=>2,),
        array('id'=>4, 'name'=>'满328减50代金券', 'spendNum'=>20, 'limitVip'=>1, 'tips'=>'VIP0以上用户专享', 'num'=>5138, 'type'=>2, 'hitNum'=>30, 'systemType'=>2,),
        array('id'=>5, 'name'=>'满648减80代金券', 'spendNum'=>23, 'limitVip'=>2, 'tips'=>'VIP1以上用户专享', 'num'=>5140, 'type'=>2, 'hitNum'=>20, 'systemType'=>2,),
        array('id'=>6, 'name'=>'66平台币', 'spendNum'=>25, 'limitVip'=>2, 'tips'=>'VIP1以上用户专享', 'num'=>66, 'type'=>4, 'hitNum'=>2, 'systemType'=>2,),
        array('id'=>7, 'name'=>'满6减3代金券', 'spendNum'=>10, 'limitVip'=>0, 'tips'=>'', 'num'=>62948, 'type'=>2, 'hitNum'=>150, 'systemType'=>1,),
        array('id'=>8, 'name'=>'满20减10代金券', 'spendNum'=>15, 'limitVip'=>0, 'tips'=>'', 'num'=>62950, 'type'=>2, 'hitNum'=>100, 'systemType'=>1,),
        array('id'=>9, 'name'=>'满68减34代金券', 'spendNum'=>15, 'limitVip'=>1, 'tips'=>'VIP0以上用户专享', 'num'=>62952, 'type'=>2, 'hitNum'=>50, 'systemType'=>1,),
        array('id'=>10, 'name'=>'满328减160代金券', 'spendNum'=>20, 'limitVip'=>1, 'tips'=>'VIP0以上用户专享', 'num'=>62954, 'type'=>2, 'hitNum'=>50, 'systemType'=>1,),
        array('id'=>11, 'name'=>'10果币卡', 'spendNum'=>25, 'limitVip'=>2, 'tips'=>'VIP1以上用户专享', 'num'=>10, 'type'=>4, 'hitNum'=>30, 'systemType'=>1,),
        array('id'=>12, 'name'=>'50果币卡', 'spendNum'=>25, 'limitVip'=>2, 'tips'=>'VIP1以上用户专享', 'num'=>50, 'type'=>4, 'hitNum'=>5, 'systemType'=>1,),
    );
    $res = array();
    foreach ($arr as $v) {
        if ($v['systemType'] != $systemType) {
            continue;
        }
        if ($id > 0) {
            if ($v['id'] == $id) {
                return $v; //如果有id，直接返回一维数组
            } 
        }
        $res[] = $v;
    }
    return $res;
}

function getTaskInfo() {
    return array(
        1 => array('id'=>1, 'name'=>'参与话题回复', 'desc'=>'清凉值+10', 'systemType'=>2, 'num'=>10, 'check'=>1,),
        2 => array('id'=>2, 'name'=>'分享活动，每个好友打开清凉值+5', 'desc'=>'好友在页面点赞，清凉值再+3', 'systemType'=>0, 'num'=>0, 'check'=>0,),
        3 => array('id'=>3, 'name'=>'登录没玩过的新游', 'desc'=>'清凉值+10', 'systemType'=>1, 'num'=>10, 'check'=>1,),
        4 => array('id'=>4, 'name'=>'当日累计实消满6元', 'desc'=>'清凉值+20', 'systemType'=>0, 'num'=>20, 'check'=>1,),
        5 => array('id'=>5, 'name'=>'当日累计实消满66元', 'desc'=>'清凉值+60', 'systemType'=>0, 'num'=>60, 'check'=>1,),
        6 => array('id'=>6, 'name'=>'参与每日签到', 'desc'=>'每次签到可获相应清凉值', 'systemType'=>0, 'num'=>10, 'check'=>1,),
    );
}

function getLotteryRes($info) {
    $randomNum = mt_rand(1, 100);
    $tmpNum = 0;
    foreach($info as $k => $v){
        if($randomNum <= $v['probability'] + $tmpNum){
            return $v;
        }
        
        $tmpNum += $v['probability'];
    }
}   

function addLog() {
    $stack = debug_backtrace();
    $args = func_get_args();
    $file = count($stack) > 1 ? $stack[1]["file"] : $stack[0]["file"];
    $data = "";
    $file_name = sprintf("/tmp/summer_%s_%s.log", date("Y-m-d"), $file);
    if (count($stack) > 1) {
        $data = sprintf("%s %s %d ", date("Y-m-d H:i:s"), $stack[1]["function"], $stack[0]["line"]);
    } else {
        $data = sprintf("%s %s %d ", date("Y-m-d H:i:s"), $stack[0]["function"], $stack[0]["line"]);
    }
    @file_put_contents($file_name, $data. json_encode($args, JSON_UNESCAPED_UNICODE). "\r\n", FILE_APPEND);
}
