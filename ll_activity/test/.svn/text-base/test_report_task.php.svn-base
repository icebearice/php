<?php
$_SERVER['RUN_MODE'] = 'development';
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once dirname(dirname(__FILE__)) . "/utils/question.class.php";

$handler = LLQuestionManager::getInstance();
$uin =  rand(100,1000);
$task_id = 1;
$report = array(1 =>array(1),2 => array(1)); 
var_dump($handler->userReportQuestion($uin, $task_id, $report));


