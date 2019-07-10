<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once SYSDIR_UTILS . '/LLDaliyTopicManager.class.php';

header("Content-type: text/html; charset=utf-8");
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$sign = isset($_REQUEST['sign']) ? $_REQUEST['sign'] : '';
$result = isset($_REQUEST['result']) ? $_REQUEST['result'] : 0;
if (!$id || !$sign || !$result || strlen($sign)!=32) {
    exit;
}

$obj = new LLDaliyTopicManager();
$replyInfo = $obj->getReply($id);
if (!$replyInfo) {
    exit;
}
$replyInfo = $replyInfo[0];
if ($replyInfo['status'] == 3) {
    echo "<script>alert('该评论已被删除');</script>";
    exit;
}

$res = $obj->updateReply($id, $result);
if ($res) {
    echo "<script>alert('处理成功');</script>";
    exit;
} else {
    echo "<script>alert('处理失败，请重试或联系管理员');</script>";
    exit;
}
