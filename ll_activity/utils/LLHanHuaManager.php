<?php
require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";

require_once SYSDIR_UTILS."/logger.php";
require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/voucherServer.class.php";
require_once SYSDIR_UTILS."/userInfoServer.class.php";
require_once SYSDIR_UTILS."/error.class.php";
require_once SYSDIR_UTILS."/LLActivityGoldenBollPrize.php";
require_once SYSDIR_UTILS."/LLActivityAggPrize.php";
require_once SYSDIR_UTILS."/XXRequestBase.php";

class LLHanHuaManager{
	private $__db;
	private $__vid;
	public function __construct() {
		$this->__db = new Db();
		$this->__db->use_db("write");
		$this->__vid = SHAOSAN_VOUCHERID;
	}
	public function sendUserVoucher($uin) {
		$this->__db->use_db("write");
		$sql = "select * from ll_hanhua_ad_voucher_log where uin = {$uin}";
		$data = $this->__db->query($sql);
		if (isset($data) && count($data) > 0) {
			return $data[0];
		}
		$voucherServer = new LLVoucherServer();
		if (true == $voucherServer->sendVoucher($uin, "test-flamingo-login-key-abc",md5($_SERVER['HTTP_USER_AGENT']), 136, 102,$this->__vid,0)) {
			FlamingoLogger::getInstance()->Logln('代金券发放成功: '.'uin '.$uin.' money_id '.$this->__vid);
			$insert = array();
			$insert['uin'] = $uin;
			$insert['vid'] = $this->__vid;
			$insert['add_time'] = date("Y-m-d H:i:s");
			$insert['ip'] = getIp();
			if (false == $this->__db->insert("ll_hanhua_ad_voucher_log",$insert)) {
				FlamingoLogger::getInstance()->Logln('代金券发放记录失败: '.'uin '.$uin.' money_id '.$this->__vid);
				FlamingoLogger::getInstance()->Logln($this->__db->sql);
				return $insert;
			}
			FlamingoLogger::getInstance()->Logln('代金券发放记录成功: '.'uin '.$uin.' money_id '.$this->__vid);
			return $insert;
		}else {
			FlamingoLogger::getInstance()->Logln('代金券发放失败: '.'uin '.$uin.' money_id '.$this->__vid);
			return null;
		}
	}
}
