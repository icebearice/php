<?php
/**
 *
 * User: ASUS
 * Date: 2019/4/16
 * Time: 9:50
 *
 * 钉钉机器人 -- 每隔1小时播报活动信息
 */

// * */1 * * * php /services/ll_activity/ll_activity/51Day/robotData.php

require_once dirname(dirname(__DIR__))."/include/config.php";
require_once dirname(__FILE__) . '/common/config.php';
require_once SYSDIR_UTILS ."/Ding.class.php";
require_once SYSDIR_UTILS . '/DB.php';



$data = "";
$res = getData($prizeArr);
foreach ($res as $key => $value) {
    $data .= $key . ": ". $value . " \r\n";
}

$url = "https://oapi.dingtalk.com/robot/send?access_token=881c1c8f312d47c496b50e9a71e127bf95a55a51ddd04c4fe5eb252a501be30b";
$ding = new Ding($url);
$ding->send_markdown("猎狗", $data);


function getData ($prizeArr) {
    $obj = new Db();
    $obj->use_db('read');
    $today = date('Y-m-d', time());

    $data = array(
        "### 今日V0~V2抽奖库存" => "",
        "- 满6减1(V0)" => 0,
        "- 满20减5(V0)" => 0,
        "- 满128减20(V0)" => 0,
        "- 成长值(V0)" => 0,
        "### 今日V3以上抽奖库存" => "",
        "- 满6减1(V3)" => 0,
        "- 满20减5(V3)" => 0,
        "- 满128减20(V3)" => 0,
        "- 满648减100(V3)" => 0,
        "- 京东卡(V3)" => 0,
        "- 游戏抱枕(V3)" => 0,
        "### 好友助力得券库存" => "",
        "- 满6减2" => 0,
        "- 满12减4" => 0,
        "- 满20减8" => 0,
        "- 满68减25" => 0,
        "- 满128减45" => 0,
        "### 今日消费任务完成用户数" => "",
        "- 单笔实消满10元" => 0,
        "- 单笔实消满100元" => 0,
        "### 充值任务得券库存" => "",
        "- *满6减2" => 0,
        "- *满12减4" => 0,
        "- *满20减8" => 0,
        "- *满68减25" => 0,
        "- *满128减45" => 0,
    );

    // 抽奖
    $sqlNum = "SELECT prize_id, v2_total_num, v3_total_num,activity_date FROM ll_51_prize_daily_num WHERE prize_id IN(1,2,3,4,5,6,7)";
    $sqlData = $obj->query($sqlNum);
    foreach ($sqlData as $k => &$v) {
        if ($v['activity_date'] != $today) {
            $v2_daily_num = $prizeArr[$v['prize_id'] - 1]['v2_daily_num'];
            $v3_daily_num = $prizeArr[$v['prize_id'] - 1]['v3_daily_num'];
            $v['v2_total_num'] = $v2_daily_num;
            $v['v3_total_num'] = $v3_daily_num;
        }
    }

    // 消费
    $todayTime = strtotime($today);
    $tomoTime = $todayTime + 86400;
    $sqlConTen = "SELECT fin_job_ten_date FROM ll_51_consume_log WHERE fin_job_ten_date >= {$todayTime} AND fin_job_ten_date < {$tomoTime}";
    $tenData = $obj->query($sqlConTen);
    $sqlConHun = "SELECT fin_job_hundred_date FROM ll_51_consume_log WHERE fin_job_hundred_date >= {$todayTime} AND fin_job_hundred_date < {$tomoTime}";
    $hunData = $obj->query($sqlConHun);

    // 助力 和 充值
    $obj->use_db('llpay');
    $sql = "SELECT id,remain_num FROM pay_voucher WHERE id IN(3286, 3288, 3290, 3292, 3294, 3296, 3298, 3300, 3302, 3304)";
    $helpConData = $obj->query($sql);


    // 抽奖
    // v0~v2赋值
    $data['- 满6减1(V0)'] = $sqlData[0]['v2_total_num'];
    $data['- 满20减5(V0)'] = $sqlData[2]['v2_total_num'];
    $data['- 满128减20(V0)'] = $sqlData[1]['v2_total_num'];
    $data['- 成长值(V0)'] = $sqlData[3]['v2_total_num'];
    // v3赋值
    $data['- 满6减1(V3)'] = $sqlData[0]['v3_total_num'];
    $data['- 满20减5(V3)'] = $sqlData[2]['v3_total_num'];
    $data['- 满128减20(V3)'] = $sqlData[1]['v3_total_num'];
    $data['- 满648减100(V3)'] = $sqlData[5]['v3_total_num'];
    $data['- 京东卡(V3)'] = $sqlData[4]['v3_total_num'];
    $data['- 游戏抱枕(V3)'] = $sqlData[6]['v3_total_num'];

    // 好友助力
    $data['- 满6减2'] = $helpConData[0]['remain_num'];
    $data['- 满12减4'] = $helpConData[1]['remain_num'];
    $data['- 满20减8'] = $helpConData[2]['remain_num'];
    $data['- 满68减25'] = $helpConData[3]['remain_num'];
    $data['- 满128减45'] = $helpConData[4]['remain_num'];

    // 消费任务
    $data['- 单笔实消满10元'] = count($tenData);
    $data['- 单笔实消满100元'] = count($hunData);

    // 充值奖励
    $data['- *满6减2'] = $helpConData[5]['remain_num'];
    $data['- *满12减4'] = $helpConData[6]['remain_num'];
    $data['- *满20减8'] = $helpConData[7]['remain_num'];
    $data['- *满68减25'] = $helpConData[8]['remain_num'];
    $data['- *满128减45'] = $helpConData[9]['remain_num'];


    return $data;
}



