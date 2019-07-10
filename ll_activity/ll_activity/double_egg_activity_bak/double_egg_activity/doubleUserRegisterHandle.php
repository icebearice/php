<?php

/**
 * Note: 用户注册成功处理
 * User: laixing.chen
 * Date: 2018/11/02
 * Time: 15:05
 */

require_once dirname(dirname(__DIR__)) . "/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/LLActivityBaseData.php";
require_once SYSDIR_UTILS . "/XXRequestBase.php";
require_once SYSDIR_UTILS . "/LLActivityAggPrize.php";
require_once dirname(__FILE__) . "/commonFunctions.php";

error_reporting(E_ALL);



if (date('Y-m-d') < Double_Activity_Start_Time) {
    response(1001, "activity not start");
}

if (date('Y-m-d') >= Double_Activity_End_Time) {
    response(1001, "activity is over");
}
if (empty($_REQUEST['uin']) || !is_numeric($_REQUEST['uin'])) {
    response(1001, "uin is invalid");
}
if (empty($_REQUEST['cid']) || $_REQUEST['cid'] != 96058) {
    response(1002, "cid is not right");
}
if (empty($_REQUEST['t'])) {
    response(1003, "time is empty");
}
if (empty($_REQUEST['key']) || $_REQUEST['key'] != 'll_register') {
    response(1004, "key is invalid");
}
if (empty($_REQUEST['sign'])) {
    response(1006, "sign is empty");
}

/*$sign = create_verify($_REQUEST, '54a2cd0ca2aa75uhliuliusjzpan9a66');
if ($sign != $_REQUEST['sign']) {
    response(1007, "sign is invalid");
}
*/

/*$db = new \LLApi\Db\DB();
$db->use_db("llpay_slave");
$vid = [2780,2782,2786];
$useLog = $db->query("select id from pay_voucher_log where uin = {$_REQUEST['uin']} and vid in (2780,2782,2786)");
if (!empty($useLog) && count($useLog) > 0) {
    response(1008, "already got the prize");
    exit;
}
*/
$vid = [1674,1672,1670];

$PrizeManage = new LLActivityAggPrizeManager();
$desc = "双旦活动>>新注册用户奖励";

foreach($vid as $v) {
   $res = $PrizeManage->grantVoucher($_REQUEST['uin'],$v,$desc);
   //var_dump($res);die;
}

/*$PrizeManage->grantVoucher($_REQUEST['uin'], 1316, $desc);
$PrizeManage->grantVoucher($_REQUEST['uin'], 1318, $desc);
$PrizeManage->grantVoucher($_REQUEST['uin'], 1320, $desc);
 */


response(0, 'success');
exit;
