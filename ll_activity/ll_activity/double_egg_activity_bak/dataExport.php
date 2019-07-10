<?php
/**
 * 双旦活动数据导出
 *
 */

require_once dirname(dirname(__DIR__))."/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/grouthServer.class.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

$action = isset($_GET['action']) ? $_GET['action'] : 'break_egg';
$db = new Db();
$db->use_db('read');
//print_r($db);die;

if ($action == 'break_egg') {
	$sql = "select id,uin,task_id,task_time from ll_activity_user_task_list where task_id = 9 and task_time >='2018-12-13' and task_time <= '2019-01-02' and uin > 0 order by id desc";
	$break_info = $db->query($sql);
	//print_r($break_info);die; 
	if ($break_info) {
		$all_coupon_info = $all_group_info = [];
		$obj = new LLGrouthServer();
		foreach($break_info as &$v) {
			$res = $obj->getUserVipLevel($v['uin'],'','',151,102,0);
			$v['vip_level'] = 0;
			if ($res) {                                                                    
				$v['vip_level'] = $res['vip_level'];                                                   
			}                                                                              
			$start_time = $v['task_time'];
			$end_time = date('Y-m-d',strtotime($v['task_time']) +24*3600);
			$psql = "select a.*,b.id,b.prize_name,b.prize_type,b.money,b.money_id from ll_activity_prize_log a left join ll_activity_double_egg_prize b on a.prize_id = b.id  ".
				"where a.uin = {$v['uin']} and a.task_id = 9 and a.add_time >= '{$start_time}' and a.add_time < '{$end_time}'";
			$prize_info = $db->query($psql);
			$v['prize_name'] = '';
			$v['prize_time'] = '';
			$v['money'] = 0;
			$v['money_id'] = '-';
			$v['is_used'] = '-';
			$v['order_money'] = '-';
			$v['use_time'] = '-';
			if ($prize_info) {
				//echo '<pre>';print_r($prize_info);die;
				$v['money'] = $prize_info[0]['money'];
				$v['prize_time'] = $prize_info[0]['add_time'];
				$v['prize_name'] = $prize_info[0]['prize_name'];
				if ($prize_info[0]['prize_type'] == '代金券') {
					$v['money_id'] = $prize_info[0]['money_id'];
					$all_coupon_info[] = $v;
				}
				else {
					$all_group_info[] = $v;
				}

				//print_r($v);die;
			}

		}
		//echo '<pre>';print_r($all_coupon_info);die;
		if ($all_coupon_info) {
			$vids = '';
			$uins = '';
			$vid_arr = [];
			$uin_arr = [];
			foreach($all_coupon_info as $v) {
				$uin_arr[] = $v['uin']; 
				$vid_arr[] = $v['money_id']; 
			}
			$uins = implode(',',$uin_arr);
			$vids = implode(',',$vid_arr);
			$coupon_list = getCouponInfo($vids,$uins);
			foreach ($coupon_list as &$c_v) {
				$c_v['is_used'] = '未使用';
				if (isset($c_v['order_money'])) {
					$c_v['is_used'] = '已使用';
				}	
				$res = $obj->getUserVipLevel($v['uin'],'','',151,102,0);
				$c_v['vip_level'] = 0;
				if ($res) {                                                                    
					$c_v['vip_level'] = $res['vip_level'];                                                   
				}                                                                              

			}
		}
		//echo '<pre>';print_r($all_group_info);die;
		//echo '<pre>';print_r($coupon_list);die;
		$last_data = array_merge($all_group_info,$coupon_list);
		//print_r($last_data);die;
		exportCSV($last_data,'砸金蛋数据.csv');
		exit();

	}

}


function getCouponInfo($vids,$uin){
	$db = new Db();	
	$db->use_db('llpay');
	$sql = "select id,uin,appid,vid as money_id,status,useTime as use_time,serialNumber,addtime as prize_time from pay_voucher_log where uin in ($uin) and vid in ($vids) and addtime >= '2018-12-13' and addtime <= '2019-01-02' ";
	//echo $sql;die;
	$coupon_info = $db->query($sql);
	if ($coupon_info) {
		foreach($coupon_info as $k=>$v) {
			$psql = "select money from pay_dev_log where serialNumber = {$v['serialNumber']} and appid = {$v['appid']}";
			$order_info = $db->query($psql);
			$coupon_info[$k]['order_money'] = '-';
			$coupon_info[$k]['use_time'] = '-';
			if ($order_info) {
				$coupon_info[$k]['order_money'] = $order_info['money'];	   	
			    $coupon_info[$k]['use_time'] = $v['use_time'];
			}
			$vsql = "select money  from pay_voucher where id = {$v['money_id']}";
			$money_info = $db->query($vsql);
			if ($money_info) {
				$coupon_info[$k]['prize_name'] = intval($money_info[0]['money']).'代金券';
			}
		}
	}
	return $coupon_info;
}

function exportCSV($data,$filename){
	$csv_data = "uin,VIP等级,奖品名称,代金券ID,获得时间,使用代金券的订单金额,代金券使用时间\n";
	if ($data) {
		foreach($data as $k=>$v) {
			$csv_data .= "{$v['uin']},{$v['vip_level']},{$v['prize_name']},{$v['money_id']},{$v['prize_time']},{$v['order_money']},{$v['use_time']}\r\n";
		}
	}
	$csv_data = (chr(0xEF).chr(0xBB).chr(0xBF)) . $csv_data;            //  excel中文识别
	header("Content-type:text/csv;charset=utf-8");
	header("Content-Disposition:attachment;filename=" . $filename);
	header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
	header("Expires:0");
	header("pragma:public");
	echo $csv_data;
}


