<?php
/*************************************************************************
 * File Name: DailyTask.class.php
 * Author: lvchao.yan
 * Created Time: 2019.02.21
 * Desc: 每日任务 
 *************************************************************************/
require_once dirname(dirname(__DIR__)) . "/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";

require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/DB.php";
require_once SYSDIR_UTILS . "/REDIS.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
require_once SYSDIR_UTILS . "/XXRequestBase.php";

define("SHARE_APP_TASK_ID", 1);
define("DAILY_QUESTION_TASK_ID", 2);
define("DAILY_TOPIC_TASK_ID",4);

class DailyTask{
	private static $__instance;
	private $__db;
	private $__cache;
	private $task_day;
	private $today_timestamp;

	public function __construct() {
		$this->__db = new Db();
		$this->__cache = new myRedis();
		$this->__cache->use_redis("read");
		$this->task_day = date("Y-m-d");
		$this->today_timestamp = strtotime($this->task_day);
	}

	public function __destruct() {
		$this->__db = null;
		$this->__cache = null;
	}

	public static function getInstance() {
		if (!(self::$__instance instanceof self)) {
			self::$__instance = new self;
		}
		return self::$__instance;
	} 

	public function registerDailyTask($uin, $task_id){  //入库每日任务并增加相应水源
		$this->__db->use_db("write");
		$sql = "select task_name, water from arbor_day_task_list where task_id = {$task_id} limit 1";
		$res = $this->__db->query($sql);
		$task_name = $res[0]["task_name"];
		$water = $res[0]["water"];

		$this->__db->query("start transaction");
		$sql = "insert into arbor_day_user_completed_task_list (uin, task_id, task_name, task_day) values({$uin}, {$task_id}, '{$task_name}', '{$this->task_day}')";
		$this->__db->query($sql);
		$error = $this->__db->db->error;
		$rows = $this->__db->db->affected_rows;
		if ($rows <= 0) {
			$this->__db->query("rollback");
			@file_put_contents('/tmp/arbor_day_activity_error.log', date('Y-m-d H:i:s')."---DailyTask.class.php.1---".$error."\n\n",  FILE_APPEND);
			return -1;
		}
		$water_sour = "water_".$water;
		$sql = "update arbor_day_user_list set {$water_sour} = {$water_sour} + 1 where uin = {$uin}";
		$this->__db->query($sql);
		$error = $this->__db->db->error;
		$rows = $this->__db->db->affected_rows;
		if ($rows <= 0) {
			$this->__db->query("rollback");
			@file_put_contents('/tmp/arbor_day_activity_error.log', date('Y-m-d H:i:s')."---DailyTask.class.php.2---".$error."\n\n",  FILE_APPEND);
			return -1;
		}
		if (!$this->__db->query("commit")) {
			$error = $this->__db->db->error;
			$this->__db->query("rollback");
			@file_put_contents('/tmp/arbor_day_activity_error.log', date('Y-m-d H:i:s')."---DailyTask.class.php.3---".$error."\n\n",  FILE_APPEND);
			return -1;
		}
		return 1;
	}

	public function shareApp($uin){  //任务1：分享66手游APP
		$this->__db->use_db("write");
        $sql = "select 1 from arbor_day_user_completed_task_list where uin = {$uin} and task_id = 2 and task_day = '{$this->task_day}'";
        $res = $this->__db->query($sql);
        if ($res) {  //若用户已完成同水源中的另一个任务：任务2
            return 0;    
        }
		$task_id = SHARE_APP_TASK_ID;
		$sql = "select 1 from arbor_day_user_completed_task_list where uin = {$uin} and task_id = {$task_id} and task_day = '{$this->task_day}'";
		$res = $this->__db->query($sql);
		if ($res) {  //今日该任务已完成
			return 0;
		}
		$this->__cache->redis->sadd("uin_set", $uin);
		return 1; 
	}

	public function dailyQuestion($uin){  //任务2：参与每日答题且答对
		$this->__db->use_db("write");
        $sql = "select 1 from arbor_day_user_completed_task_list where uin = {$uin} and task_id = 1 and task_day = '{$this->task_day}'";
        $res = $this->__db->query($sql);
        if ($res) {  //若用户已完成同水源中的另一个任务：任务1
            return 0;    
        }
		$this->__db->use_db("llbackend");
		$sql = "select 1 from ll_user_rand_question_list where uin = {$uin} and get_date = '{$this->task_day}' and status = 3 limit 1";
		$res = $this->__db->query($sql);
		if ($res) {
			return $this->registerDailyTask($uin, DAILY_QUESTION_TASK_ID);
		}
		return 0;
	}

	public function dailyTopic($uin){  //任务4：当日话题单条评论得5个赞
		$this->__db->use_db("write");
        $sql = "select 1 from arbor_day_user_completed_task_list where uin = {$uin} and task_id = 3 and task_day = '{$this->task_day}'";
        $res = $this->__db->query($sql);
        if ($res) {  //若用户已完成同水源中的另一个任务：任务3
            return 0;    
        }
		$this->__db->use_db("lldaliytopic");
		$now_time = time();
		$sql = "select max(push_time) push_time from ll_topic_list where push_time <= {$now_time}";  //查询最新生效话题
		$res = $this->__db->query($sql);
		$push_time = $res[0]["push_time"];
		$sql = "select id from ll_topic_list where push_time = {$push_time}";
		$res = $this->__db->query($sql);
		$id = $res[0]["id"];
		$sql = "select like_times from ll_reply_list where uid = {$uin} and add_time >= {$this->today_timestamp} and topic_id = {$id}";	
		$res = $this->__db->query($sql);
		foreach ($res as $row) {
			if ($row["like_times"] >= 5) {
				return $this->registerDailyTask($uin, DAILY_TOPIC_TASK_ID);
			}
		}
		return 0;
	}

	public function getDailyTaskList(){  //拉取每日任务列表
		$this->__db->use_db("write");
		$daily_task_list = array();
		$sql = "select * from arbor_day_task_list";
		$res = $this->__db->query($sql);
		foreach ($res as $row) {
			$daily_task_list[] = array(
				"task_id"       => $row["task_id"],
				"task_name"     => $row["task_name"],
				"water"         => $row["water"],
				"ps"            => $row["ps"],
				"completed"     => 0,
				"complete_time" => 0
			);
		}
		return $daily_task_list;
	}

	public function getUserDailyTaskList($uin){  //拉取用户每日任务列表
		$user_daily_task_list = $this->getDailyTaskList();
		$sql = "select task_id, add_time from arbor_day_user_completed_task_list where uin = {$uin} and task_day = '{$this->task_day}' and task_id != 0";	
		$res = $this->__db->query($sql);
		$completed_task_id = array();
		foreach ($res as $row) {  //标记已完成任务
			$completed_task_id[] = $row["task_id"];
			$user_daily_task_list[$row["task_id"] - 1]["completed"] = 1;
			$user_daily_task_list[$row["task_id"] - 1]["complete_time"] = strtotime($row["add_time"]);
		}

		if (!in_array(DAILY_QUESTION_TASK_ID, $completed_task_id)) {  //查询用户是否完成每日答题任务
			$flag = $this->dailyQuestion($uin);
			if ($flag == -1) {
				return -1;
			} 
			if ($flag == 1) {
				$user_daily_task_list[DAILY_QUESTION_TASK_ID - 1]["completed"] = 1;
				$user_daily_task_list[DAILY_QUESTION_TASK_ID - 1]["complete_time"] = time();
			}
		}

		if (!in_array(DAILY_TOPIC_TASK_ID, $completed_task_id)) {  //查询用户是否完成每日话题任务
			$flag = $this->dailyTopic($uin);
			if ($flag == -1) {
				return -1;
			} 
			if ($flag == 1) {
				$user_daily_task_list[DAILY_TOPIC_TASK_ID - 1]["completed"] = 1;
				$user_daily_task_list[DAILY_TOPIC_TASK_ID - 1]["complete_time"] = time();
			}
		}

		return $user_daily_task_list;
	}
}
