<?php
require_once dirname(dirname(__FILE__)) . "/utils/flamingoBaseThriftLink.class.php";
require_once dirname(dirname(__FILE__)) . "/utils/flamingoETCD.class.php";
require_once dirname(dirname(__FILE__)) . "/protocols/SGPSms.proto.php";
require_once dirname(dirname(__FILE__)) . "/utils/logger.php";


class SMSThriftManager extends FlamingoBaseThriftLink{
    public function __construct() {
        global $ETCD_SERVER_ARR;
        $etcd = new FlamingoETCD($ETCD_SERVER_ARR);
        $node = $etcd->getServer("/sms");
        $data  = explode(":",$node);
        if (count($data) == 2) {
            $this->__ip = $data[0];
            $this->__port = $data[1];
        }
        parent::__construct();
        $this->Init();
    }
    private function Init() {
        $this->setHeadCmd(XXProto_SGPSmsProto_CMD::CMD_SGPSmsProto);
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
            $res = XXProto_SGPSmsProto::parseFromString($res);
            if (isset($res)) {
                return $res;
            }
        }catch(TEception $tx) {
            file_put_contents("/tmp/thirftBasemanagerFailed.log", sprintf("\r\n %s:%d:%s", __FILE__, __LINE__, date("Y-m-d H:i:s")) . var_export($tx, true), FILE_APPEND);
	    return null;
        }
	return null;
    }
    public function setUserInfo($login_key, $productID, $udid, $platform, $uin) {
        $this->setLoginKey($login_key);
        $this->setUin($uin);
        $userInfo = new XXProto_UserInfo();
        $userInfo->setUuid($udid);
        $userInfo->setProductID($productID);
        $userInfo->setPlatformType($platform);
        $userInfo->setVersion("1.0.0");
        parent::setUserInfo($userInfo);
    }
    public function sendDefaultMessage($phone, $msg) {
        $request = new XXProto_SGPSmsProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SUBCMD_SGPSmsProto::SUBCMD_SGPSmsProto_SENT_PHONE_USER_DEFINED_REQ);
        $req = new XXProto_SGPSmsSentPhoneUserDefinedReq();
        $req->setPhone_number($phone);
		$req->setSms_context($msg);
		$req->setSms_token(md5("huangzelinshiyigetiancai"));
		$req->setSms_user_name("testToken");
		$req->setSms_temple_id(1);
        $request->setSend_phone_user_defined_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if (!isset($resp) || ($resp->getResult() != 0)) {
            return false;
        }
        return true;
    } 
}

