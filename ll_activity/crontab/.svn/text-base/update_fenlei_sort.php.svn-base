<?php
$_SERVER['RUN_MODE'] = 'development';
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once dirname(dirname(__FILE__)) . '/include/config.inc.php';


require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/DB.php";
require_once SYSDIR_UTILS . "/XXRequestBase.php";


function getAllGameAppID() {
	$db = new Db();
	$db->use_db("read");
	$sql = "select ID, appid from ll_game_list where appid > 0";
	$data = $db->query($sql);
	return $data;
}	

function getAppNewUserAndActivityUser($appid) {
	$request = array(
		'appid' => $appid,
	);
	$data = base64_encode(xxtea_encrypt(json_encode($request), '#%$*)&*M<><vance'));
	$url = "http://pdatbiz03.rmz.flamingo-inc.com:9179/llsdk/uuid_new_active.php";
	$response = send_http_request($url, $data);
	$response = json_decode(xxtea_decrypt(base64_decode($response), '#%$*)&*M<><vance'), true);
	if ($response['errcode'] != 0 || !isset($response['response']) || count($response['response']) <= 1) {
		return null;
	}
	return $response['response'];
}

function getPayUserInfo($appid) {
	$db = new Db();
	$db->use_db("llpay");
	$before = time() - 86400 * 7;
	$sql = "select count(1) as total from pay_dev_log where app_id = '{$appid}' and status = 1 and add_time >= {$before}";
	$data = $db->query($sql);
	if (isset($data) && count($data) <= 0) {
		return 0;
	}
	return $data[0]['total'];
}

function getAppSortNumber($appid) {
	$pay = getPayUserInfo($appid);
	$new_act = getAppNewUserAndActivityUser($appid);
	$number = $pay *  2; 
	if (!isset($new_act)) {
		return $number;
	}
	foreach ($new_act as $k=>$v) {
		if (isset($v['uuid_new'])) {
			$number = $number + $v['uuid_new'];
		}
		if (isset($v['uuid_active'])) {
			$number = $number + $v['uuid_active'];
		}
	}
	return $number;
}

function updateGameFenleiInfo($gid, $sort_num) {
	$sql = "update ll_game_category set sort_num = {$sort_num} where game_id = {$gid} and should_sort = 1";
	$db = new Db();
	$db->use_db("write");
	var_dump($sql);
	$db->query($sql);
	return;
}

function main() {
	$allGames = getAllGameAPPID();
	foreach ($allGames as $k=>$v) {
		$sort = getAppSortNumber($v['appid']);
		updateGameFenleiInfo($v['ID'], $sort);
	}
}

main();
