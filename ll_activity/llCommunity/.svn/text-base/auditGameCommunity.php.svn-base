<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once SYSDIR_UTILS . '/LLGameCommunityManager.class.php';

header("Content-type: text/html; charset=utf-8");
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$sign = isset($_REQUEST['sign']) ? $_REQUEST['sign'] : '';
$result = isset($_REQUEST['result']) ? $_REQUEST['result'] : 0;
if (!$id || !$sign || !$result || strlen($sign)!=32) {
    exit;
}

$obj = new LLGameCommunityManager();
$commentInfo = $obj->getComment($id, 0, 1);
if (!$commentInfo) {
    exit;
}
$commentInfo = $commentInfo[0];
if ($commentInfo['status'] != 2) {
    echo "<script>alert('该点评已处理');</script>";
    exit;
}
$realSign = md5($commentInfo['id'].$commentInfo['appid'].$commentInfo['content'].$commentInfo['ip']);
if ($realSign != $sign) {
    echo "<script>alert('错误的信息，请联系管理员');</script>";
    exit;
}

if ($result == 99) { //发奖励的情况
    $amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : 0;
    if (!$amount) {
        echo '<html>
                <form action="" method="GET">
                    <input type="hidden" name="id" value="'.$id.'" />
                    <input type="hidden" name="sign" value="'.$sign.'" />
                    <input type="hidden" name="result" value="'.$result.'" />
                    内容:'.$commentInfo['content'].'"<br />
                    奖励:<select name="amount" type="amount" >
                        <option value="1" >1个平台币</option>
                        <option value="2" >2个平台币</option>
                        <option value="3" >3个平台币</option>
                        <option value="4" >4个平台币</option>
                        <option value="5" >5个平台币</option>
                    </select>
                    <input type="submit" value="确认" />
                    </form> 
                </html>';
        exit;
    } 
    
    $res = $obj->updateComment($id, 1, $amount*100);//单位分
} else {
    $res = $obj->updateComment($id, $result, 0);
}

if ($res) {
    echo "<script>alert('处理成功');</script>";
    exit;
} else {
    echo "<script>alert('处理失败，请重试或联系管理员');</script>";
    exit;
}
