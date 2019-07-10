<?php
require_once dirname(dirname(__FILE__)) . "/utils/flamingoBaseThriftLink.class.php";
require_once dirname(dirname(__FILE__)) . "/utils/flamingoETCD.class.php";
require_once dirname(dirname(__FILE__)) . "/protocols/SLiuLiuXGameData.proto.php";
require_once dirname(dirname(__FILE__)) . "/utils/logger.php";
require_once dirname(dirname(__FILE__)) . "/protocols/LiuLiuXGameBase.proto.php";
require_once dirname(dirname(__FILE__)) . "/protocols/GameBase.proto.php";

class LLGameDataManager extends FlamingoBaseThriftLink{
    public function __construct() {
        global $ETCD_SERVER_ARR;
        $etcd = new FlamingoETCD($ETCD_SERVER_ARR);
        $node = $etcd->getServer("/ll_game_detail_center");
        $data  = explode(":",$node);
        if (count($data) == 2) {
            $this->__ip = $data[0];
            $this->__port = $data[1];
        }
        parent::__construct();
        $this->Init();
    }
    private function Init() {
        $this->setHeadCmd(XXProto_SLiuLiuXGameDataProto_CMD::CMD_SLiuLiuXGameDataProto);
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
            $res = XXProto_SLiuLiuXGameDataProto::parseFromString($res);
            if (isset($res)) {
                return $res;
            }
        }catch(TEception $tx) {
            file_put_contents("/tmp/thirftBasemanagerFailed.log", sprintf("\r\n %s:%d:%s", __FILE__, __LINE__, date("Y-m-d H:i:s")) . var_export($tx, true), FILE_APPEND);
            return null;
        }
        return null;
    }

    /*
     * 获取游戏信息
     */
    public function getGameInfo($id) {
        $request = new XXProto_SLiuLiuXGameDataProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuXGameDataProto_SUBCMD::SUBCMD_SLiuLiuXGameDataProto_DetailInfosReq);
        $req = new XXProto_SLLXGameDetailInfosReq();
        $req->appendId($id);
        
        $request->setInfos_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response); 
        if (!isset($resp) || $resp->getResult() != 0) {
            return array();
        }
        return json_decode(json_encode($resp->getInfos_res()->getApps()), true);
    }
}
