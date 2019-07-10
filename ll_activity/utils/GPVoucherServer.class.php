<?php
require_once dirname(dirname(__FILE__)) . "/utils/flamingoBaseThriftLink.class.php";
require_once dirname(dirname(__FILE__)) . "/utils/flamingoETCD.class.php";
require_once dirname(dirname(__FILE__)) . "/protocols/LiuLiuXVoucherData.proto.php";

require_once dirname(dirname(__FILE__)) . "/protocols/LiuLiuXVoucherBase.proto.php";
require_once dirname(dirname(__FILE__)) . "/protocols/XXPBBase.proto.php";
require_once dirname(dirname(__FILE__)) . "/utils/logger.php";


class GPVoucherServer extends FlamingoBaseThriftLink {
    public function __construct() {
        global $ETCD_SERVER_ARR;
        $etcd = new FlamingoETCD($ETCD_SERVER_ARR);
        $node = $etcd->getServer("/gpv3_voucher_center");
        $data  = explode(":",$node);
        if (count($data) == 2) {
            $this->__ip = $data[0];
            $this->__port = $data[1];
        }
        parent::__construct();
        $this->Init();
    }
    private function Init() {
        $this->setHeadCmd(XXProto_LiuLiuXVoucherProto_CMD::CMD_LiuLiuVoucherProto);
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
            $res = XXProto_LiuLiuXVoucherProto::parseFromString($res);
            if (isset($res)) {
                return $res;
            }
        }catch(TEception $tx) {
            file_put_contents("/tmp/thirftBasemanagerFailed.log", sprintf("\r\n %s:%d:%s", __FILE__, __LINE__, date("Y-m-d H:i:s")) . var_export($tx, true), FILE_APPEND);
	        return null;
        }
	    return null;
    }
 
    public function sendVoucher($uin, $loginKey, $uuid, $productID, $platform, $vid, $appid = 0) {
		$userInfo = new XXProto_UserInfo();
		$userInfo->setUuid($uuid);
		$userInfo->setProductID($productID);
		$userInfo->setPlatformType($platform);
		$userInfo->setAppid($appid);
		$this->setAppID($appid);
		$this->setUin($uin);
		$this->setLoginKey($loginKey);
		$this->setUserInfo($userInfo);

        $request = new XXProto_LiuLiuXVoucherProto();
        $infoReq = new XXProto_LLXGetVoucherReq();
        $infoReq->appendVids($vid);
        $request->setSubcmd(XXProto_LiuLiuXVoucherProto_SUBCMD::SUBCMD_LiuLiuVoucherProto_GetVoucherReq);
        $request->setGet_voucher_req($infoReq);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if (!isset($resp) || ($resp->getResult() != 0)) {
            FlamingoLogger::getInstance()->Logln("DEBUG", "get voucher info error ", $resp);
            return FALSE;
        }
        return TRUE;
    }

    public function getVoucherInfo($vid) {
        $request = new XXProto_LiuLiuXVoucherProto();
        $infoReq = new XXProto_LLXGetVoucherInfoReq();
        $infoReq->setVid($vid);
        $request->setSubcmd(XXProto_LiuLiuXVoucherProto_SUBCMD::SUBCMD_LiuLiuVoucherProto_GetVoucherInfoReq);
        $request->setGet_voucher_info_req($infoReq);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if (!isset($resp) || ($resp->getResult() != 0)) {
            FlamingoLogger::getInstance()->Logln("DEBUG", "get voucher info error ", $resp);
            return FALSE;
        }
		$info = $resp->getGet_voucher_info_res();
        if (!$info) {
            return False;
        }
		$res = json_decode(json_encode($info),true);
		return $res['info'];
    }
}
