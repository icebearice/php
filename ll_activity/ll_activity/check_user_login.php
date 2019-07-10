<?php                                                                                                           
/*me: checkUserLogin.php                                                                                        
 * * * Desc: 检查用户是否登录，并返回相关的用户信息                                                                
 * * ************************************************************************/                                     
//header('Access-Control-Allow-Origin:*');                                                                        
require_once dirname(dirname(__FILE__)) . "/include/config.php";                                                
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";                                            
require_once SYSDIR_UTILS . "/error.class.php";                                                                     
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
require_once SYSDIR_UTILS . "/logger.php";
FlamingoLogger::getInstance()->Logln($_REQUEST);

$response = array(                                                                                              
	'code'=>0,                                                                                                      
	'err_msg'=>'',                                                                                                  
	'data'=>'',                                                                                                     
);                                                                                                              
$uin = isset($_REQUEST['uin'])?$_REQUEST['uin']: 0;                                                             
$login_key = isset($_REQUEST['login_Key'])? $_REQUEST['login_Key']:"";                                          
$uuid = isset($_REQUEST['uuid'])? $_REQUEST['uuid']:"";                                                         
$productID = isset($_REQUEST['productID'])? $_REQUEST['productID'] : 136;                                       
$platform = isset($_REQUEST['platformType'])? $_REQUEST['platformType']:102;                                    
$appid = isset($_REQUEST['appID'])?$_REQUEST['appID']:0;
$auth = new LLUserAuthServer();                                                                                 

if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key,$appid)) {                                   
	$response['code'] =     ErrorCode::User_Not_Login;                                              
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);                      
	echo json_encode($response);                                                       
	exit();                                                                                
}                                                                                                               


$userHandler = new LLUserInfoServer();
$userInfo = $userHandler->getUserInfoByUin($uin);
$userInfo=json_decode(json_encode($userInfo), true);
$base_data=$userInfo['base_data'];
unset($base_data['upwd']);
unset($base_data['uex']);
unset($base_data['usalt']);
if(isset($base_data['uphone'])){
	$phone=$base_data['uphone'];
}else{
  $phone="";
}
$strlen=mb_strlen($phone, 'utf-8');
$firstStr= mb_substr($phone, 0, 3, 'utf-8');
$lastStr = mb_substr($phone, -4, 4, 'utf-8');
if(!empty($phone) || $phone == ""){
   $base_data['uphone']=$phone;
}else{
  $phone= $firstStr . str_repeat("*",4) . $lastStr;
  $base_data['uphone']=$phone;
}
$userInfo['base_data']=$base_data;
if(isset($userInfo)){
	$response['data']= $userInfo;
}
echo json_encode($response);                                                                                    
FlamingoLogger::getInstance()->Logln($response);
exit;   
