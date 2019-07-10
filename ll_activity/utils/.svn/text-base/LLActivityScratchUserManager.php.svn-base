<?php
require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";

require_once SYSDIR_UTILS."/logger.php";
require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/userInfoServer.class.php";
require_once SYSDIR_UTILS."/error.class.php";

class LLActivityScratchUserManager{
	private $__db;
	public function __construct(){
		$this->__db = new Db(); 
		$this->__db->use_db("write");
	}

    public function getUserScratchTimes($uin,$range=1){
	  $info=array();
	  $sql="select scratch_times, prize_total_times from ll_activity_user_scratch where uin={$uin}";
	  $res=$this->__db->query($sql);
	  if(false==$res){
	     return array('code'=>ErrorCode::DataBase_Not_OK,'result'=>null);
	  }
	  $result['scratch_times'] = isset($res[0]['scratch_times'])?$res[0]['scratch_times'] : 0;
	  $result['prize_total_times'] = isset($res[0]['prize_total_times']) ? $res[0]['prize_total_times'] :0;
	  $date = date("Y-m-d");
	  $sql="select num as cost from ll_activity_user_cost where uin={$uin} and task_time = '{$date}'";
	  $res = $this->__db->query($sql);
	  $result['cost'] = isset($res[0]['cost']) ? $res[0]['cost'] / 100 :0;
	  $sql = "select b.prize_name, b.money_id, a.prize_status,a.add_time from ll_activity_user_scratch_log a left join ll_activity_scratch_prize b on a.prize_id = b.id where a.uin = {$uin} and b.scratch_range={$range} order by a.add_time desc";
	  $data = $this->__db->query($sql);
	  $result['prize_list'] = $data;
	  return $result;
      
	}
}
