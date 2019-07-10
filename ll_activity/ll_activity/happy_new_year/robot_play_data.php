<?php

/**
 * 机器人播放数据统计
 *
 */

require_once dirname(dirname(__DIR__))."/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS ."/logger.php";
require_once SYSDIR_UTILS ."/LLActivityBaseData.php";
require_once SYSDIR_UTILS ."/happynewyear/robotData.php";
require_once SYSDIR_UTILS ."/Ding.class.php";

$response = array(
	'code'=> 0,
	'err_msg' => '',
	'data'=> '',
);

$url = "https://oapi.dingtalk.com/robot/send?access_token=7a91b90678430df12ea975342bd4a004c91da018ac568925ac6979be9e562361";
$ding = new Ding($url);
$daily_topic = getDailyTopicData();
$year_prize = getAcceptYearPrizeData();
$daily_task = getDailyTaskData();
$contri_value = getContriValuePkData();
$cost_top = getCostTopData();

$flag = "预热活动播报";
$data = "# 打年货模块  \r\n";
$data.= "今日领取年货人数：".$year_prize['today_accept_prize_people_count']."  \r\n";
$data.= "累计领取年货人数：".$year_prize['all_accept_prize_people_count']."  \r\n";
$data.= "66点成长值领取人数：".$year_prize['prize_leave_count']['66growth']."  \r\n  \r\n";
$data.= "3元代金券库存：".$year_prize['prize_leave_count']['3yuan']."  \r\n";
$data.= "8元代金券库存：".$year_prize['prize_leave_count']['8yuan']."  \r\n";
$data.= "166点成长值库存：".$year_prize['prize_leave_count']['166growth']."  \r\n  \r\n";
$data.= "15元代金券库存：".$year_prize['prize_leave_count']['15yuan']."  \r\n  \r\n";
$data.= "30元代金券库存：".$year_prize['prize_leave_count']['30yuan']."  \r\n  \r\n";

if (time() >= strtotime("2019-01-31")) { 
	$data = "# 每日任务&六合彩模块  \r\n";
	$data.= "今日完成每日任务人数：".$daily_task['today_complete_people_count']."  \r\n";
	$data.= "今日完成每日话题人数：".$daily_task['complete_daily_topic_count']."  \r\n";
	$data.= "今日实消达6元人数：".$daily_task['complete_cost_sex_yuan_count']."  \r\n";
	$data.= "今日完成邀请好友人数：".$daily_task['complete_share_friend_count']."  \r\n";
	$data.= "今日合成奖励人数：".$daily_task['today_swop_prize_people_count']."  \r\n";
	$data.= "游戏周边库存：".$daily_task['game_leave_count']."  \r\n";
	$data.= "今日奖励发放金额：".$daily_task['today_prize_value_count']."  \r\n";
	$data.= "18元代金券库存：".$daily_task['prize_leave_count']['eighteen_yuan_vou']."  \r\n";
	$data.= "10元代金券库存：".$daily_task['prize_leave_count']['ten_yuan_vou']."  \r\n";
	$data.= "6元代金券库存：".$daily_task['prize_leave_count']['sex_yuan_vou']."  \r\n  \r\n";
	$data.= "50点成长值获得人数：".$daily_task['prize_leave_count']['50_growth']."  \r\n  \r\n";
	$data.= "30点成长值获得人数：".$daily_task['prize_leave_count']['30_growth']."  \r\n  \r\n";
	$data.= "# 贡献值PK模块  \r\n";
	$data.= "今日参与人数：".$contri_value['today_pk_people_count']."  \r\n";
	$data.= "今日挑战成功人数：".$contri_value['today_pk_win_people_count']."  \r\n";
	$data.= "今日获得奖励人次：".$contri_value['today_get_prize_count']."  \r\n";
	$data.= "累计参与人数：".$contri_value['all_pk_people_count']."  \r\n";
	$user = '';
	foreach($contri_value['max_win_count_uin'] as $uin){
		$user.=$uin.' ';
	}
	$data.= "当前胜利次数最高用户uin：".$user."  \r\n  \r\n";
	$data.= "# 新春排行榜模块  \r\n";
	$data.= "日榜第一名实消金额：".$cost_top['daily_top_one_cost']."  \r\n";
	$data.= "日榜前十名实消金额：".$cost_top['daily_top_ten_all_cost']."  \r\n";
	$data.= "总榜第一名实消金额：".$cost_top['total_top_one_cost']."  \r\n";
	$data.= "总榜前十名实消金额：".$cost_top['total_top_ten_all_cost']."  \r\n";
	$data.= "日榜前十假名单人数：".$cost_top['daily_top_ten_fake_uin_count']."  \r\n";
	$data.= "总榜前十假名单人数：".$cost_top['total_top_ten_fake_uin_count']."  \r\n";

	$dd = date("Y-m-d", time());
	$t1 = $dd." 08:50:00";
	$t2 = $dd." 09:10:00";
	$dt = time();
	if ($dt > strtotime($t1) && $dt < strtotime($t2)) {
		$data.= "  \t\n";
		$data.= getGivePrizeTimerData();
	}

	$flag = "正式活动播报";
}

$ding->send_markdown($flag,$data);
