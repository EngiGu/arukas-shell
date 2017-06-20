<?php
function login($returnCookie=1){
        $post="email=451292130%40qq.com&password=gq19940507";
        $url = "https://app.arukas.io/api/login";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        if($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        }
        
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if($returnCookie){
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie']  = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info['cookie'];
        }else{
            return $data;
        }
        
}

//登录成功后获取数据
function get_content($cookie)
{
    //登录成功之后访问的页面
    $contextUrl = "https://app.arukas.io/api/containers";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $contextUrl);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    if($cookie) {
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    return $data;        
}

//登录成功之后保存cookie
/*login($cookie);*/
$cookie = login($returnCookie=1);
//获取数据
$result= get_content($cookie);
/*echo $result;*/

function getNeedBetween($kw1,$mark1,$mark2){
$kw=$kw1;
//$kw='123′.$kw.'123′;
$st =stripos($kw,$mark1);
$ed =stripos($kw,$mark2);
if(($st==false||$ed==false)||$st>=$ed)
return 0;
$kw=substr($kw,($st+1),($ed-$st-1));
return $kw;
}
$pre_info=getNeedBetween($result, 'mappings' , 'created' );
echo "\"port_m",$pre_info,"</ br>";

?>
