<?php
require_once dirname(dirname(__FILE__)) . "/utils/flamingoBaseThriftLink.class.php";
require_once dirname(dirname(__FILE__)) . "/utils/flamingoETCD.class.php";
require_once dirname(dirname(__FILE__)) . "/protocols/SLiuLiuGameBoard.proto.php";
require_once dirname(dirname(__FILE__)) . "/utils/logger.php";

class LLGameBoardManager extends FlamingoBaseThriftLink{
    public function __construct() {
        global $ETCD_SERVER_ARR;
        $etcd = new FlamingoETCD($ETCD_SERVER_ARR);
        $node = $etcd->getServer("/ll_game_board");
        $data  = explode(":",$node);
        if (count($data) == 2) {
            $this->__ip = $data[0];
            $this->__port = $data[1];
        }
        parent::__construct();
        $this->Init();
    }
    private function Init() {
        $this->setHeadCmd(XXProto_SLiuLiuGameBoardProto_CMD::CMD_SLiuLiuGameBoardProto);
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
            $res = XXProto_SLiuLiuGameBoardProto::parseFromString($res);
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
     * 获取榜单信息
     */
    public function getBoard($id, $start, $count) {
        $request = new XXProto_SLiuLiuGameBoardProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuGameBoardProto_SUBCMD::SUBCMD_SLiuLiuGameBoardProto_GET_BOARD_INFO_REQ);
        $req = new XXProto_SLiuLiuGameBoardGetBoardReq();
        $req->setId($id);
        $req->setStart($start);
        $req->setNum($count);
        
        $request->setGet_board_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response); 
        if (!isset($resp) || $resp->getResult() != 0) {
            return array();
        }
        return json_decode(json_encode($resp->getGet_board_res()->getBoards()), true);
    }

    /*
     * 获取游戏排行信息
     *
     */
    public function getRanking($boardId, $appid, $start, $count) {
        $request = new XXProto_SLiuLiuGameBoardProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuGameBoardProto_SUBCMD::SUBCMD_SLiuLiuGameBoardProto_GET_GAME_RANKING_REQ);
        $req = new XXProto_SLiuLiuGameBoardGetGameRankingReq();
        $req->setBoardId($boardId);
        if ($appid > 0) {
            $req->appendAppid($appid);
        }
        $req->setStart($start);
        $req->setNum($count);

        $request->setGame_ranking_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if (!isset($resp) || $resp->getResult() != 0) {
            return array();
        }
        return json_decode(json_encode($resp->getGame_ranking_res()->getRankings()), true);
    }

    /*
     * 打call
     *
     */
    public function hitCall($appid, $shareUin, $ip) {
        $request = new XXProto_SLiuLiuGameBoardProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuGameBoardProto_SUBCMD::SUBCMD_SLiuLiuGameBoardProto_HIT_CALL_REQ);
        $req = new XXProto_SLiuLiuGameBoardHitCallReq();
        $req->setIp($ip);
        $req->setUid($shareUin);
        $req->setAppid($appid);

        $request->setHitCallReq($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if (!isset($resp)) {
            return array(
                'code' => 1999,
                'err_msg' => '未知错误',    
            );
        }
        return array(
            'code' => $resp->getResult(),
            'err_msg' => $resp->getErr_msg(),
        );
    }

    /*
     * 结算榜单
     *
     */
    public function closeBoard($id) {
        $request = new XXProto_SLiuLiuGameBoardProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuGameBoardProto_SUBCMD::SUBCMD_SLiuLiuGameBoardProto_CLOSE_BOARD_REQ);
        $req = new XXProto_SLiuLiuGameBoardCloseBoardReq();
        $req->setBoard_id($id);
        $request->setClose_board_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if(!isset($resp) || $resp->getResult() != 0) {
            return false;
        }
        return true;
    }
}

