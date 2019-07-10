<?php
/*************************************************************************
* File Name: GPVipInfo.php
* Author: zhibin.hong
* Desc: 果盘VIP相关 
************************************************************************/
//配置文件
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once SYSDIR_UTILS. "/DB.php";

class GPVIPInfo{
    public $db;
    
    function __construct(){
        $this->db = new DB();
    }

    function __destruct(){
    }


	function getVipLevel($uin){  //获取用户的vip等级，用数字表示
		$this->db->use_db('gppay');
		$_sql = "SELECT vip_level, curr_czz FROM gp_user_vip_list WHERE uin = '{$uin}'";
        $result = $this->db->query($_sql);
		if(empty($result)){
			return 0;
		}
		$vip_level = $result[0]['vip_level'] > 0 ? $result[0]['vip_level'] : 0;
		return $vip_level;
	}
}
