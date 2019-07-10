<?php
require_once dirname(dirname(__FILE__)) . "/utils/flamingoBaseThriftLink.class.php";
require_once dirname(dirname(__FILE__)) . "/utils/flamingoETCD.class.php";
require_once dirname(dirname(__FILE__)) . "/protocols/SGPUserLoginKey.proto.php";
require_once dirname(dirname(__FILE__)) . "/utils/logger.php";


class LLUserAuthServer extends FlamingoBaseThriftLink {
    public function __construct() {
        global $ETCD_SERVER_ARR;
        $etcd = new FlamingoETCD($ETCD_SERVER_ARR);
        $node = $etcd->getServer("/ll_auth_server");
        $data  = explode(":",$node);
        if (count($data) == 2) {
            $this->__ip = $data[0];
            $this->__port = $data[1];
        }
        parent::__construct();
        $this->Init();
    }
    private function Init() {
        $this->setHeadCmd(XXProto_SGPUserLoginKeyProto_CMD::CMD_SGPUserLoginKeyProto);
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
            $res = XXProto_SGPUserLoginKeyProto::parseFromString($res);
            if (isset($res)) {
                return $res;
            }
        }catch(TEception $tx) {
            file_put_contents("/tmp/thirftBasemanagerFailed.log", sprintf("\r\n %s:%d:%s", __FILE__, __LINE__, date("Y-m-d H:i:s")) . var_export($tx, true), FILE_APPEND);
	    return null;
        }
	return null;
    }
 
    public function checkUserLogin($productID, $udid,$platform, $uin, $login_key,$appid=0) {
        $this->setLoginKey($login_key);
        $this->setUin($uin);
        $request = new XXProto_SGPUserLoginKeyProto();
        $checkReq = new XXProto_SGPUserCheckLoginKeyReq();
        $checkReq->setProduct_id($productID);
        $checkReq->setAppid($appid);
        $checkReq->setLoginkey($login_key);
        $checkReq->setUuid($udid);
		$checkReq->setUin($uin);
        $request->setSubcmd(XXProto_SUBCMD_SGPUserLoginKeyProto::SUBCMD_SGPUserLoginKeyProto_CHECK_LOGINKEY_REQ);
        $request->setCheck_loginkey_req($checkReq);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if (isset($resp) && ($resp->getResult() == 0)) {
            return true;
        }else {
            FlamingoLogger::getInstance()->Logln("DEBUG", $uin, $udid, $login_key, $resp);
        }
        return false;
    }
}
