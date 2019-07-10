<?php
$_SERVER['RUN_MODE'] = 'development';
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once dirname(dirname(__FILE__)) . "/utils/flamingoSMSThriftManager.class.php";


$handler = new SMSThriftManager();
$handler->setUserInfo("  ",136, "uuid",101,887);
$phone = 15876538366;
$msg = "【果盘游戏】亲爱的ax，果盘赠送您价值288元周边大礼包一份！数量有限，快联系您的VIP美女客服微信号：guopanvip3或flamingovip1（贝贝）登记，礼物自动到家！果盘圣斗士星矢重生期待与您再创辉煌！如遇异常，可联系在线客服QQ：800032344咨询";
var_dump($handler->sendDefaultmessage($phone, $msg));
