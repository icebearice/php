<?php
require_once dirname(dirname(__FILE__)) . "/utils/flamingoBaseThriftLink.class.php";
require_once dirname(dirname(__FILE__)) . "/utils/flamingoETCD.class.php";
require_once dirname(dirname(__FILE__)) . "/protocols/SXXGameUin.proto.php";
require_once dirname(dirname(__FILE__)) . "/utils/logger.php";

class GPGameUinManager extends FlamingoBaseThriftLink{
    public function __construct() {
        global $ETCD_SERVER_ARR;
        $etcd = new FlamingoETCD($ETCD_SERVER_ARR);
        $node = $etcd->getServer("/gp_game_uin");
        $data  = explode(":",$node);
        if (count($data) == 2) {
            $this->__ip = $data[0];
            $this->__port = $data[1];
        }
        parent::__construct();
        $this->Init();
    }
    private function Init() {
        $this->setHeadCmd(XXProto_SXXGameUinProto_CMD::CMD_SXXGameUinProto);
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
            $res = XXProto_SXXGameUinProto::parseFromString($res);
            if (isset($res)) {
                return $res;
            }
        } catch(TEception $tx) {
            file_put_contents("/tmp/thirftBasemanagerFailed.log", sprintf("\r\n %s:%d:%s", __FILE__, __LINE__, date("Y-m-d H:i:s")) . var_export($tx, true), FILE_APPEND);
            return null;
        }
        return null;
    }

    /*
     * 获取用户所有的gameuin
     */
    public function getGameUinByUid($uid) {
        if(empty($uid)) return array();
        $request = new XXProto_SXXGameUinProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SXXGameUinProto_SUBCMD::SUBCMD_SXXGameUinProto_GETUIDALLAPPIDANDGAMEUINREQ);
        $userAllGameUinReq = new XXProto_GetUidAllAppidAndGameUinReq();
        $userAllGameUinReq->setUid($uid);
        $request->setGet_uid_all_appid_and_game_uin_req($userAllGameUinReq);
        $response = $this->send($request);
        $res = $this->praseResult($response);
        if (!isset($res)) {
            return array();
        }
        if ($res->getResult() != 0) {
            return array();
        }
        $gameUinRes = $res->getGet_uid_all_appid_and_game_uin_res();
        if (!isset($gameUinRes)) {
            return array();
        }
        if ($gameUinRes->getSuccess() != true) {
            return array();
        }
        return json_decode(json_encode($gameUinRes->getAppid_and_game_uin_arr(), true));
    }
}
//$obj = new GPGameUinManager();
//var_dump($obj->getGameUinByUid(1806727));
