<?php
/*
 * 根据参数，新增榜单数
 * author zhibin.hong
 * date 2019-02-15
 *
 */
require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once SYSDIR_UTILS . '/DB.php';

echo '请输入开始的日期(格式如2019-02-15 00:00:01):';
$date = trim(fgets(STDIN));

echo '请输入开始的榜单期数:';
$board_num = trim(fgets(STDIN));

echo '请输入每期榜单的持续天数:';
$days = trim(fgets(STDIN));

echo '请输入需要增加的榜单期数，1-10整数:';
$times = trim(fgets(STDIN));

echo "开始日期：{$date} \n持续天数：{$days}\n增加榜单期数：{$times}\n请确认信息无误[Y/N]：";
while (!feof(STDIN)) {
    $res = fread(STDIN, 1024);
    if (trim($res) === 'Y') {
        break;
    } else if (trim($res) === 'N') {
        echo "取消操作\n";
        exit;
    }
    echo "\n开始日期：{$date}开始期数：{$board_num} \n持续天数：{$days}\n增加榜单期数：{$times}\n请确认信息无误[Y/N]：";
}

//echo 'do some thing...';
if (!is_numeric($times) || !is_numeric($board_num) || !is_numeric($days)) {
    echo "榜单期数、榜单期数或者榜单持续天数有误，请重新输入\n";
    exit;
}

$obj = new Db();
$obj->use_db( 'llcommunity' );
for($i=0; $i<$times; $i++) {
    $starttime = strtotime($date) + $i * 86400 * $days;
    $endtime = $starttime + 86400 * $days - 2;
    $closetime = $endtime + 1;
    $startdate = date('Y-m-d H:i:s', $starttime);
    $enddate = date('Y-m-d H:i:s', $endtime);
    $closedate = date('Y-m-d H:i:s', $closetime);
    echo "$startdate $enddate $closedate\n";

    $arr = array(
        'name' => '66手游最受欢迎游戏('.($board_num+$i).'期)',
        'platform' => 101,
        'add_time' => time(),
        'start_time' => $starttime,
        'end_time' => $endtime,
        'close_time' => $closetime,
        'status' => 1,
        'comment_limit' => 1,
        'voting_banner' => '',
        'vote_banner' => '',
        'remark' => '66手游最受欢迎游戏('.($board_num+$i).'期)_ios',
    );
    $obj->insert('ll_board_info', $arr);    
    $arr['platform'] = 102;
    $arr['comment_limit'] = 2;
    $arr['remark'] = '66手游最受欢迎游戏('.($board_num+$i).'期)_android';
    $obj->insert('ll_board_info', $arr);    
}
