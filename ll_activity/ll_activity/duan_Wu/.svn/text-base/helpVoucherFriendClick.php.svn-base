
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/15
 * Time: 9:32
 * 好友点击：这里需要传一个发起助力的uin和其ip
 */
require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/common/config.php';
require_once dirname(__FILE__) . '/common/func.php';
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
require_once SYSDIR_UTILS . '/voucherServer.class.php';
require_once SYSDIR_UTILS . '/XXRequestBase.php';
$result = array(
    'code' => -1,
    'msg' => ''
);

//是否为活动时间
$time = isActivityTime($startTime, $endTime);
if (!$time['status']) {
    $result['msg'] = $time['msg'];
    echo json_encode($result);exit;
}



// 分享链接需要传一个uin
$uin = isset($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
$now=date("Y-m-d");
$obj = new Db();
$obj->use_db('read');
//好友点击链接时后端先查询用户是否有发起当天是否有发起助力没有则默认生成一个助力
$selectSql="select * from ll_duanwu_help_voucher_select where uin=$uin and select_date='{$now}'";
$selectData=$obj->query($selectSql);
if (empty($selectData)){
    //获取往日的ip
    $selectIpSql="select * from ll_duanwu_help_voucher_select where uin=$uin";
    $ipList=$obj->query($selectIpSql);
    if(empty($ipList)){
        $result['msg']='检查uin是否正确';
        echo json_encode($result);exit;
    }
    $myip=$ipList[0]['myip'];
    $obj = new Db();
    $obj->use_db('write');
    //给当天生成一个新的助力
    $insertArr = array();
    $insertArr['uin'] = $uin;
    $insertArr['select_date'] = $now;
    $insertArr['count_of_nuo_mi'] = 0;
    $insertArr['friend_help_num'] = 0;
    $insertArr['myip'] = $myip;
    $obj->use_db('write');
    $obj->insert('ll_duanwu_help_voucher_select', $insertArr);
    $status = $obj->db->affected_rows;
    if (!$status) {
        $result['msg'] = 'll_duanwu_help_voucher_select';
        $result['code'] = 5118;
        logLn("uin:{$uin} ll_duanwu_help_voucher_select  error");
        echo json_encode($result);exit;
    }
    $selectData[]=$insertArr;
}
$myIp=$selectData[0]['myip'];
$ip=getIp();
//检验是否是自己给自己助力
if ($ip==$myIp){
    $result['msg'] = '自己不能给自己助力';
    $result['code'] = 549;
    echo json_encode($result);exit;
}
//判断是否已经当天助力过了
$obj->use_db('read');
$sql="select * from ll_duanwu_help_voucher_log where ip='{$ip}' and help_date='{$now}' and type=1";
$helpData=$obj->query($sql);
if (count($helpData)>0){
    $result['msg'] = '该ip今日已助力成功，如需继续助力请注册66，糯米数提升5倍';
    $result['code'] = 548;
    echo json_encode($result);
    exit;
}
//开启事务
$obj->use_db('write');
$obj->query('start transaction');
$friend_help_num=$selectData[0]['friend_help_num']+1;
$count_of_nuo_mi=$selectData[0]['count_of_nuo_mi']+1;
$sql="update ll_duanwu_help_voucher_select set friend_help_num=$friend_help_num ,count_of_nuo_mi=$count_of_nuo_mi where uin=$uin and select_date='{$now}'";
$obj->query($sql);
$updaRows = $obj->db->affected_rows;
if (!$updaRows) {
    $obj->query('rollback');
    logLn("uin:{$uin} sql excute error：$sqlUpda");
    echo json_encode(array('code' => 51070, 'msg' => '操作频繁'));exit;
}
$sql="insert into ll_duanwu_help_voucher_log value ('',1,'{$ip}','{$now}')";
$obj->query($sql);
$inseRows = $obj->db->affected_rows;
if (!$inseRows) {
    $obj->query('rollback');
    logLn("uin:{$uin}, sql excute error $sqlInsr");
    echo json_encode(array('code' => 51073, 'msg' => '操作频繁'));exit;
}
$obj->query('commit');
$result['msg'] = '助力成功';
$result['code'] = 1;
echo json_encode($result);
exit;
