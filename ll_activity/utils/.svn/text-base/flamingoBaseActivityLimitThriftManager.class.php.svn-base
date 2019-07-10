<?php
require_once dirname(dirname(__FILE__)) . "/utils/flamingoBaseThriftLink.class.php";
require_once dirname(dirname(__FILE__)) . "/utils/flamingoETCD.class.php";
require_once dirname(dirname(__FILE__)) . "/protocols/SActivityLimit.proto.php";
require_once dirname(dirname(__FILE__)) . "/utils/logger.php";

class BaseActivityLimitThriftManager extends FlamingoBaseThriftLink{
    public function __construct() {
        global $ETCD_SERVER_ARR;
        $etcd = new FlamingoETCD($ETCD_SERVER_ARR);
        $node = $etcd->getServer("/base_activity_limit");
        $data  = explode(":",$node);
        if (count($data) == 2) {
            $this->__ip = $data[0];
            $this->__port = $data[1];
        }
        parent::__construct();
        $this->Init();
    }
    private function Init() {
        $this->setHeadCmd(XXProto_SActivityLimitProto_CMD::CMD_SActivityLimitProto);
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
            $res = XXProto_SActivityLimitProto::parseFromString($res);
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
     * @params $modelType int 1每日限制模式,2每周限制模式,3每月限制模式
     */
    public function GetInfo($activityId, $name, $value, $modelType=1, $limitTimes=1) {
        $request = new XXProto_SActivityLimitProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SActivityLimitProto_SUBCMD::SUBCMD_SActivityLimitProto_GET_REQ);
        $req = new XXProto_SActivityLimitGetInfoReq();
        $info = new XXProto_SActivityLimitInfo();
        $field = new XXProto_ActivityFieldInfo();
        $field->setName($name);
        $field->setValue($value);
        $field->setModel($modelType);
        $field->setLimit_times($limitTimes);
        $info->setActivity_id($activityId);
        $info->appendField($field); //只支持一个？
        $req->setInfo($info);
        $request->setGet_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if (!isset($resp) || ($resp->getResult() != 0)) {
            return array();
        }
        return json_decode(json_encode($resp->getGet_res()), true);
    } 

    public function CheckInfo($content) {
        $request = new XXProto_SActivityLimitProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SActivityLimitProto_SUBCMD::SUBCMD_SActivityLimitProto_CHECK_WORD_REQ);
        $req = new XXProto_SActivityLimitCheckWordReq();
        $req->setContent($content);
        $request->setCheck_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if (!isset($resp) || ($resp->getResult() != 0)) {
            return array();
        }
        return json_decode(json_encode($resp->getCheck_res()->getBlock()), true);
    }

    public function ReportInfo($activityId, $arr) {
        $request = new XXProto_SActivityLimitProto();
        $request->setResult(0);
        $request->setSubcmd(XXProto_SActivityLimitProto_SUBCMD::SUBCMD_SActivityLimitProto_REPORT_REQ);
        $req = new XXProto_SActivityLimitReportReq();
        $info = new XXProto_SActivityLimitInfo();
        foreach( $arr as $k => $v ) {
            if (!isset($v['name']) || !isset($v['value'])) {
                continue;
            }
            $field = new XXProto_ActivityFieldInfo();
            $field->setName($v['name']);
            $field->setValue($v['value']);
            $info->appendField($field);
        }
        $info->setActivity_id($activityId);
        $req->setInfo($info);
        $request->setReport_req($req);
        $response = $this->send($request);
        $resp = $this->praseResult($response);
        if (!isset($resp) || ($resp->getResult() != 0)) {
            return false;
        }
        return true;
    }
}
