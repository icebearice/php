<?php
$_SERVER['RUN_MODE'] = 'development';
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once dirname(dirname(__FILE__)) . "/utils/flamingoQuestionThriftManager.class.php";


$handler = new QuestionThriftManager();
$handler->setUserInfo("  ",136, "uuid",101,887);
var_dump($handler->getQuestionList(1));


