<?php
/*
 * 从etcd里找到某个服务的成员的一个节点的ip:port
 * 根据各节点的负载来计算权重
 *
 */
class FlamingoETCD {
    private $etcd_server;    //etcd服务接口
    private $file_dir;       //保存临时文件目录
    private $tmp_file_time;  //临时文件有效期
    private $etcd_info_time; //从etcd接口里获取的信息的有效期

    /*
     * http://10.8.8.209:2379/v2/keys 正服
     * http://192.168.6.111:2379/v2/keys 测服
     *
     */
    function __construct( $etcd_server='http://10.8.8.209:2379/v2/keys' ) {
        $this->etcd_server = $etcd_server;
        $this->file_dir = '/tmp/etcd_client';
        if( !is_dir($this->file_dir) ) {
            @mkdir( $this->file_dir, 0777 );
        }
        $this->tmp_file_time = 20;
        $this->etcd_info_time = 30;
    }

    function __destruct() {

    }

    function getServer( $server_type, $idc = 'rmz', $stage = 0 ) {
        $info = $this->getServerByFile( $server_type ); 
        $addrs =  $this->comparisonEtcdKey( $info, $idc, $stage );
        if( $addrs ) {
            return $addrs;
        } 

        $info = $this->getServerByUrl( $server_type );
        $addrs = $this->comparisonEtcdKey( $info, $idc, $stage );
        return $addrs;
    }

    /*  
     * info etcd一个成员的所有节点信息 json
     * 过滤一个成员里所有节点的time(上报时间) load(负载)  idc(机房) 等信息
     * 并比对所有节点的负载，按照负载的权重做随机因子，随机返回一个节点
     * load越大，命中概率越小
     * return string
     *
     */
    private function comparisonEtcdKey( $info, $idc, $stage ) {
        if( !$info ) {
            return '';
        }
        $arr = json_decode( $info, true );
        if( !is_array($arr) ) {
            return '';
        }

        $total = 0;
        foreach( $arr as $k => $v ) {
            $value = json_decode( $v['value'], true );
            if( !is_array($value) ) {
                continue;
            }
            if( isset($value['idc']) && $value['idc'] ) {
                if( time() - $value['time'] > $this->etcd_info_time ) {
                    continue;
                }
                if( $value['idc'] != $idc || $value['stage'] != $stage ) {
                    continue;
                }
            }
            if( $value['status'] != 0 ) {
                continue;
            }
            $addrs_arr[$value['addrs']] = $value['load'];
            $total += $value['load'];
        }

        //先按照load升序排序
        asort( $addrs_arr );
        
        $sum = 0;
        foreach( $addrs_arr as $k => $v ) {
            $prob = (int)($v/$total*1000);
            $sum += $prob;
            $sort_arr[] = array(
                'prob' => $prob,
                'addrs' => $k,
            );
        }
        
        $i = 0; 
        $count = count( $sort_arr );
        foreach( $sort_arr as $k => $v ) {
            $i++;
            $rand_num = mt_rand( 1, $sum );
            if( $rand_num <= $v['prob'] ) {
                return $sort_arr[$count - $i]['addrs'];
            }
            $sum -= $v['prob']; 
        }
        return '';
    }

    /*
     * 从文件缓存里读取etcd一个成员的所有节点信息
     * $server_type 例: /xxzhushou_game_script 
     *
     * return json
     *
     */
    private function getServerByFile( $server_type ) {
        $file = $this->file_dir . $server_type;
        if( !is_file($file) ) {
            return ''; 
        }

        $file_status = $this->checkTmpFileValid( $file ); 
        if( !$file_status ) {
            return '';
        }
        return file_get_contents( $file );
    }

    private function checkTmpFileValid( $file ) {
        $handle = fopen( $file, 'r' );
        $fstat = fstat( $handle );
        fclose( $handle );
        if( !isset($fstat['mtime']) ) {
            return false;
        }

        $time = time();
        $mtime = $fstat['mtime']; 
        if( $time - $mtime > $this->tmp_file_time ) {
            return false;
        } 

        return true;
    }

    /*
     * 从etcd接口去获取一个成员的所有节点信息
     *
     * return json
     *
     */
    private function getServerByUrl( $server_type ) {
        //$url = $this->etcd_server . $server_type;
        $url = is_string($this->etcd_server) ? $this->etcd_server . $server_type : $this->etcd_server[mt_rand(0, count($this->etcd_server)-1)] . $server_type;
        $res = $this->makeRequest( $url );
        if( !isset($res['code']) || $res['code']!=200 ) {
            return '';
        }       

        $response = isset($res['result']) ? $res['result'] : ''; 
        if( !$response ) {
            return '';
        }
        $arr = json_decode( $response, true );
        if( !isset($arr['node']['nodes']) ) {
            return '';
        }

        $result = json_encode( $arr['node']['nodes'] ); 
        $this->writeServerInfoToFile( $server_type, $result );
        return $result;
    }

    private function writeServerInfoToFile( $server_type, $info ) {
        $file = $this->file_dir . $server_type;
        $res = file_put_contents( $file, $info ); 
        if( $res != strlen($info) ) {
            return false;
        }
        return true;
    }

    private function makeRequest($url, $argument = array(), $ttl = 15, $method = "GET", $cookie='', $follow=0){
        if (!$url) {
            throw new LogicException('url can not empty');
        }

        if (substr($url, 0, 7) != 'http://' && substr($url, 0, 8) != 'https://') {
            return array('result' => NULL, 'code' => '400');
        }
        if ($method == 'GET' && count($argument) > 0) {
            $url .= "?" . (http_build_query($argument));
        }
        $header = array(
            'Accept-Language: zh-cn,zh;q=0.8',
            'Connection: Keep-alive',
            'Cache-Control: max-age=0'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $argument);
        }
        if( file_exists($cookie) ){
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        }
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $ttl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if( $follow==1 ){
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        }
        $return = array();
        $return['result'] = curl_exec($ch);
        $return['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        unset($ch);

        return $return;
    }
}
