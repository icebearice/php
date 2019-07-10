<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once SYSDIR_UTILS . '/LLDaliyTopicManager.class.php';

$response = array(
    'code'=> 0,
    'err_msg' => '',
    'topic_list'=> '',
);

$topicId = 29;

$obj = new LLDaliyTopicManager();
$topic_list = $obj->getTopic(0, 0, 1, 1, $topicId);
if (!$topic_list) {
    echo json_encode($response);
    exit;
}

$response['topic_list'] = $topic_list;
print_r($response);
exit;
