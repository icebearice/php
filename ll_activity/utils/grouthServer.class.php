<?php

/**
 * 用户成长值发放服务
 */

require_once dirname(dirname(__FILE__)) . "/utils/flamingoBaseThriftLink.class.php";
require_once dirname(dirname(__FILE__)) . "/utils/flamingoETCD.class.php";

require_once dirname(dirname(__FILE__)) . "/protocols/SLiuLiuXVipData.proto.php";
require_once dirname(dirname(__FILE__)) . "/protocols/SLiuLiuXVipBase.proto.php";
require_once dirname(dirname(__FILE__)) . "/utils/logger.php";


class LLGrouthServer extends FlamingoBaseThriftLink {
    public function __construct() {
        global $ETCD_SERVER_ARR;
        $etcd = new FlamingoETCD($ETCD_SERVER_ARR);
        $node = $etcd->getServer("/ll_vip_center");
        $data  = explode(":",$node);
        if (count($data) == 2) {
            $this->__ip = $data[0];
            $this->__port = $data[1];
        }
        parent::__construct();
        $this->Init();
    }
    private function Init() {
        $this->setHeadCmd(XXProto_SLiuLiuXVipProto_CMD::CMD_SLiuLiuVipProto);
    }
    public function __destruct() {
        parent::__destruct();
    }

    protected function praseResult($response) {
        $res = parent::praseResult($response);
        if ($res == null) {
            return null;
        }
        try {
            $res = XXProto_SLiuLiuXVipProto::parseFromString($res);
            if (isset($res)) {
                return $res;
            }
        }catch(TEception $tx) {
            file_put_contents("/tmp/thirftBasemanagerFailed.log", sprintf("\r\n %s:%d:%s", __FILE__, __LINE__, date("Y-m-d H:i:s")) . var_export($tx, true), FILE_APPEND);
	    return null;
        }
	return null;
    }
 
	/**
	 * 增加成长值
	 */

    public function addGrouthValue($uin,$value,$loginKey,$uuid,$productID,$platform,$appid = 0) {
		$userInfo = new XXProto_UserInfo();
		$userInfo->setUuid($uuid);
		$userInfo->setProductID($productID);
		$userInfo->setPlatformType($platform);
		$userInfo->setAppid($appid);
		$this->setAppID($appid);
		$this->setUin($uin);
		$this->setLoginKey($loginKey);
		$this->setUserInfo($userInfo);

        $request = new XXProto_SLiuLiuXVipProto();
        $req = new XXProto_SLLXPlusGrouthValueReq();
        $req->setGrouth_value($value);
        $req->setRemark('植树节活动奖励');
        $request->setSubcmd(XXProto_SLiuLiuXVipProto_SUBCMD::SUBCMD_SLiuLiuVipProto_PlusGrouthValueReq);
        $request->setPlus_grouth_value_req($req);
		@file_put_contents("/tmp/voucher_send.log", "[". strftime("%Y-%m-%d %H:%M:%S",time())."] uin:{$uin}-->". var_export($request,true)."\n", FILE_APPEND );
		@file_put_contents("/tmp/voucher_send.log", "[". strftime("%Y-%m-%d %H:%M:%S",time())."]". var_export($uin,true)."\n", FILE_APPEND );
        $response = $this->send($request);
        $resp = $this->praseResult($response);
		@file_put_contents("/tmp/voucher_send.log", "[". strftime("%Y-%m-%d %H:%M:%S",time())."]". var_export($resp,true)."\n", FILE_APPEND );
        if (!isset($resp) || ($resp->getResult() != 0)) {
            FlamingoLogger::getInstance()->Logln("DEBUG", "get voucher info error ", $resp);
            return FALSE;
        }
        return TRUE;
    }

    /**
	 *  获取用户等级
	 */

	public function getUserVipLevel($uin,$loginKey,$uuid,$productID,$platform,$appid = 0){
		$userInfo = new XXProto_UserInfo();
		$userInfo->setUuid($uuid);
		$userInfo->setProductID($productID);
		$userInfo->setPlatformType($platform);
		$userInfo->setAppid($appid);
		$this->setAppID($appid);
		$this->setUin($uin);
		$this->setLoginKey($loginKey);
		$this->setUserInfo($userInfo);

        $request = new XXProto_SLiuLiuXVipProto();
//        $req = new XXProto_SLLXUserVipInfoReq();
        $request->setSubcmd(XXProto_SLiuLiuXVipProto_SUBCMD::SUBCMD_SLiuLiuVipProto_UserVipInfoReq);
  //      $request->setUser_vip_info_req($req);
		@file_put_contents("/tmp/voucher_send.log", "[". strftime("%Y-%m-%d %H:%M:%S",time())."] uin:{$uin}-->". var_export($request,true)."\n", FILE_APPEND );
		@file_put_contents("/tmp/voucher_send.log", "[". strftime("%Y-%m-%d %H:%M:%S",time())."]". var_export($uin,true)."\n", FILE_APPEND );
        $response = $this->send($request);
        $resp = $this->praseResult($response);
		//print_r($resp);die;
		@file_put_contents("/tmp/voucher_send.log", "[". strftime("%Y-%m-%d %H:%M:%S",time())."]". var_export($resp,true)."\n", FILE_APPEND );
        if (!isset($resp) || ($resp->getResult() != 0)) {
            FlamingoLogger::getInstance()->Logln("DEBUG", "get voucher info error ", $resp);
            return FALSE;
        }

		$vip_info = $resp->getUser_vip_info_res();
		$vip_info = json_decode(json_encode($vip_info),true);
        return $vip_info;
	}
    
    /*
     * 增加成长值不要那么多花里花哨的参数
     *
     */
    public function addGrouthValueV2($uin, $value, $remark='管理员操作') {
		$this->setUin($uin);
        $request = new XXProto_SLiuLiuXVipProto();
        $req = new XXProto_SLLXPlusGrouthValueReq();
        $req->setGrouth_value($value);
        $req->setRemark($remark);
        $request->setSubcmd(XXProto_SLiuLiuXVipProto_SUBCMD::SUBCMD_SLiuLiuVipProto_PlusGrouthValueReq);
        $request->setPlus_grouth_value_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if (!isset($resp) || ($resp->getResult() != 0)) {
            return FALSE;
        }
        return TRUE;
    }
}
