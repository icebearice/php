
<?php
/**
 * User: haian.jin
 * Date: 2019/5/16
 * Time: 13:34
 *
 * 消费送豪礼 展示任务
 */
require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/common/config.php';
require_once dirname(__FILE__) . '/common/func.php';
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
require_once SYSDIR_UTILS . '/voucherServer.class.php';

$result = array(
    'code' => -1,
    'msg' => '',
    'data' => array(),
);

//是否为活动时间
$time = isActivityTime($startTime, $endTime);
if (!$time['status']) {
    $result['msg'] = $time['msg'];
    echo json_encode($result);exit;
}

// 是否登录
$uin = isset($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
$login_key = isset($_REQUEST['login_Key']) ? $_REQUEST['login_Key'] : '';
$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : '';
$productID = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 136;
$platform = isset($_REQUEST['platformType']) ? $_REQUEST['platformType'] : 102;
$appid = isset($_REQUEST['appID']) ? $_REQUEST['appID'] : 0;

$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {
    $result['code']=503;
    $result['msg']='未登录';
    $result['data']=$consumeArr;
    echo json_encode($result);
    exit;
}

// 已登录
$obj = new Db();
$ids=implode(',',$gameArr);
// 任务相关信息
$now=date('Y-m-d');
$obj->use_db('read');
$now=date("Y-m-d");
$nowTime=strtotime($now);
foreach ($consumeArr as $k=>&$v){
    $taskId=$v['id'];
    $sqlGet = "SELECT * FROM ll_duanwu_consume_log WHERE uin=$uin and taskdate='{$now}' and taskID=$taskId";
    $consData = $obj->query($sqlGet);
    if (!$consData){
        if($taskId==5){
            $obj->use_db('write');
            $obj->query('start transaction');
            $sql="insert into ll_duanwu_consume_log(taskID,uin,taskdate,status) values(5,$uin,'{$now}',0)";

            @file_put_contents("/tmp/duanWuShoWTask.log",date('Y-m-d h:m:i')." 请求参数：".$sql."\n",FILE_APPEND );
            $obj->query($sql);
            $resUserNum = $obj->db->affected_rows;
            if (!$resUserNum) {
                @file_put_contents("/tmp/duanWuShoWTask.log",date('Y-m-d h:m:i')." 插入出错："."\n",FILE_APPEND );
                $obj->query('rollback');
            }
            $obj->query('commit');
        }
        $v['status']=0;
    }else{
        $finish= $consData[0]['status'];
        if($finish==1){
            $v['status']=1;
        }else{
            $v['status']=0;
        }
    }

}
@file_put_contents("/tmp/duanWuShoWTask.log",date('Y-m-d h:m:i')." 返回数据为".var_export($consumeArr,true)."\n",FILE_APPEND );
$result['msg']='登录成功';
$result['data']['taskList'] = $consumeArr;

$result['code']=1;
echo json_encode($result);
exit;



