<?php                                                                                                      

/**                                                                                                        
 * Created by daodao                                                                                       
 * Date: 2017-04-12 10:06                                                                                  
 */                                                                                                        

class Ding {                                                                                               

	private $robot_url;                                                                                    
	private $logger_file_path;                                                                             
	private $debug_mode;                                                                                   

	/**                                                                                                    
	 * Ding constructor.                                                                                   
	 * @param $robot_url                                                                                   
	 * @param bool $debug_mode                                                                             
	 * @param null $logger_file_path                                                                       
	 */                                                                                                    
	public function __construct($robot_url, $debug_mode = false, $logger_file_path = null) {               

		$this->robot_url = $robot_url;                                                                     
		$this->logger_file_path = $logger_file_path ? $logger_file_path : "/tmp/ding_robot.log";           
		$this->debug_mode = $debug_mode;                                                                   
	}                                                                                                      

	/**                                                                                                    
	 * @param boolean $debug_mode                                                                          
	 */                                                                                                    

	public function set_debug_mode($debug_mode) {                                                          
		$this->debug_mode = $debug_mode ? true : false;                                                    
	}                                                                                                      

	/**                                                                                                    
	 * @param $content                                                                                     
	 * @param array $at_list                                                                               
	 * @param bool $is_at_all, default false                                                               
	 * @return bool                                                                                        
	 */                                                                                                    
	public function send_text($content, $at_list = array(), $is_at_all = false) {                          

		if (!$content) {                                                                                   
			return false;                                                                                  
		}                                                                                                  
		$mess_arr = array(                                                                                 
			'msgtype' => 'text',                                                                           
			'text' => array(                                                                               
				'content' => $content,                                                                     
			),                                                                                             
		);                                                                                                 

		if (!empty($at_list)) {                                                                            
			$mess_arr['at']['atMobiles'] = $at_list;                                                       
		}                                                                                                  
		if (true === $is_at_all) {                         
			$mess_arr['at']['isAtAll'] = true;             
		}                                                  
		return $this->send_to_ding(json_encode($mess_arr));
	}                                                      
	/**                                                                                                               
	 * @param $title                                                                                                  
	 * @param $text                                                                                                   
	 * @param $link_url                                                                                               
	 * @param string $pic_url                                                                                         
	 * @return bool                                                                                                   
	 */                                                                                                               
	public function send_link($title, $text, $link_url, $pic_url = "") {                                              

		if(!$title || !$text || !$link_url){                                                                          
			return false;                                                                                             
		}                                                                                                             
		$mess_arr = array(                                                                                            
			'msgtype' => 'link',                                                                                      
			'link' => array(                                                                                          
				'text' => $text,                                                                                      
				'title' => $title,                                                                                    
				'picUrl' => $pic_url,                                                                                 
				'messageUrl' => $link_url,                                                                            
			),                                                                                                        
		);                                                                                                            
		return $this->send_to_ding(json_encode($mess_arr));                                                           
	}                                                                                                                 

	/**                                                                                                               
	 * @param $title                                                                                                  
	 * @param $markdown                                                                                               
	 * @return bool                                                                                                   
	 */                                                                                                               
	public function send_markdown($title, $markdown){
	if(!$title || !$markdown){
			return false;                                                                                             
	}                                                                                                             
	$mess_arr = array(                                                                                            
		'msgtype' => 'markdown',                                                                                  
		'markdown' => array(                                                                                      
			'title' => $title,                                                                                    
			'text' => $markdown,                                                                                  
		),                                                                                                        
	);                                                                                                            
	return $this->send_to_ding(json_encode($mess_arr));                                                           
	}                                                                                                                 

	public function send_action_card($title, $text, $single_title, $single_url, $hide_avatar = 0, $btn_orientation = 0
	){                                                                                                                    
		if(!$title || !$text){                                                                                        
			return false;                                                                                             
		}                                                                                                             
		$mess_arr = array(                                                                                            
			'msgtype' => 'actionCard',                                                                                
			'actionCard' => array(                                                                                    
				'title' => $title,
				'text' => $text,
				'hideAvatar' => $hide_avatar,
				'btnOrientation' => $btn_orientation,
				'singleTitle' => $single_title,
				'singleURL' => $single_url,
			)
		);
		return $this->send_to_ding(json_encode($mess_arr));
	}

	/**
	 * @param $message_json
	 * @return bool
	 */

	private function send_to_ding($message_json) {

		$this->logger('message_json: ' . $message_json);
		if (!$message_json) {
			return false;
		}
		try {
			$res = $this->http_post_json($this->robot_url, $message_json);
			$this->logger('钉钉返回值: ' . var_export($res, true));             
		} catch (Exception $e) {                                                
			$this->logger('exception: ' . $e->getMessage());                    
			return false;                                                       
		}                                                                       
		if ($res['code'] == 200) {                                              
			$ding_err_code = json_decode($res['result'], true)['errcode'];      
			if ($ding_err_code == 0) {                                          
				$this->logger('消息发送成功');                                  
				return true;                                                    
			}                                                                   
			$this->logger('发送失败, ding_error_code: ' . $ding_err_code);      
		}                                                                       
		return false;                                                           
	}                                                                           

	/**                                                                                                               
	 * @param $url                                                                                                    
	 * @param $json_str
	 * @param int $ttl
	 * @return array
	 */
	private function http_post_json($url, $json_str, $ttl = 5) {

		if (!$url) {
			throw new LogicException("url error");
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, $ttl);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_str);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Content-Length: ' . strlen($json_str)));
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		return array('code' => $httpCode, 'result' => $response);
	}


	/**
	 * @param $content
	 */

	private function logger($content) {

		if($this->debug_mode) {
			@file_put_contents($this->logger_file_path, "[" . strftime("%Y%m%d%H%M%S", time()) . "]" . $content . "\n", FILE_APPEND);
		}
	}
}
