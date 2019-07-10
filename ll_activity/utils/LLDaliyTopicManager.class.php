<?php
require_once dirname(dirname(__FILE__)) . "/utils/flamingoBaseThriftLink.class.php";
require_once dirname(dirname(__FILE__)) . "/utils/flamingoETCD.class.php";
require_once dirname(dirname(__FILE__)) . "/protocols/SLiuLiuDaliyTopic.proto.php";
require_once dirname(dirname(__FILE__)) . "/utils/logger.php";

class LLDaliyTopicManager extends FlamingoBaseThriftLink{
    public function __construct() {
        global $ETCD_SERVER_ARR;
        $etcd = new FlamingoETCD($ETCD_SERVER_ARR);
        $node = $etcd->getServer("/ll_daliy_topic");
        $data  = explode(":",$node);
        if (count($data) == 2) {
            $this->__ip = $data[0];
            $this->__port = $data[1];
        }
        parent::__construct();
        $this->Init();
    }
    private function Init() {
        $this->setHeadCmd(XXProto_SLiuLiuDaliyTopicProto_CMD::CMD_SLiuLiuDaliyTopicProto);
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
            $res = XXProto_SLiuLiuDaliyTopicProto::parseFromString($res);
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
     * 添加回复
     */
    public function addReply($uid, $topic_id, $content, $ip, $label_id=0) {
        $request = new XXProto_SLiuLiuDaliyTopicProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuDaliyTopicProto_SUBCMD::SUBCMD_SLiuLiuDaliyTopicProto_ADD_REPLY_REQ);
        $req = new XXProto_SLiuLiuDaliyTopicAddReplyReq();
        $reply = new XXProto_SLiuLiuDaliyTopicReply();
        $reply->setUid($uid); 
        $reply->setTopicId($topic_id);
        $reply->setContent($content);
        $reply->setStatus(1);
        $reply->setLabel_id($label_id);
        $reply->setIp($ip);

        $req->setReply($reply);
        $request->setAdd_reply_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if (!isset($resp)) {
            return array(
                'code' => 1999,
                'msg' => '未知错误',
            ); 
        }
        return array(
            'code' => $resp->getResult(),
            'msg' => $resp->getErr_msg(),
        );
    } 

    /*
     * 获取话题列表
     * 这里暂时更多是获取最新话题
     *
     */
    public function getTopic($uid = 0, $start=0, $num=1, $status=1, $id=0) {
        $request = new XXProto_SLiuLiuDaliyTopicProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuDaliyTopicProto_SUBCMD::SUBCMD_SLiuLiuDaliyTopicProto_GET_TOPIC_REQ);
        $req = new XXProto_SLiuLiuDaliyTopicGetTopicReq();
        $req->setId($id);
        $req->setUid($uid);
        $req->setStatus($status);
        $orderInfo = new XXProto_SLiuLiuDaliyTopicOrderInfo();
        $orderInfo->setKey("push_time");
        $orderInfo->setValue(1);
        $req->appendOrder_info($orderInfo);
        $req->setStart($start);
        $req->setNum($num);

        $request->setGet_topic_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if (!isset($resp) || $resp->getResult() != 0) {
            return array();
        }
        return json_decode(json_encode($resp->getGet_topic_res()->getInfos()), true);
    }

    /*
     * 获取回复
     */
    public function getReply($id=0, $uid=0, $start=0, $num=1, $topic_id=0, $status=0, $not_uid=0, $sortArr=array('sort_num'=>1, 'id'=>1),$reply_type=0) {
        $request = new XXProto_SLiuLiuDaliyTopicProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuDaliyTopicProto_SUBCMD::SUBCMD_SLiuLiuDaliyTopicProto_GET_REPLY_REQ);
        $req = new XXProto_SLiuLiuDaliyTopicGetReplyReq();
        $req->setId($id);
        $req->setUid($uid);
        $req->setStatus($status);
        foreach($sortArr as $k => $v) {
            $orderInfo = new XXProto_SLiuLiuDaliyTopicOrderInfo();
            $orderInfo->setKey($k);
            $orderInfo->setValue($v);
            $req->appendOrder_info($orderInfo);
        }

        $req->setStart($start);
        $req->setNum($num);
        $req->setTopic_id($topic_id);
        $req->setNot_uid($not_uid);
	$req->setReply_type($reply_type);
        $request->setGet_reply_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response); 
        if (!isset($resp) || $resp->getResult() != 0) {
            return array();
        }
        return json_decode(json_encode($resp->getGet_reply_res()->getReplys()), true);
    }

    /*
     * 对一个回复点赞
     *
     */
    public function addLike($uid, $reply_id, $ip,$second_reply_id) {
        $request = new XXProto_SLiuLiuDaliyTopicProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuDaliyTopicProto_SUBCMD::SUBCMD_SLiuLiuDaliyTopicProto_ADD_LIKE_REQ);
        $req = new XXProto_SLiuLiuDaliyTopicAddLikeReq();
        $like = new XXProto_SLiuLiuDaliyTopicLike();
        $like->setUid($uid);
        $like->setLike_reply_id($reply_id);
        $like->setIp($ip);
	$like->setLike_second_reply_id($second_reply_id);
        $req->setLike($like);
        $request->setAdd_like_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if (!isset($resp)) {
            return array(
                'code' => 1999,
                'msg' => '未知错误',
            ); 
        }
        return array(
            'code' => $resp->getResult(),
            'msg' => $resp->getErr_msg(),
        );
    }

    /*
     * 获取标签信息
     *
     */
    public function getLabelInfo($ids) {
        $request = new XXProto_SLiuLiuDaliyTopicProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuDaliyTopicProto_SUBCMD::SUBCMD_SLiuLiuDaliyTopicProto_GET_LABEL_INFO_REQ);
        $req = new XXProto_SLiuLiuDaliyTopicGetLabelReq();
        foreach($ids as $id) {
            $req->appendLabel_id($id);
        }
        $request->setLabel_info_req($req); 
        $response = $this->send($request); 
        $resp = $this->praseResult($response);
        if(!isset($resp) || $resp->getResult() != 0) {
            return array(); 
        }
        return json_decode(json_encode($resp->getLabel_info_res()->getInfos()), true);
    }

    /*
     * 获取点赞信息
     *
     */
    public function getLike($uid, $reply_id,$second_reply_id) {
        $request = new XXProto_SLiuLiuDaliyTopicProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuDaliyTopicProto_SUBCMD::SUBCMD_SLiuLiuDaliyTopicProto_GET_LIKE_REQ);
        $req = new XXProto_SLiuLiuDaliyTopicGetLikeReq();
        $req->setUid($uid);
        $req->setReply_id($reply_id);
	$req->setSecond_reply_id($second_reply_id);
        $request->setGet_like_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if(!isset($resp) || $resp->getResult() != 0) {
            return array();
        }
        return json_decode(json_encode($resp->getGet_like_res()->getLike()), true);
    }

    /*
     * 更新一个评论
     * 一般都是审核用
     *
     */
    public function updateReply($id, $status) {
        $request = new XXProto_SLiuLiuDaliyTopicProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuDaliyTopicProto_SUBCMD::SUBCMD_SLiuLiuDaliyTopicProto_UPDATE_REPLY_REQ);
        $req = new XXProto_SLiuLiuDaliyTopicUpdateReplyReq();
        $reply = new XXProto_SLiuLiuDaliyTopicReply();
        $reply->setId($id);
        $reply->setStatus($status);
        $req->setReply($reply);
        $req->appendSet_type(XXProto_SLiuLiuDaliyTopicUpdate_ReplySetType::ReplySetType_status);
        $request->setUpdate_reply_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if(!isset($resp) || $resp->getResult() != 0) {
            return false;
        }
        return true;
    }

    /*
     * 举报一个评论 
     */
    public function addTipOff($uin,$reply_id,$report_type,$report_comment,$second_reply_id=0,$ip="") {
        $request = new XXProto_SLiuLiuDaliyTopicProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuDaliyTopicProto_SUBCMD::SUBCMD_SLiuLiuDaliyTopicProto_ADD_TIP_OFF_REQ);
        $req = new XXProto_SLiuLiuDaliyTopicAddTipOffReq();
        $req->setUid($uin);
        $req->setReport_type($report_type);
        $req->setReport_comment($report_comment);
        $req->setReply_id($reply_id);
        $req->setSecond_reply_id($second_reply_id);
        $req->setIp($ip);
        $request->setAdd_tip_off_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if(!isset($resp) || $resp->getResult() != 0) {
            return $resp->getResult();
        }
        return 0;
    }

    public function getVoteInfo($topic_id) {
	    $request = new XXProto_SLiuLiuDaliyTopicProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuDaliyTopicProto_SUBCMD::SUBCMD_SLiuLiuDaliyTopicProto_GET_VOTE_INFO_REQ);
	    $req = new XXProto_SLiuLiuDaliyTopicGetVoteInfoReq();
	    $req->setTopic_id($topic_id);
	    $request->setGet_vote_info_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if(!isset($resp) || $resp->getResult() != 0) {
            return $resp->getResult();
        }
	    return json_decode(json_encode($resp->getGet_vote_info_res()->getVote_info()),true);
    }

    public function addVote($uin,$vote_id,$select) {
    	$request = new XXProto_SLiuLiuDaliyTopicProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuDaliyTopicProto_SUBCMD::SUBCMD_SLiuLiuDaliyTopicProto_ADD_VOTE_REQ);
        $req = new XXProto_SLiuLiuDaliyTopicAddVoteReq();
        $req->setUin($uin);
        $req->setVote_id($vote_id);
        $req->setSelect_button($select);
        $request->setAdd_vote_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if(!isset($resp) || $resp->getResult() != 0) {
            return $resp->getResult();
        }
	    return 0;
    }

    public function getSecondReply($reply_id=0,$start=0, $num=1,$second_reply_id = 0,$sortArr=array('sort_num'=>1, 'id'=>0),$id=0,$status = 0) {
        $request = new XXProto_SLiuLiuDaliyTopicProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuDaliyTopicProto_SUBCMD::SUBCMD_SLiuLiuDaliyTopicProto_GET_SECOND_REPLY_REQ);
        $req = new XXProto_SLiuLiuDaliyTopicGetSecondReplyReq();
        $req->setReplyId($reply_id);
        $req->setStart($start);
        $req->setNum($num);
        $req->setSecond_reply_id($second_reply_id);
        $req->setId($id);
	if ($status) {
		$req->setStatus($status);
	}
        foreach($sortArr as $k => $v) {
            $orderInfo = new XXProto_SLiuLiuDaliyTopicOrderInfo();
            $orderInfo->setKey($k);
            $orderInfo->setValue($v);
            $req->appendOrder_info($orderInfo);
        }

        $request->setGet_second_reply_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response); 
        if (!isset($resp) || $resp->getResult() != 0) {
            return array();
        }
        return json_decode(json_encode($resp->getGet_second_reply_res()->getReplys()), true);
    }

    public function addSecondReply($uin, $reply_id,$second_reply_id, $content, $ip,$status=1) {
        $request = new XXProto_SLiuLiuDaliyTopicProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SLiuLiuDaliyTopicProto_SUBCMD::SUBCMD_SLiuLiuDaliyTopicProto_ADD_SECOND_REPLY_REQ);
        $req = new XXProto_SLiuLiuDaliyTopicAddSecondReplyReq();
        $second_reply = new XXProto_SLiuLiuDaliySecondReply();
        $second_reply->setUid($uin);
        $second_reply->setReply_id($reply_id);
        $second_reply->setSecond_reply_id($second_reply_id);
        $second_reply->setContent($content);
        $second_reply->setIp($ip);
        $second_reply->setStatus($status);
        $req->setSecond_reply($second_reply);
        $request->setAdd_second_reply_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response); 
        if (!isset($resp) || $resp->getResult() != 0) {
            return $resp->getResult();
        }
        return 0;
    }
}

//$a = new LLDaliyTopicManager();
//$res = $a->addReply(111, 10, '评论，随便点评一下啦', '1.1.1.2');
//var_dump($res);
