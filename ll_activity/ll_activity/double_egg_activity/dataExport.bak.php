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

$action = isset($_GET['action']) ? $_GET['action'] : 'golden_ball';
$db = new Db();
$db->use_db('read');
//print_r($db);die;

if ($action == 'break_egg') {
	$sql = "select id,uin,task_id,task_time from ll_activity_user_task_list where task_id = 9 and task_time >='2018-12-24' and task_time <= '2019-01-02' and uin > 0 order by id desc";
	$break_info = $db->query($sql);
	//print_r($break_info);die; 
	if ($break_info) {
		$all_coupon_info = $all_group_info = [];
		$obj = new LLGrouthServer();
		foreach($break_info as &$v) {
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
					$res = $obj->getUserVipLevel($v['uin'],'','',151,102,0);
					$v['vip_level'] = 0;
					if ($res) {                                                                    
						$v['vip_level'] = $res['vip_level'];                                                   
					}                                                                              
					$all_group_info[] = $v;
				}

				//print_r($v);die;
			}

		}
		//echo '<pre>';print_r($all_group_info);die;
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
			$uins = implode(',',array_unique($uin_arr));
			$vids = implode(',',array_unique($vid_arr));
			$coupon_list = getCouponInfo($vids,$uins);
			foreach ($coupon_list as &$c_v) {
				$res = $obj->getUserVipLevel($c_v['uin'],'','',151,102,0);
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

if ($action == 'golden_ball') {
	$start_time = '2018-12-24';
	$end_time = '2019-01-03';
	$sql = "select * from ll_activity_user_task_list where task_id in (1,2,3) and task_time >= '{$start_time}' and task_time < '{$end_time}' order by id desc";
	$task_list = $db->query($sql);
	if ($task_list) {
		$all_group_info = $all_coupon_info = $no_completed =  $all_uin = $all_vid = $coupon_list = [];
		foreach($task_list as $v) {
                        $v['task_name'] = getTaskName($v['task_id']);
                        $v['order_money'] = '-';
                        $v['use_time'] = '-';
                        $v['prize_time'] = '-';
			if ($v['status'] == 3 && $v['completed_num'] == 1) {
				$pstart_time = $v['task_time'];
				$pend_time= date('Y-m-d',strtotime($v['task_time'])+24*3600);

				$psql = "select a.*,b.id,b.prize_name,b.prize_type,b.money,b.money_id from ll_activity_prize_log a left join ll_activity_double_egg_prize b "
					."on a.prize_id = b.id  where a.uin = {$v['uin']} and a.task_id = {$v['task_id']} and a.add_time >= '{$pstart_time}' and a.add_time < '{$pend_time}'";
                                //echo $psql;die;
				$prize_info = $db->query($psql);

				if ($prize_info) {
					if($prize_info[0]['prize_type'] == '成长值') {
						$all_group_info[] = $v;
					}
					else if ($prize_info[0]['prize_type'] == '代金券') {
						$all_uin[] = $v['uin'];
						$all_vid[] = $prize_info[0]['money_id'];
					}
				}
			}
			else {
				$no_completed[] = $v;
			}

		}
		if ($all_uin) {
			$uins = implode(',',array_unique($all_uin));
			$vids = implode(',',array_unique($all_vid));
			$coupon_list = getCouponInfo($vids,$uins);
			if ($coupon_list) {
				foreach($coupon_list as &$v) {
					$v['status'] = 3;
					if ($v['money_id'] == 1412) {
						$v['task_name'] = '实销金额满10元';
					}
					else if ($v['money_id'] == 1410) {
						$v['task_name'] = '偷取一名好友金蛋';
					}
                                        $task_time = date('Y-m-d',strtotime($v['prize_time']));

                                        $time_info = $db->query("select * from ll_activity_user_task_list where uin = {$v['uin']} and task_time = '{$task_time}'");
                                        $v['first_add_time'] = $time_info[0]['first_add_time'];
                                        $v['update_time'] = $time_info[0]['update_time'];
                                        $v['task_time'] = $time_info[0]['task_time'];
				}
			}
		}

	}
         
	//print_r($task_list);die;    
	//print_r($no_completed);die;    
	//print_r($task_list);    
	//print_r($all_group_info);die;    
        $last_data = array_merge($all_group_info,$coupon_list);
        $last_data = array_merge($last_data,$no_completed);
        //print_r($last_data);die;
	exportCSV1($last_data,'摘金球数据.csv');
	exit();
}


function getTaskName ($task_id) {
        switch($task_id) {
              case 1:
                $task_name = '参与每日话题讨论';
                break;
              case 2:
                $task_name = '实际消费金额满10元';
                break;
              case 3:
                $task_name = '偷取1名好友金蛋';
                break;
               default :
                $task_name = '';
                
        }
        return $task_name;
}


function getCouponInfo($vids,$uin){
	$db = new Db();	
	$db->use_db('llpay');
	$sql = "select id,uin,appid,vid as money_id,status,useTime as use_time,serialNumber,addtime as prize_time from pay_voucher_log where uin in ($uin) and vid in ($vids) and addtime >= '2018-12-24' and addtime <= '2019-01-02' and grant_admin = '' order by id desc";
	//echo $sql;die;
	$coupon_info = $db->query($sql);
	if ($coupon_info) {
		foreach($coupon_info as $k=>$v) {
			$coupon_info[$k]['order_money'] = '-';
			$coupon_info[$k]['use_time'] = '-';
			$coupon_info[$k]['is_used'] = '未使用';
			if ($v['serialNumber']) { 
				$psql = "select money from pay_dev_log where serialNumber = '{$v['serialNumber']}' and app_id = {$v['appid']}";
				$order_info = $db->query($psql);
				$coupon_info[$k]['order_money'] = '-';
				$coupon_info[$k]['use_time'] = '-';
				if ($order_info) {
					$coupon_info[$k]['order_money'] = $order_info[0]['money'];	   	
					$coupon_info[$k]['use_time'] = $v['use_time'];
					$coupon_info[$k]['is_used'] = '已使用';
				}
			}
			$vsql = "select money  from pay_voucher where id = {$v['money_id']}";
			$money_info = $db->query($vsql);
			if ($money_info) {
				$coupon_info[$k]['prize_name'] = intval($money_info[0]['money']).'元代金券';
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

function exportCSV1($data,$filename){
	$csv_data = "uin,任务名称,是否完成任务,领取时间,完成时间,使用代金券的订单金额,代金券领取时间,代金券使用时间\n";
	if ($data) {
		foreach($data as $k=>$v) {
                         $v['status_name'] = '未完成';
                         if ($v['status'] == 3) {
                             $v['status_name'] = '已完成';
                         }
			$csv_data .= "{$v['uin']},{$v['task_name']},{$v['status_name']},{$v['update_time']},{$v['first_add_time']},".
                                      "{$v['order_money']},{$v['prize_time']},{$v['use_time']}\r\n";
		}
	}
        //echo $csv_data;die;
	$csv_data = (chr(0xEF).chr(0xBB).chr(0xBF)) . $csv_data;            //  excel中文识别
	header("Content-type:text/csv;charset=utf-8");
	header("Content-Disposition:attachment;filename=" . $filename);
	header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
	header("Expires:0");
	header("pragma:public");
	echo $csv_data;
}
