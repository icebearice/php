<?php
require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";

require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/error.class.php";
require_once SYSDIR_UTILS."/userInfoServer.class.php";
require_once SYSDIR_UTILS."/voucherServer.class.php";
require_once SYSDIR_UTILS."/LLActivityBaseData.php";
require_once SYSDIR_UTILS . "/logger.php";


class LLActivityMyReward{
	private $__db;

	public function __construct(){
		$this->__db=new Db();
		$this->__db->use_db("write");
	}

	public function getMyReward($uin,$start=0,$count=10){
		$result = array();
		$info = array();

		$sql = "select a.id,a.uin,a.prize_id,b.prize_name,b.prize_type,b.money,b.money_id from ll_activity_prize_log a inner join ll_activity_double_egg_prize b on a.prize_id = b.id where a.uin = {$uin} and ".
			" a.add_time >'".Double_Activity_Start_Time."' and a.add_time < '".Double_Activity_End_Time."' and a.status = 1 and a.money_status = 1 order by a.id desc ";
		file_put_contents('/tmp/activity.log',$sql,FILE_APPEND);

		$res = $this->__db->query($sql);
		if($res === FALSE){
			return $res;
		}
		foreach($res as &$v){
			$v['min_order_amount'] = 0;
			$v['start_time'] = '';
			$v['expire_time'] = ''; 
			if ($v['prize_type'] == '代金券') {
				$obj = new LLVoucherServer();
				$coupon_info = $obj->getVoucherInfo($v['money_id']);
				if ($coupon_info) {
					$v['money'] = $coupon_info['money'];
					$v['min_order_amount'] = $coupon_info['min_order_amount'];
					$v['start_time'] = date('Y-m-d',$coupon_info['start_time']);
					$v['expire_time'] = date('Y-m-d',$coupon_info['expire_time']);
				}

			}   
		}
		return $res;
	}



	/*public function getPrizeInfo($limit_num){
		$result = array();
		$info=array();
		$sql = "select b.id,uin,prize_name from ll_activity_prize a inner join ll_activity_prize_log b on a.id=b.prize_id limit {$limit_num}";
		$res = $this->__db->query($sql);
		if($res === FALSE) {
			return array('code'=> ErrorCode::DataBase_Not_OK,'result'=>null);
		}
		$userHandler = new LLUserInfoServer();
		foreach($res as $v){
			$info['id']= $v['id'];
			$info['reward_info']=$v['prize_name'];
			$info['user_name']=$userHandler->getUserNameByUin($v['uin']);
			$result[]=$info;
		}
		return array('code'=>ErrorCode::OK,'result'=>$result);
	}
	 */

}	
