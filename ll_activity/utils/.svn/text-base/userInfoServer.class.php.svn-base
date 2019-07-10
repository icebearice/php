<?php
require_once dirname(dirname(__FILE__)) . "/utils/flamingoBaseThriftLink.class.php";
require_once dirname(dirname(__FILE__)) . "/utils/flamingoETCD.class.php";
require_once dirname(dirname(__FILE__)) . "/protocols/SLiuLiuUser.proto.php";
require_once dirname(dirname(__FILE__)) . "/utils/logger.php";


class LLUserInfoServer extends FlamingoBaseThriftLink {
    public function __construct() {
        global $ETCD_SERVER_ARR;
        $etcd = new FlamingoETCD($ETCD_SERVER_ARR);
        $node = $etcd->getServer("/ll_user_content");
        $data  = explode(":",$node);
        if (count($data) == 2) {
            $this->__ip = $data[0];
            $this->__port = $data[1];
        }
        parent::__construct();
        $this->Init();
    }
    private function Init() {
        $this->setHeadCmd(XXProto_SLiuLiuUserProto_CMD::CMD_SLiuLiuUserProto);
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
            $res =  XXProto_SLiuLiuUserProto::parseFromString($res);
            if (isset($res)) {
                return $res;
            }
        }catch(TEception $tx) {
            file_put_contents("/tmp/thirftBasemanagerFailed.log", sprintf("\r\n %s:%d:%s", __FILE__, __LINE__, date("Y-m-d H:i:s")) . var_export($tx, true), FILE_APPEND);
	    return null;
        }
	return null;
    }
 
    public function getUserInfoByUin($uin) {
        $request = new XXProto_SLiuLiuUserProto();
        $infoReq = new XXProto_SLiuLiuUserGetInfoReq();
        $infoReq->setUid($uin);
        $request->setSubcmd(XXProto_SLiuLiuUserProto_SUBCMD::SUBCMD_SLiuLiuUserProto_GET_INFO_REQ);
        $request->setGet_info_req($infoReq);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if (!isset($resp) || ($resp->getResult() != 0)) {
            FlamingoLogger::getInstance()->Logln("DEBUG", "get user info error ", $request);
            return null;
        }
        return $resp->getGet_info_res()->getInfo();
    }
    public function getUserNameByUin($uin) {
        $uinfo = $this->getUserInfoByUin($uin);
        if (!isset($uinfo)) {
            return "一个好的66用户";
        } 
        return $uinfo->getBase_data()->getUnickname();
    }
}
