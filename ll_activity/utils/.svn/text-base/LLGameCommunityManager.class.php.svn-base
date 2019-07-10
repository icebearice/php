<?php
require_once dirname(dirname(__FILE__)) . "/utils/flamingoBaseThriftLink.class.php";
require_once dirname(dirname(__FILE__)) . "/utils/flamingoETCD.class.php";
require_once dirname(dirname(__FILE__)) . "/protocols/SLiuLiuGameCommunity.proto.php";
require_once dirname(dirname(__FILE__)) . "/utils/logger.php";

class LLGameCommunityManager extends FlamingoBaseThriftLink{
    public function __construct() {
        global $ETCD_SERVER_ARR;
        $etcd = new FlamingoETCD($ETCD_SERVER_ARR);
        $node = $etcd->getServer("/ll_game_community");
        $data  = explode(":",$node);
        if (count($data) == 2) {
            $this->__ip = $data[0];
            $this->__port = $data[1];
        }
        parent::__construct();
        $this->Init();
    }
    private function Init() {
        $this->setHeadCmd(XXProto_SLiuLiuGameCommunityProto_CMD::CMD_SLiuLiuGameCommunityProto);
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
            $res = XXProto_SLiuLiuGameCommunityProto::parseFromString($res);
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
     * 获取点评
     */
    public function getComment($id, $start, $count, $status=0, $appid=0) {
        $request = new XXProto_SLiuLiuGameCommunityProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuGameCommunityProto_SUBCMD::SUBCMD_SLiuLiuGameCommunityProto_GET_COMMENT_REQ);
        $req = new XXProto_SLiuLiuGameCommunityGetCommentReq();
        $req->setId($id);
        $req->setStart($start);
        $req->setNum($count);
        $req->setStatus($status);
        $req->setAppid($appid);
        $orderInfos = new XXProto_SLiuLiuGameCommunityOrderInfo();
        $orderInfos->setKey("sort_num");
        $orderInfos->setValue(1);
        $req->appendOrderInfo($orderInfos);
        
        $request->setGet_comment_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response); 
        if (!isset($resp) || $resp->getResult() != 0) {
            return array();
        }
        return json_decode(json_encode($resp->getGet_comment_res()->getComments()), true);
    }

    /*
     * 更新一个评论
     * 一般都是审核用
     *
     */
    public function updateComment($id, $status, $amount) {
        $request = new XXProto_SLiuLiuGameCommunityProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuGameCommunityProto_SUBCMD::SUBCMD_SLiuLiuGameCommunityProto_UPDATE_COMMENT_REQ);
        $req = new XXProto_SLiuLiuGameCommunityUpdateCommentReq();
        $comment = new XXProto_SLiuLiuGameCommunityCommentInfo();
        $comment->setId($id);
        $comment->setStatus($status);
        $comment->setAmount($amount);
        $req->setInfo($comment);
        if ($status > 0) {
            $req->appendSet_type(XXProto_SLiuLiuGameCommunityComment_SetType::SetType_CommentStatus);
        }
        if ($amount > 0) {
            $req->appendSet_type(XXProto_SLiuLiuGameCommunityComment_SetType::SetType_CommentAmount);
        }
        $request->setUpdate_comment_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if(!isset($resp) || $resp->getResult() != 0) {
            return false;
        }
        return true;
    }

    /*
     * 发布点评
     *
     */
    public function addComment($uid, $appid, $ip, $content, $is_share=1) {
        $request = new XXProto_SLiuLiuGameCommunityProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuGameCommunityProto_SUBCMD::SUBCMD_SLiuLiuGameCommunityProto_ADD_COMMENT_REQ);
        $req = new XXProto_SLiuLiuGameCommunityAddCommentReq();
        $comment = new XXProto_SLiuLiuGameCommunityCommentInfo();
        $comment->setUid($uid);
        $comment->setAppid($appid);
        $comment->setIp($ip);
        $comment->setContent($content);
        $comment->setIsShare($is_share);
        $req->setComment($comment);
        $request->setAdd_comment_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if(!isset($resp)) {
            return array(
                'result' => 1999,
                'err_msg' => '未知错误，请重试',    
            );
        }
        return array(
            'result' => $resp->getResult(),    
            'err_msg' => $resp->getErr_msg(),
        );
    }
}
