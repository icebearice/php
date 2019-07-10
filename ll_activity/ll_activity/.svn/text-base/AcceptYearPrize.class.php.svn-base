<?php
/*************************************************************************
* File Name: AcceptYearPrize.class.php
* Author: lvchao.yan
* Created Time: 2018.12.20
* Desc: 打年货 
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

class AcceptYearPrize{
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

	public function showYearPrize(){  //获取年货信息
		$sql = "select prize_id,prize_name,prize_total,prize_vip_level,prize_icon from ll_activity_prize_list where prize_id between 1 and 6";
		return $this->__db->query($sql);
	}

    public function getUserAcceptPrizeId($uin){  //获取用户已领取年货id
		$accept_prize_id = array();
		$sql = "select prize_id from ll_activity_user_accept_prize_info where uin = {$uin} and prize_id between 1 and 6";
		$result = $this->__db->query($sql);
		if (count($result) != 0){
			foreach($result as $res){
				$accept_prize_id[] = $res['prize_id'];
			}
		}
		return $accept_prize_id;
	}

	public function getYearPrize($prizeId){  //用户领取年货
		$sql = "update ll_activity_prize_list set prize_total = prize_total-1 where prize_id = {$prizeId}";
		if ($this->__db->query($sql)) {
			return 1;
		} else {
			return 0;
		}
	}

	public function getUserVipLevel($uin){  //获取用户VIP等级信息
		return 'VIP2';
		//待完成............
	}

	public function isUserBindingPhone($uin){  //用户绑定手机验证
		return 1;
		//待完成............
	}
}