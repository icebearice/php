<?php
require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";

require_once SYSDIR_UTILS."/logger.php";
require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/userInfoServer.class.php";
require_once SYSDIR_UTILS."/error.class.php";

class LLActivityMessageManager{
	private $__db;
	private $__sortedId;
	private $__topUin;
	public function __construct(){
		$this->__db = new Db(); 
		$this->__db->use_db("write");
		$this->__sortedId=0;
		$this->__topUin=array();
	}

	public function __destruct(){

	}
	public function addMessage($message, $uin,$addCredit){
		$this->__db->query("start transaction");
		$msgs = $this->getMessageByUin($uin);
        if (!empty($msgs)) {
	        return ErrorCode::User_Message_Had_Exist;
		}									                

		$addTime = date("Y-m-d H:i:s");
		$data = array(
			'uin'=>$uin,
			'message'=>$message,
			'add_time'=>$addTime,
			'message_type'=>0,
			'sorted'=>0,
			'credit'=>0,
			'status'=>0
		);
		$result = $this->__db->insert('ll_activity_message',$data);
		if($result ===FALSE){
			$this->__db->query("rollback");
			return ErrorCode::DataBase_Not_OK;
		}
		$result = $this->addCredit($uin,$addCredit,0,'开学寄语');
		if($result === FALSE){
			$this->__db->query("rollback");
			return ErrorCode::DataBase_Not_OK;
		}
		$this->__db->query("commit");
		return ErrorCode::OK;
	}

	private function addCredit($uin,$credit,$op,$comment){
		$addTime = date("Y-m-d H:i:s");
		$data = array(
			'uin'=>$uin,
			'credit'=>$credit,
			'op'=>$op,
			'task_id'=>0,
			'comment'=>$comment,
			'add_time'=>$addTime
		);
		$result = $this->__db->insert('ll_activity_credit_log',$data);
		if($result === FALSE){
			return FALSE;
		}

		$sql = "insert into ll_activity_credit (uin,credit,today_credit,update_time) values({$uin},{$credit},{$credit},'{$addTime}') ON DUPLICATE KEY "
		       ."update  credit = credit + {$credit} , today_credit = today_credit+{$credit} , update_time ='{$addTime}';";
		$result = $this->__db->query($sql);
		if($result === FALSE){
			return  FALSE;
		}
		return TRUE;
	}




	public function getMessageInfo($uin,$top_num,$other_num,$loginFlag){
		$word_done = 2; //用户是否已经发过寄语
		$result = array();
		$messageTop = $this->getMessageByTop($uin,$top_num);
		if(!isset($messageTop)){
			return array('code'=>ErrorCode::DataBase_Not_OK,'word_done'=>null,'result'=>null);
		}
		$result = $messageTop['result'];
		if($loginFlag===TRUE && $messageTop['flag']===TRUE){
			    $word_done=1;
		}

		if($loginFlag===TRUE && $messageTop['flag']===TRUE || $loginFlag===FALSE ){
			$other_num += 1;
		} else {
			$messageUin = $this->getMessageByUin($uin);
			if(!empty($messageUin)){
				$word_done = 1;
				$result[]=$messageUin;	
			} else {
				$other_num += 1;
			}
		}

		//没有置顶加精三条的额外部分
		$messageOther = $this->getMessageByOther($uin,$other_num);
		if(!isset($messageOther)){
			return array('code'=>ErrorCode::DataBase_Not_OK,'word_done'=>null,'result'=>null);
		}

		foreach($messageOther as $v){
			$result[]=$v;
		}
		return array('code'=>ErrorCode::OK,'word_done'=>$word_done,'result'=>$result);	
	}

	private function getMessageByUin($uin){
		$info=array();
		$sql = "select id,message_type,uin,credit,message,add_time from ll_activity_message where uin = {$uin} limit 1;";
		$message_list = $this->__db->query($sql);
		if($message_list === FALSE){
			return null;
		}
		$userHandler = new LLUserInfoServer();
    	foreach($message_list as $v){
			$userInfo = $userHandler->getUserInfoByUin($v['uin']);
		//	var_dump($userInfo);
			if(!isset($userInfo)){
				continue;
			}
			$info['id'] = $v['id'];
			$user_name = $userInfo->getBase_data()->getUnickname();
			$info['user_name'] = !empty($user_name)?$user_name:"一个好的66用户";
			$info['user_icon'] = $userInfo->getExt_data()->getUico();
			$info['word'] = $v['message'];
			$info['credit']=$v['credit'];
			$info['time']=$v['add_time'];
			$info['type']=$v['message_type'];
		}
		//var_dump($info);
		return $info;
	}

		

	private function getMessageByTop($uin,$limit_num){
		$flag = FALSE;
		$result = array();
		$info = array();
		$sql = "select id,message_type, uin,credit,message,add_time from ll_activity_message where message_type = 1 and sorted!=0 order by sorted limit {$limit_num};";
		$message_list = $this->__db->query($sql);
		if($message_list === FALSE){
			return null;
		}
		$userHandler = new LLUserInfoServer();
		foreach($message_list as $v){
			$this->__topUin[]=$v['uin'];
			if($uin == $v['uin']){
				$flag = TRUE;
			}
			$userInfo = $userHandler->getUserInfoByUin($v['uin']);
			if(!isset($userInfo)){
				continue;	
			}
			$info['id'] = $v['id'];
			$user_name = $userInfo->getBase_data()->getUnickname();
			$info['user_name'] = !empty($user_name)?$user_name:"一个好的66用户";
			$info['user_icon'] = $userInfo->getExt_data()->getUico();
			$info['word'] = $v['message'];
			$info['credit']=$v['credit'];
			$info['time']=$v['add_time'];
			$info['type']=$v['message_type'];
			$result[]=$info;
		}
		return array(
			'result'=>$result,
			'flag'=>$flag
		);
	}		

	private function getMessageByOther($uin,$limit_num){
		$result = array();
		$info=array();
		$uinStrTmp =$this->__topUin;
		$uinStrTmp[] = $uin;
		$uinStr = implode(',',$uinStrTmp);
		$sql="select id,message_type, uin,credit, message,add_time from ll_activity_message where uin not in ({$uinStr}) order by add_time desc  limit {$limit_num};";
		$message_list = $this->__db->query($sql);
		//var_dump($message_list);
		if($message_list === FALSE){
			return null;
		}
		//var_dump($message_list);
		$userHandler = new LLUserInfoServer();
		foreach($message_list as $v){
			$userInfo = $userHandler->getUserInfoByUin($v['uin']);
			if(!isset($userInfo)){
				continue;
			}
			$info['id'] = $v['id'];
			$user_name = $userInfo->getBase_data()->getUnickname();
			$info['user_name'] = !empty($user_name)?$user_name:"一个好的66用户";
			$info['user_icon'] = $userInfo->getExt_data()->getUico();
			$info['time']=$v['add_time'];
			$info['word']=$v['message'];
			$info['credit']=$v['credit'];
			$info['type'] = $v['message_type'];
			$result[]=$info;
			//var_dump($v['uin']);
		}
		return $result;
	}

	private function getSortedId(){
		++$this->__sortedId;
		return $this->__sortedId;
	}
}

