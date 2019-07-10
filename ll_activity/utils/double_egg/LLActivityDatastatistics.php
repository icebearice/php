<?php
require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once SYSDIR_UTILS."/logger.php";
require_once SYSDIR_UTILS."/DB.php";

class LLActivityDatastatistics{
	private $__db;
	public function __construct() {
		$this->__db = new Db();
		$this->__db->use_db("read");
	}

	public function getStatData(){
		$today = date('Y-m-d');
		$today_end = date('Y-m-d',time()+24*3600);
		//砸金球
		$break_egg_today_join_num = $this->getJoinNum(1,$today,$today);
		$break_egg_join_num = $this->getJoinNum(1,Double_Activity_Start_Time,Double_Activity_End_Time);
		list($vf_coupon_storge,$vs_coupon_storge) = $this->getStorgeNum();//库存
		$time = strtotime($today);
		list($coupon_today_num,$coupon_today_money) = $this->getPrizeInfo(1,'代金券',$today,$today_end);
		list($grouth_today_num,$grouth_today_money) = $this->getPrizeInfo(1,'成长值',$today,$today_end);
		list($coupon_all_num,$coupon_all_money) = $this->getPrizeInfo(1,'代金券',Double_Activity_Start_Time,Double_Activity_End_Time);
		list($grouth_all_num,$grouth_all_money) = $this->getPrizeInfo(1,'成长值',strtotime(Double_Activity_Start_Time),Double_Activity_End_Time);

		//摘金球
		$golden_today_join_uin = $this->getJoinNum(2,$today,$today,2);
		$golden_join_uin = $this->getJoinNum(2,Double_Activity_Start_Time,Double_Activity_End_Time,2);
		$golden_today_join_num = $this->getJoinNum(2,$today,$today);
		$golden_join_num = $this->getJoinNum(2,Double_Activity_Start_Time,Double_Activity_End_Time);

		//实消任务
		list($today_cost_num,$today_cost_get_num) = $this->getTaskInfo(2,$today,$today);	
		list($all_cost_num,$all_cost_get_num) = $this->getTaskInfo(2,Double_Activity_Start_Time,Double_Activity_End_Time);	

		//分享任务
		list($today_share_num,$today_share_get_num) = $this->getTaskInfo(3,$today,$today);	
		list($all_share_num,$all_share_get_num) = $this->getTaskInfo(3,Double_Activity_Start_Time,Double_Activity_End_Time);	

		//话题讨论
		list($today_chat_num,$today_chat_get_num) = $this->getTaskInfo(1,$today,$today);	
		list($all_chat_num,$all_chat_get_num) = $this->getTaskInfo(1,Double_Activity_Start_Time,Double_Activity_End_Time);	
		list($number_one_cost,$total) = $this->getCostInfo();
		$data['cost']['number_one_cost'] = $number_one_cost;
		$data['cost']['total'] = $total;

		$data['break_egg']['break_egg_today_join_num'] = $break_egg_today_join_num;
		$data['break_egg']['break_egg_join_num'] = $break_egg_join_num;
		$data['break_egg']['vf_coupon_storge'] = $vf_coupon_storge;
		$data['break_egg']['vs_coupon_storge'] = $vs_coupon_storge;
		$data['break_egg']['coupon_today_num'] = $coupon_today_num;
		$data['break_egg']['coupon_today_money'] = $coupon_today_money;
		$data['break_egg']['grouth_today_num'] = $grouth_today_num;
		$data['break_egg']['grouth_today_money'] = $grouth_today_money;
		$data['break_egg']['coupon_all_num'] = $coupon_all_num;
		$data['break_egg']['coupon_all_money'] = $coupon_all_money;
		$data['break_egg']['grouth_all_num'] = $grouth_all_num;
		$data['break_egg']['grouth_all_money'] = $grouth_all_money;
		$data['break_egg']['total_num'] = $coupon_all_num+$grouth_all_num;
		$data['break_egg']['total_money'] = $coupon_all_money+$grouth_all_money;


		$data['get_golden']['golden_today_join_num'] = $golden_today_join_num;
		$data['get_golden']['golden_join_num'] = $golden_join_num;
		$data['get_golden']['golden_today_join_uin'] = $golden_today_join_uin;
		$data['get_golden']['golden_join_uin'] = $golden_join_uin;

		$data['get_golden']['today_cost_num'] = $today_cost_num;
		$data['get_golden']['today_cost_get_num'] = $today_cost_get_num;
		//$data['get_golden']['all_get_num'] = $all_get_num;
		$data['get_golden']['all_cost_num'] = $all_cost_num;//实销任务完成人数
		$data['get_golden']['all_cost_get_num'] = $all_cost_get_num;//实销任务完成人数
		$data['get_golden']['today_share_num'] = $today_share_num;
		$data['get_golden']['today_share_get_num'] = $today_share_get_num;
		$data['get_golden']['all_share_num'] = $all_share_num;
		$data['get_golden']['all_share_get_num'] = $all_share_get_num;

		$data['get_golden']['today_chat_num'] = $today_chat_num;
		$data['get_golden']['today_chat_get_num'] = $today_chat_get_num;
		$data['get_golden']['all_chat_get_num'] = $all_chat_get_num;
		$data['get_golden']['all_chat_num'] = $all_chat_num;

		$data['cost']['total'] = $total;
		$data['cost']['number_one_cost'] = $number_one_cost;
		return $data;

	}



	public function getJoinNum($task_id = 1,$start_time,$end_time,$type = 1){
		$t = date('Y-m-d');
		$user_task = '';
		if($task_id == 1) {//砸金蛋
			$user_task = 9;
		}
		else if ($task_id == 2) { //摘金球
			$user_task = '1,2,3';
		}

		$where = "task_id in ({$user_task})";

		if($start_time == $end_time) {
			$where .= " and task_time ='{$start_time}'";
		}
		else {
			$where .= " and task_time >='{$start_time}' and task_time < '{$end_time}'";
		}

		$field = 'count(distinct(uin)) as num';
		if($type == 2) {
			$field = 'count(uin) as num';
		}

		$sql = "select {$field} from ll_activity_user_task_list where {$where}";
		//echo $sql."\r\n";
		$num_info = $this->__db->query($sql);
		$num = 0;
		if($num_info) {
			$num = $num_info[0]['num'];
		}
		return $num;

	}



	public function getStorgeNum(){
		$sql = "select * from ll_activity_double_egg_prize where atype = 1";
		$prize_info = $this->__db->query($sql);
		$vf_completed_num = $vs_completed_num = 0;
		$vf_limited_num = $vs_limited_num = 0;
		$vf_storge = $vs_storge_num = 0;
		if($prize_info) {
			foreach($prize_info as $v){
				if($v['vip_level'] == 0){
					$vf_completed_num += $v['completed_num'];
					$vf_limited_num += $v['limited_num'];
				}
				else if ($v['vip_level'] == 4) {
					$vs_completed_num += $v['completed_num'];
					$vs_limited_num += $v['limited_num'];
				}
			}
			$vf_storge = $vf_limited_num - $vf_completed_num;
			$vs_storge = $vs_limited_num - $vs_completed_num;

		}
		return [$vf_storge,$vs_storge];
	}


	public function getPrizeInfo($task_id,$prize_type='代金券',$start_time,$end_time){
		$user_task = '';
		if($task_id == 1) {
			$user_task = 9; 
		}
		else if ($task_id == 2) {
			$user_task = '1,2,3'; 
		}
		$today_num = 0;
		$today_money = 0;
		$ewhere = '';

		$sql = "select count(a.id) as num,sum(money) as total  from ll_activity_prize_log a inner join ll_activity_double_egg_prize b on a.prize_id = b.id  ".
			" where a.task_id in ({$user_task}) and b.prize_type = '{$prize_type}' and a.add_time >='{$start_time}' and a.add_time <'{$end_time}'";
		$today_num = $today_money = 0;
		$num_info = $this->__db->query($sql);
		if($num_info){
			if ($num_info[0]['num']) {
				$today_num = $num_info[0]['num'];
				$today_money = $num_info[0]['total'];
			}
		}
		return [$today_num,$today_money];
	}


	public function getTaskInfo($tid,$start_time,$end_time) {
		$where = '';
		if($start_time == $end_time) {
			$where = "task_time = '{$start_time}'";
		}
		else {
			$where = "task_time < {$end_time} and task_time >={$start_time}"; 
		}
		$sql = "select * from ll_activity_user_task_list where task_id = {$tid} and {$where}";
		//echo $sql;die;
		$cost_info = $this->__db->query($sql);
		$completed_num = $get_num = 0;
		if($cost_info){
			foreach($cost_info as $v){
				if($v['completed_num'] == 1 && $v['status'] == 2) {
					$completed_num++;
				}
				else if ($v['status'] == 3) {
					$get_num++;
				}
			}

		}
		return [$completed_num,$get_num];
	}


	public function getCostInfo(){
		$t = date('Y-m-d');
		$sql = "select num from ll_activity_user_cost where task_time = '{$t}' order by num desc limit 1";
		$cost_info = $this->__db->query($sql);
		$fcost_num = 0;
		$total = 0;
		if($cost_info){
			$fcost_num = $cost_info[0]['num']/100;
		}
		$csql = "select sum(num) as total from ll_activity_user_cost where task_time = '{$t}'";
		$cost_list_info = $this->__db->query($csql);
		if($cost_list_info) {
			if ($cost_list_info[0]['total']) {
				$total = $cost_list_info[0]['total']/100;
			}
		}

		return [$fcost_num,$total];
	}


}

