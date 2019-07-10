<?php
require_once dirname(dirname(__DIR__))."/include/config.inc.php";
require_once dirname(dirname(__DIR__))."/include/config.php";
require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/happynewyear/commonFunction.php";

function getDailyTopicData(){
	$__db = new Db();
	$__db->use_db('lldaliytopic');
	$today = date("Y-m-d");
	$today_timestamp = strtotime($today);

	$data = array(
		'today_parti_people_count' => 0,           //今日参与人数
		'all_parti_people_count' => 0,             //累计参与人数
	);
	$sql = "select count(distinct uid) count from ll_reply_list where add_time >= {$today_timestamp}";
	$res = $__db->query($sql);
	if ($res){
		$data['today_parti_people_count'] = $res[0]['count'];
	}

	$start_timestamp = strtotime(date('2019-01-01'));  //活动开始时间
	$sql = "select count(distinct uid) count from ll_reply_list where add_time >= {$start_timestamp}";
	$res = $__db->query($sql);
	if ($res){
		$data['all_parti_people_count']	= $res[0]['count'];
	}
	return $data;
}

function getAcceptYearPrizeData(){
	$__db = new Db();
	$__db->use_db("write");
	$today = date("Y-m-d");
	$today_timestamp = strtotime($today);

	$data = array(
		'today_accept_prize_people_count' => 0,    //今日领取年货人数
		'all_accept_prize_people_count' => 0,      //累计领取年货人数
		'prize_leave_count' => array()             //奖励库存
	);


	$sql = "select count(distinct uin) count from ll_activity_user_accept_prize_info where prize_id between 1 and 6";
	$sql.= " and unix_timestamp(accept_time) >= {$today_timestamp}";
	$res = $__db->query($sql);
	$data['today_accept_prize_people_count'] = $res[0]['count'];

	$sql = "select count(distinct uin) count from ll_activity_user_accept_prize_info where prize_id between 1 and 6";
	$res = $__db->query($sql);
	$data['all_accept_prize_people_count']= $res[0]['count'];

	$sql = "select prize_total from ll_activity_prize_list where prize_id in(1,2,3,4,5,6)";
	$res = $__db->query($sql);
	$count = 99999999 - $res[0]['prize_total'];
	$data['prize_leave_count'] = array(
		'66growth' => $count,
		'3yuan' => $res[1]['prize_total'],
		'8yuan' => $res[2]['prize_total'],
		'166growth' => $res[3]['prize_total'],
		'15yuan' => $res[4]['prize_total'],
		'30yuan' => $res[5]['prize_total']
	);
	return $data;
}

function getDailyTaskData(){
	$__db = new Db();
	$__db->use_db("write");
	$today = date("Y-m-d");
	$today_timestamp = strtotime($today);

	$data = array(
		'today_complete_people_count' => 0,          //完成每日任务人数
		'complete_daily_topic_count' => 0,           //完成每日话题人数
		'complete_cost_sex_yuan_count' => 0,         //完成实消6元人数
		'complete_share_friend_count' => 0,          //完成邀请好友人数
		'today_swop_prize_people_count' => 0,        //今日合成奖励人数
		'game_leave_count' => 0,                     //游戏周边库存
		'today_prize_value_count' => 0,              //今日奖励发放金额
		'prize_leave_count' => array()               //奖励库存
	);

	$sql = "select count(distinct uin) count from ll_activity_user_complete_daily_task_info where task_day = '{$today}'";
	$res = $__db->query($sql);
	if ($res) {
	    $data['today_complete_people_count'] = $res[0]['count'];
	}
	$sql = "select count(*) from ll_activity_user_complete_daily_task_info where task_id in (2,5,6) and task_day = '{$today}' group by task_id";
	$res = $__db->query($sql);
	if ($res){
		$data['complete_daily_topic_count'] = isset($res[0]['count(*)']) ? $res[0]['count(*)'] : 0;
		$data['complete_cost_sex_yuan_count'] = isset($res[1]['count(*)']) ? $res[1]['count(*)'] : 0;
		$data['complete_share_friend_count'] = isset($res[2]['count(*)']) ? $res[2]['count(*)'] : 0;
	}
	$sql = "select count(distinct uin) count from ll_activity_user_accept_prize_info where prize_id between 24 and 29 and unix_timestamp(accept_time) >= {$today_timestamp}";
	$res = $__db->query($sql);
	if ($res){
		$data['today_swop_prize_people_count'] = $res[0]['count'];
	}
	$sql = "select prize_total from ll_activity_prize_list where prize_id = 24";
	$res = $__db->query($sql);
	if($res){
	    $data['game_leave_count'] = $res[0]['prize_total'];
	}
	$sql = "select prize_name from ll_activity_user_accept_prize_info where prize_id in (25,26,27)";
	$sql.= " and unix_timestamp(accept_time) >= {$today_timestamp}";
	$res = $__db->query($sql);
	$value_sum = 0;
	foreach($res as $vou){
		$value_sum += intval($vou['prize_name']);
	}
	$data['today_prize_value_count'] = $value_sum;

	$sql = "select prize_total from ll_activity_prize_list where prize_id in (25,26,27,28,29)";
	$res = $__db->query($sql);
	if($res){
		$data['prize_leave_count']['eighteen_yuan_vou'] = $res[0]['prize_total'];
		$data['prize_leave_count']['ten_yuan_vou'] = $res[1]['prize_total'];
		$data['prize_leave_count']['sex_yuan_vou'] = $res[2]['prize_total'];
		$data['prize_leave_count']['50_growth'] = 99999999 - $res[3]['prize_total'];
		$data['prize_leave_count']['30_growth'] = 99999999 - $res[4]['prize_total'];
	}
	return $data;
}

function getContriValuePkData(){
	$__db = new Db();
	$__db->use_db("write");
	$today = date("Y-m-d");
	$today_timestamp = strtotime($today);

	$data = array(
		'today_pk_people_count' => 0,             //今日参与人数
		'today_pk_win_people_count' => 0,         //今日挑战成功人数
		'today_get_prize_count' => 0,             //今日获得奖励人次
		'all_pk_people_count' => 0,               //累计参与人数
		'max_win_count_uin' => array()            //当前胜利次数最高用户uin
	);

	$sql = "select count(*) from ll_activity_user_list_of_activity where pk_times < 2";
	$res = $__db->query($sql);
	if($res){
		$data['today_pk_people_count'] = $res[0]['count(*)'];
	}
	$sql = "select count(distinct uin) count from ll_activity_user_accept_prize_info where prize_id between 7 and 13";
	$sql.=" and unix_timestamp(accept_time) >= {$today_timestamp}";
	$res = $__db->query($sql);
	if($res){
		$data['today_pk_win_people_count'] = $res[0]['count'];
	}
	$sql = "select count(*) from ll_activity_user_accept_prize_info where prize_id between 7 and 13";
	$sql.=" and unix_timestamp(accept_time) >= {$today_timestamp}";
	$res = $__db->query($sql);
	if($res){
		$data['today_get_prize_count'] = $res[0]['count(*)'];
	}
	$sql = "select count(*) from ll_activity_user_list_of_activity where all_pk_times < 30";
	$res = $__db->query($sql);
	if($res){
		$data['all_pk_people_count'] = $res[0]['count(*)'];
	}
	$sql = "select uin from ll_activity_user_list_of_activity where pk_win_count = (select max(pk_win_count) from ll_activity_user_list_of_activity)";
	$res = $__db->query($sql);
	if($res){
		foreach ($res as $user) {
			$data['max_win_count_uin'][] = $user['uin'];
		}
	}
	return $data;
}

function getCostTopData(){
	$__db = new Db();
	$__db->use_db("write");
	$today = date("Y-m-d");
	$today_timestamp = strtotime($today);

	$data = array(
		'daily_top_one_cost' => 0,                //日榜第一名实消金额
		'daily_top_ten_all_cost' => 0,            //日榜前十名实消金额
		'total_top_one_cost' => 0,                //总榜第一名实消金额
		'total_top_ten_all_cost' => 0,            //总榜前十名实消金额
		'daily_top_ten_fake_uin_count' => 0,      //日榜前十假名单人数
		'total_top_ten_fake_uin_count' => 0       //总榜前十假名单人数
	);

	$sql = "select max(num)/100 cost from ll_activity_user_cost_of_year where task_time = '{$today}'";
	$res = $__db->query($sql);
	if($res){
		$data['daily_top_one_cost'] = $res[0]['cost'];
	}
	$res = getUserCostRanking(10,0,0);
	//print_r($res);exit();
	$sum = 0;
	foreach ($res as $user) {
		if ($user['uin'] == 0){
			continue;
		}
		$sum += $user['cost'];
	}
	$data['daily_top_ten_all_cost'] = $sum;

	$sql = "select max(num)/100 cost from ll_activity_user_cost_of_year";
	$res = $__db->query($sql);
	if($res){
		$data['total_top_one_cost'] = $res[0]['cost'];
	}
	$res = getUserCostRanking(10,0,1);
	$sum = 0;
	foreach ($res as $user) {
		if ($user['uin'] == 0) {
			continue;
		}
		$sum += $user['cost'];
	}
	$data['total_top_ten_all_cost'] = $sum;

	return $data;
}

function getGivePrizeTimerData() {
	$__db = new Db();
	$__db->use_db("write");
	$today = date("Y-m-d");
	$today_timestamp = strtotime($today);

	$data = "# 昨日新春排行榜代金券发放情况  \t\n";
	$sql = "select uin, prize_name, prize_status from ll_activity_prize_give_log where 1 = 1";
	$sql.= " and prize_sour = '新春排行榜日榜' and unix_timestamp(add_time) >= {$today_timestamp}";
	$res = $__db->query($sql);
	if ($res) {
		foreach($res as $row) {
			$status = "发放失败";
			if ($row['prize_status'] == 1 ) {
				$status = "发放成功";
			}
			$data.= "uin：".$row['uin']."；代金券：".$row['prize_name']."；状态：".$status."  \t\n";
		}
	}
	return $data;
}
