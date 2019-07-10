<?php
exit;
require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/config.php';
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . '/VIPInfo.php';
require_once SYSDIR_UTILS . '/voucherServer.class.php';
require_once SYSDIR_UTILS . '/grouthServer.class.php';

$result = array(
    'code' => 0,
    'msg' => '',
    'data' => array(),
);

$extendPrize = array(
    0 => array('vip_level'=>0, 'pay_type'=>1, 'num'=>20, 'name'=>'送20成长值',),
    1 => array('vip_level'=>1, 'pay_type'=>2, 'num'=>2934, 'name'=>'满6减1代金券',),
    2 => array('vip_level'=>2, 'pay_type'=>2, 'num'=>2936, 'name'=>'满6减1代金券',),
    3 => array('vip_level'=>3, 'pay_type'=>2, 'num'=>2938, 'name'=>'满8减2代金券',),
    4 => array('vip_level'=>4, 'pay_type'=>2, 'num'=>2940, 'name'=>'满8减2代金券',),
    5 => array('vip_level'=>5, 'pay_type'=>2, 'num'=>2942, 'name'=>'满8减2代金券',),
    6 => array('vip_level'=>6, 'pay_type'=>2, 'num'=>2944, 'name'=>'满20减5代金券',),
    7 => array('vip_level'=>7, 'pay_type'=>2, 'num'=>2946, 'name'=>'满20减5代金券',),
    8 => array('vip_level'=>8, 'pay_type'=>2, 'num'=>2948, 'name'=>'满20减5代金券',),
    9 => array('vip_level'=>9, 'pay_type'=>2, 'num'=>2950, 'name'=>'满20减5代金券',),
);

$obj = new Db();
$obj->use_db('write');
$sql = 'SELECT * FROM ll_fools_user_prize ORDER BY add_time ASC';
$data = $obj->query($sql);
$vipObj = new VIPInfo(); 
$voucherObj = new LLVoucherServer();
$grouthObj = new LLGrouthServer();
foreach( $data as $k => $v ) {
    $sql = "SELECT id FROM ll_fools_lottery WHERE uid = '{$v['uid']}'";
    $isExists = $obj->query($sql);
    if ($isExists) {
        continue;
    }
    $vip_level = $vipObj->getVipLevel($v['uid']);
    $insert_arr = array(
        'uid' => $v['uid'],
        'prize_name' => $extendPrize[$vip_level]['name'],
        'add_time' => time(),
        'descript' => $extendPrize[$vip_level]['pay_type']==1 ? '成长值可提升VIP升级速度享受更多福利哦' : '可在APP内“我的——代金券”查看',
    );
    $obj->insert('ll_fools_push_prize', $insert_arr);
    $rows = $obj->db->affected_rows;
    if ($rows > 0) {
        if ($extendPrize[$vip_level]['pay_type'] == 1) { //发成长值
            $pushRes = $grouthObj->addGrouthValueV2($v['uid'], $extendPrize[$vip_level]['num'], '愚人节活动奖励');
        } else { //发代金券
            $pushRes = $voucherObj->sendVoucher($v['uid'], 'test-flamingo-login-key-abc', 'adc', 136, 102, $extendPrize[$vip_level]['num'], 0);
        }
        if (!$pushRes) {
            echo "{$v['uid']} {$vip_level} send failed!!!\n";
        }
    }
}
