<?php
/*************************************************************************
 * File Name:test.php
 * Author: lvchao.yan
 * Created Time: 2018.12.20
 * Desc:测试专用
 *************************************************************************/

require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)). "/include/config.inc.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/VIPInfo.php";
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
require_once SYSDIR_UTILS . "/happynewyear/DailyTaskList.class.php";
require_once SYSDIR_UTILS . "/happynewyear/MarkSexRoller.class.php";
require_once SYSDIR_UTILS . "/happynewyear/UserInfo.class.php";
require_once SYSDIR_UTILS . "/happynewyear/commonFunction.php";
require_once SYSDIR_UTILS . "/voucherServer.class.php";
require_once SYSDIR_UTILS . "/DB.php";
$__db = new Db();
$__db->use_db("write");
$sql = "select 1 from arbor_day_user_completed_task_list where uin = 3980 and task_id = 6 and task_day = '2019-02-21'";
$res = $__db->query($sql);
var_dump(count("false"));
