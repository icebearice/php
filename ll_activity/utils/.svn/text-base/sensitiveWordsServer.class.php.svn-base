<?php
require_once dirname(dirname(__FILE__)) . "/utils/flamingoBaseThriftLink.class.php";
require_once dirname(dirname(__FILE__)) . "/utils/flamingoETCD.class.php";
require_once dirname(dirname(__FILE__)) . "/protocols/SGPSensitiveWords.proto.php";
require_once dirname(dirname(__FILE__)) . "/utils/logger.php";


class SensitiveWordsServer extends FlamingoBaseThriftLink {
    public function __construct() {
        global $ETCD_SERVER_ARR;
        $etcd = new FlamingoETCD($ETCD_SERVER_ARR);
        $node = $etcd->getServer("/gp_sensitive_words");
        $data  = explode(":",$node);
        if (count($data) == 2) {
            $this->__ip = $data[0];
            $this->__port = $data[1];
        }
        parent::__construct();
        $this->Init();
    }
    private function Init() {
        $this->setHeadCmd(XXProto_SGPSensitiveWordsProto_CMD::CMD_SGPSensitiveWordsProto);
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
            $res =  XXProto_SGPSensitiveWordsProto::parseFromString($res);
            if (isset($res)) {
                return $res;
            }
        }catch(TEception $tx) {
            file_put_contents("/tmp/thirftBasemanagerFailed.log", sprintf("\r\n %s:%d:%s", __FILE__, __LINE__, date("Y-m-d H:i:s")) . var_export($tx, true), FILE_APPEND);
	    return null;
        }
	return null;
    }
 
    public function checkByWord($word) {
        $request = new XXProto_SGPSensitiveWordsProto();
        $infoReq = new XXProto_SGPSensitiveWordsCheckWordsReq();
        $infoReq->setWords($word);
        $request->setSubcmd(XXProto_SUBCMD_SGPSensitiveWordsProto::SUBCMD_SGPSensitiveWordsProto_CHECK_WORDS_REQ);
        $request->setCheck_words_req($infoReq);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if (!isset($resp) || ($resp->getResult() != 0)) {
            FlamingoLogger::getInstance()->Logln("DEBUG", "get user info error ", $request);
            return null;
		}
		if($resp->getCheck_words_res()->getIs_hit() == 1){
			return TRUE;
		}
		return FALSE;
    }
}
