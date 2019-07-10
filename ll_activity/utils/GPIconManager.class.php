<?php
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/XXRequestBase.php";

class GPIconManager{
	public function __construct() {
		$this->__key = PAYCENTER_GPICON_KEY;
		$this->__secret = PAYCENTER_GPICON_SECRET;
		$this->__url = PAYCENTER_GPICON_REBATE_URL;
	}

	//发放平台币
	public function sendPlatformCoin($uin, $platform_coin, $reason = "66活动奖励",$source_type = 3){
		$t = time();
		$order_sn = "ll_task_reward_" . date("YmdHis"). rand(1000000, 10000000);
		//$coin_type = "llcoin";
		$type = 1;
		$token = "ThisIsToken" . rand(999, 10000);
		$rebate = $platform_coin * 100;
		$operate_admin="flamingo";
		$source_sn="";
		$req_arr = array(
			'orderSn' => $order_sn,
			'uin' => $uin,
			'reason' => $reason,
			'key' => $this->__key,
			'rebate' => $rebate,
			'type' => $type,
			'token' => $token,
			't' => $t,
			'operate_admin'=> $operate_admin,
			'source_type' => $source_type,
			'source_sn' => $order_sn
		);  

		$sign = $this->createVerify($req_arr, $this->__secret);
		$req_arr['sign'] = $sign;
		$p_result = send_http_request($this->__url . "?". http_build_query($req_arr), null, true);
		$result = json_decode($p_result, true);
		if($result['code'] != 200){
			return false;
		}else{
			return true;
		}
	}

	// 签名算法
	private function createVerify($request, $secret) {
        ksort($request);
        $result = '';
        foreach ($request as $key => $value) {
                if ($key == 'sign') {
                        continue;
                }
                $result .= $value;
        }
        $result .= $secret;
        $result = md5($result);
        return $result;
	}
}
