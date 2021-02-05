# 使用介绍

------

该php代码可以判断用户来自国外还是国内，然后做出相应的行为可以用于

> * 禁止国外访问
> * 跳转至单独域名使用cdn
> * 等等

这是一个简单的程序
下面介绍一下运行原理

    <?php
        // 淘宝API查询国家代码
        $url = "http://ip.taobao.com/service/getIpInfo.php?ip=".get_client_ip()."&accessKey=alibaba-inc";
        // 解析返回的JSON
        $json = json_decode(file_get_contents($url));
        // 写入国家ID变量
        $country = $json->{"data"}->{"country_id"};
        
        // 判断国家代码 把需要判断的国家加在下面就行了
        $countrys = array("CN");
        
        // 用meta标签跳转防止被拦截
        if (in_array($country, $countrys))
        {
        // 当符合判断时，做出行为
            echo '<meta http-equiv="Refresh" content="0;url=/t/ip/'.strtolower($country).'" />';
        }
        else
        {
            // 不符合时做出行为
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
