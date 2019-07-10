<?php
/*************************************************************************
* File Name: DailyTaskList.class.php
* Author: lvchao.yan
* Created Time: 2018.12.21
* Desc: 每日任务列表 
*************************************************************************/
require_once dirname(dirname(__DIR__)) . "/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";

require_once SYSDIR_UTILS . "/DB.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/userInfoServer.class.php";

class DailyTaskList{
    private static $__instance;
	private $__db;
    
    private function __construct() {
		$this->__db = new Db();
		$this->__db->use_db("write");
	}

	public static function getInstance() {
		if (!(self::$__instance instanceof self)) {
			self::$__instance = new self;
		}
		return self::$__instance;
    } 
	
	public function showDailyTaskList(){  //获取每日任务列表
		$sql = "select task_id,task_name,task_times from ll_activity_daily_task_list where task_id between 1 and 6";
		return $this->__db->query($sql);
    }
    
    public function userHasCompletedTheTask($uin,$task_id,$task_name){  //将已完成每日任务信息入库并GO++
		/* 贡献值PK和邀请好友接力在完成任务脚本里入库，其他任务从此入库（写每日任务表和GO次数）*/
		$today = strtotime(date("Y-m-d"));  //今日零时时间戳
        $sql = "select * from ll_activity_user_complete_daily_task_info where uin = {$uin} and task_id = {$task_id}";
		if ($task_id != 3) {  //除了绑定手机，其他任务都是每日任务，需日清
			$sql .=" and unix_timestamp(compelete_time) >= {$today}";
		}
        $res = $this->__db->query($sql);
        if (count($res) == 0) {  //只入库一次
			$task_day = date("Y-m-d");
			$sql = "insert into ll_activity_user_complete_daily_task_info (uin,task_id,task_name,task_times,task_day)";
			$sql.= " values({$uin},{$task_id},'{$task_name}',1,'{$task_day}')";
            $this->__db->query($sql);
			$row = $this->__db->db->affected_rows;
			if ($row <= 0) return -1;
            $sql = "update ll_activity_user_list_of_activity set go_times = go_times + 1 where uin = {$uin}";
            $this->__db->query($sql);
			$row = $this->__db->db->affected_rows;
			if ($row <= 0) return -1;
        }
    }

    public function getUserCompletedTask($uin){  //获取用户已完成任务集
		$completed_task = array();
		$today = date("Y-m-d");  //今日年月日

		if (1) {  //用户已登录
			$task_id = 1;
			$task_name = '每日登陆活动';	
			if ($this->userHasCompletedTheTask($uin,$task_id,$task_name) == -1) return -1;
		}

        if (UserInfo::getInstance()->isUserPhone($uin)) {  //查询用户是否绑定手机任务
            $task_id = 3;  
            $task_name = '绑定手机号';
            if ($this->userHasCompletedTheTask($uin,$task_id,$task_name) == -1) return -1;
			$completed_task[] = array('task_id' => $task_id,'task_times' => 1);
        }

		$sql = "select task_id,task_times from ll_activity_user_complete_daily_task_info where uin = {$uin} and task_id != 3 and task_day = '{$today}'";
		$res = $this->__db->query($sql);
		if ($res) {
			foreach($res as $task){
				$completed_task[] = array('task_id' => $task['task_id'],'task_times' => $task['task_times']);
			}
		}
        
		return $completed_task;
    }
}
