<?php
require_once dirname(dirname(__DIR__))."/include/config.inc.php";
require_once dirname(dirname(__DIR__))."/include/config.php";
require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/arbor_day_activity/commonFunction.php";

function getData(){
	$__db = new Db();
	$__db->use_db("write");

	$today = date("Y-m-d");
	$today_timestamp = strtotime($today);

	$data = array(
		"# 100毫升" => "  \r\n",
		"●V0~V2满6减2库存" => 0,
		"●V0~V2满8减2库存" => 0,
		"●V0~V2满4减1库存" => 0,	
		"●V0~V2成长值库存" => 0,	
		"●V3以上1元平台币库存" => 0,	
		"●V3以上满12减3库存" => 0,	
		"●V3以上满8减2库存" => 0,	
		"●V3以上满4减1库存" => 0,	
		"# 500毫升" => "  \r\n",
		"●V0~V2满20减5代金券库存(500毫升)" => 0,	
		"●V0~V2满12减3代金券库存" => 0,	
		"●V0~V2满7减2库存" => 0,	
		"●V0~V21元平台币库存" => 0,	
		"●V3以上京东卡库存(500毫升)" => 0,	
		"●V3以上2元平台币库存" => 0,	
		"●V3以上满68减15代金券库存" => 0,	
		"●V3以上满30减8代金券库存" => 0,	
		"●V3以上满20减5代金券库存" => 0,	
		"# 1000毫升" => "  \r\n",
		"●V0~V2京东卡库存" => 0,	
		"●V0~V2满68减10代金券库存" => 0,	
		"●V0~V2满20减5代金券库存(1000毫升)" => 0,	
		"●V0~V2满10减3代金券库存" => 0,	
		"●V0~V22元平台币库存" => 0,	
		"●V3以上天猫精灵库存" => 0,	
		"●V3以上京东卡库存(1000毫升)" => 0,	
		"●V3以上满648减40代金券库存" => 0,	
		"●V3以上满328减30代金券库存" => 0,	
		"●V3以上满128减20代金券库存" => 0,	
		"# 其他" => "  \r\n",
		"●邀请好友奖励库存" => 0,	
		"●新人专享券库存" => 0,	
		"# 每日任务" => "  \r\n",
		"●把66分享至朋友圈" => 0,	
		"●每日答题" => 0,	
		"●玩1款喜欢的新游10分钟" => 0,	
		"●当日话题单条评论得5个赞" => 0,	
		"●邀请1名好友下载注册66" => 0,	
		"●在新游产生任意实消或在老游戏实消满10元" => 0	
	);

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 1";
	$res = $__db->query($sql);
	$data["●V0~V2满6减2库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 2";
	$res = $__db->query($sql);
	$data["●V0~V2满8减2库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 3";
	$res = $__db->query($sql);
	$data["●V0~V2满4减1库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 4";
	$res = $__db->query($sql);
	$data["●V0~V2成长值库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 15";
	$res = $__db->query($sql);
	$data["●V3以上1元平台币库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 16";
	$res = $__db->query($sql);
	$data["●V3以上满12减3库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 17";
	$res = $__db->query($sql);
	$data["●V3以上满8减2库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 18";
	$res = $__db->query($sql);
	$data["●V3以上满4减1库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 5";
	$res = $__db->query($sql);
	$data["●V0~V2满20减5代金券库存(500毫升)"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 6";
	$res = $__db->query($sql);
	$data["●V0~V2满12减3代金券库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 7";
	$res = $__db->query($sql);
	$data["●V0~V2满7减2库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 8";
	$res = $__db->query($sql);
	$data["●V0~V21元平台币库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 19";
	$res = $__db->query($sql);
	$data["●V3以上京东卡库存(500毫升)"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 20";
	$res = $__db->query($sql);
	$data["●V3以上2元平台币库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 21";
	$res = $__db->query($sql);
	$data["●V3以上满68减15代金券库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 22";
	$res = $__db->query($sql);
	$data["●V3以上满30减8代金券库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 23";
	$res = $__db->query($sql);
	$data["●V3以上满20减5代金券库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 10";
	$res = $__db->query($sql);
	$data["●V0~V2京东卡库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 11";
	$res = $__db->query($sql);
	$data["●V0~V2满68减10代金券库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 12";
	$res = $__db->query($sql);
	$data["●V0~V2满20减5代金券库存(1000毫升)"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 13";
	$res = $__db->query($sql);
	$data["●V0~V2满10减3代金券库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 14";
	$res = $__db->query($sql);
	$data["●V0~V22元平台币库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 24";
	$res = $__db->query($sql);
	$data["●V3以上天猫精灵库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 25";
	$res = $__db->query($sql);
	$data["●V3以上京东卡库存(1000毫升)"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 26";
	$res = $__db->query($sql);
	$data["●V3以上满648减40代金券库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 27";
	$res = $__db->query($sql);
	$data["●V3以上满328减30代金券库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 28";
	$res = $__db->query($sql);
	$data["●V3以上满128减20代金券库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 29";
	$res = $__db->query($sql);
	$data["●邀请好友奖励库存"] = $res[0]["prize_total"];

	$sql = "select prize_total from arbor_day_prize_list where prize_id = 30";
	$res = $__db->query($sql);
	$data["●新人专享券库存"] = $res[0]["prize_total"];

	$sql = "select count(*) count from arbor_day_user_completed_task_list where task_id = 1 and task_day = '{$today}'";
	$res = $__db->query($sql);
	$data["●把66分享至朋友圈"] = $res[0]["count"];

	$sql = "select count(*) count from arbor_day_user_completed_task_list where task_id = 2 and task_day = '{$today}'";
	$res = $__db->query($sql);
	$data["●每日答题"] = $res[0]["count"];

	$sql = "select count(*) count from arbor_day_user_completed_task_list where task_id = 3 and task_day = '{$today}'";
	$res = $__db->query($sql);
	$data["●玩1款喜欢的新游10分钟"] = $res[0]["count"];

	$sql = "select count(*) count from arbor_day_user_completed_task_list where task_id = 4 and task_day = '{$today}'";
	$res = $__db->query($sql);
	$data["●当日话题单条评论得5个赞"] = $res[0]["count"];

	$sql = "select count(*) count from arbor_day_user_completed_task_list where task_id = 5 and task_day = '{$today}'";
	$res = $__db->query($sql);
	$data["●邀请1名好友下载注册66"] = $res[0]["count"];

	$sql = "select count(*) count from arbor_day_user_completed_task_list where task_id = 6 and task_day = '{$today}'";
	$res = $__db->query($sql);
	$data["●在新游产生任意实消或在老游戏实消满10元"] = $res[0]["count"];

	return $data;
}
