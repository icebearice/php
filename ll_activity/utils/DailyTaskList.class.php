<?php
/*************************************************************************
* File Name: DailyTaskList.class.php
* Author: lvchao.yan
* Created Time: 2018.12.21
* Desc: 每日任务列表 
*************************************************************************/
require_once dirname(dirname(__DIR__)) . "/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";

require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/DB.php";
//require_once SYSDIR_UTILS . "/REDIS.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
require_once SYSDIR_UTILS . "/XXRequestBase.php";

//define ("CACHE_TIME", 600);

class DailyTaskList{
    private static $__instance;
	private $__db;
	//private $__cache;
    
    private function __construct() {
		$this->__db = new Db();
		$this->__db->use_db("write");
		// $this->__cache = new myRedis();
		// $this->__cache->use_redis("read");
	}

	public static function getInstance() {
		if (!(self::$__instance instanceof self)) {
			self::$__instance = new self;
		}
		return self::$__instance;
    } 
	
	public function isUserLogined(){  //登陆态验证
		$uin = isset($_REQUEST['uin'])?$_REQUEST['uin']: 0;
		$login_key = isset($_REQUEST['login_Key'])? $_REQUEST['login_Key']:"";
		$uuid = isset($_REQUEST['uuid'])? $_REQUEST['uuid']:"";
		$productID = isset($_REQUEST['productID'])? $_REQUEST['productID'] : 136;
		$platform = isset($_REQUEST['platformType'])? $_REQUEST['platformType']:102;

		$auth = new LLUserAuthServer();
		if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key)) {
    		return 0;
		} else {
			return 1;
		}
	}

	public function showDailyTaskList(){  //获取每日任务列表
		$sql = "select task_id,task_name,task_times from ll_activity_daily_task_list where task_id between 1 and 6";
		return $this->__db->query($sql);
    }
    
    public function getUserCompletedTask($uin){  //获取用户已完成任务集
		$completed_task = array();
		$sql = "select task_id,task_times from ll_activity_user_complete_daily_task_info where uin = {$uin} and task_id between 1 and 6";
		$result = $this->__db->query($sql);
		return $result;
    }
}