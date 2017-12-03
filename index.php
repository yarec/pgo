<?php

function cacert_file(){
    return "cacert.pem";
}
/**
 * 使用curl来读取或发送数据
 * @param string $url
 * @param int $connecttime		连接时间
 * @param int $timeout	超时时间
 * @param string $postFields	使用POST方式请求
 * @return
 */
function curl($url,$connecttime=10,$timeout=30,$postFields=''){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connecttime);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.8) Gecko/20100202 Firefox/3.5.8 GTB7.0');//IE7
    curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    #curl_setopt($ch, CURLOPT_ENCODING, "gzip");

    if(file_exists(cacert_file())){
        curl_setopt($ch, CURLOPT_CAINFO, cacert_file());
    }
    if($postFields){
        if(is_array($postFields)){
            $postFields = http_build_query($postFields);
        }
        //指定post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        //添加变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    }
    $result = curl_exec($ch);
    if(curl_errno($ch)){
        #\Yii::log(curl_error($ch).'==>'.var_export(curl_getinfo($ch),true),'error','curlContent');
        return '';
    }
    curl_close($ch);
    return $result;
}

header("Content-Type:text/html;charset=utf-8");

$url = 'http://google.com';
if(isset($_GET['q'])){
    $w = $_GET['q'];
    $url = "$url/search?ie=ISO-8859-1&hl=es&source=hp&biw=&bih=&q=$w&btnG=Buscar+con+Google&gbv=2";
}
#$url = "compress.zlib://" . $url;
$c = file_get_contents($url);
#$c = curl($url);

echo $c;
die;
