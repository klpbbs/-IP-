<?php
    // 淘宝API查询国家代码
    $url = "http://ip.taobao.com/service/getIpInfo.php?ip=".get_client_ip()."&accessKey=alibaba-inc";
    $json = json_decode(file_get_contents($url));
    $country = $json->{"data"}->{"country_id"};
    
    // 判断国家代码 把需要判断的国家加在下面就行了
    $countrys = array("CN");
    
    // 用meta标签跳转防止被拦截
    if (in_array($country, $countrys))
    {
        echo '<meta http-equiv="Refresh" content="0;url=/t/ip/'.strtolower($country).'" />';
    }
    else
    {
        echo '<meta http-equiv="Refresh" content="0;url=/t/ip/gw/" />';
    }

    // 获取IP地址
    function get_client_ip($type = 0) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }