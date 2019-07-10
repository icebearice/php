?php
require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";

require_once SYSDIR_UTILS."/logger.php";
require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/userInfoServer.class.php";
require_once SYSDIR_UTILS."/error.class.php";

class LLActivityCreditManager{
	private $__db;
	public function __construct(){
		$this->__db = new Db(); 
		$this->__db->use_db("read");
	}

	public function getCreditInfo($uin,$headIndex,$limit_num){
		$result = array();
		$info = array();
		$sql = "select id,credit,comment,add_time from ll_activity_credit_log where uin = {$uin} order by add_time desc limit {$headIndex}, {$limit_num};";
		$res = $this->__db->query($sql);
		if($res === FALSE){
			return array('code'=>ErrorCode::DataBase_Not_OK,'result'=>null);
		}
		foreach($res as $v){
			$info['id']=$v['id'];
			$info['credit']=$v['credit'];
			$info['comment']=$v['comment'];
			$info['time']=$v['add_time'];
			$result[]=$info;
		}
		return array('code'=>ErrorCode::OK,'result'=>$result);
	}

	public function getCredit($uin){
		$sql = "select today_credit,credit from ll_activity_credit where uin = {$uin};";
		$res = $this->__db->query($sql);
		if($res === FALSE){
			return array('code'=>ErrorCode::DataBase_Not_OK,'result'=>null);
		}
		foreach($res as $v){
			return array('code'=>ErrorCode::OK,'result'=>$v);
		}
		return array('code'=>ErrorCode::OK,'result'=>array('credit'=>0,'today_credit'=>0));
	}

}



