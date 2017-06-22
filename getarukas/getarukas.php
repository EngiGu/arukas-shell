<?php
function login($returnCookie=1){
        $post="email=15527434825%40163.com&password=gq19940507";
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
    $contextUrl = "https://app.arukas.io/api/containers/ca6744fd-4677-4aea-a71e-f2e80f646ca5";
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
//echo $result;

$content=json_decode($result);
//var_dump($content);

//得到密码
$cmd=$content->data->attributes->cmd;
//echo $cmd,"</ br>";
$tmp=explode(" ", $cmd);
//var_dump($tmp);
$password=$tmp[5];
//echo $password;
//得到加密方法
$decodemethod=$tmp[7];
//echo $decodemethod;
//得到协议
$protocol=$tmp[11];
//echo $protocol;
//得到混淆
$confusion=$tmp[9];
//echo $confusion;




//得到IP
$host=$content->data->attributes->port_mappings[0][0]->host;
//echo $host,"</ br>";
$tmp=explode(".", $host);
//var_dump($tmp);
$tmp=explode("-", $tmp[0]);
$host=$tmp[1].'.'.$tmp[2].'.'.$tmp[3].'.'.$tmp[4];
//echo $host;

//得到端口
$port=$content->data->attributes->port_mappings[0][0]->service_port;
//echo $port;

//153.125.232.237:31343:auth_sha1:rc4-md5:http_simple:R3ExOTk0MDUwNys=/?obfsparam=bS4xMDAxMC5jb20va3pydw==&remarks=5qix6Iqx5LqR
$ssrurl="ssr://".base64_encode($host.":".$port.":".$protocol.":".$decodemethod.":".$confusion.":".base64_encode($password)."/?obfsparam=bS4xMDAxMC5jb20va3pydw==&remarks=5qix6Iqx5LqR");
//echo $ssrurl;
//已经弃用
//$qcurl="http://s.jiathis.com/qrcode.php?url=".$ssrurl;
//$img = file_get_contents($qcurl); 
//file_put_contents('ssr.png',$img); 
//echo '<img src="ssr.png">';


$html=<<<"ht"
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="email=no, address=no, telephone=no">
    <title>Arukas获取 </title>
    <!-- build:css css/style.css -->
    <link rel="stylesheet" href="http://wap.10010hb.net/weixin/statics/css/screen.css">
    <!-- endbuild -->
    <script type="text/javascript">
        var mainDomain = "http://wap.10010hb.net/weixin";
    </script>
</head>
<body>
<div class="page page-netFlow">
    <div class="ui-list-title">
        您的樱花云信息如下：
    </div>
    <ul class="ui-list ui-list-text ui-border-tb" id="netInfo">
        <li class="ui-border-t">
            <div class="ui-list-info">
                <h4>IP</h4>
            </div>
            <div class="ui-list-action"><em name="amount">$host</em></div>
        </li>
        <li class="ui-border-t">
            <div class="ui-list-info">
                <h4>端口</h4>
            </div>
            <div class="ui-list-action"><em name="amount">$port</em></div>
        </li>
        <li class="ui-border-t">
            <div class="ui-list-info">
                <h4>加密方式</h4>
            </div>
            <div class="ui-list-action"><em name="amount">$decodemethod</em></div>
        </li>
        <li class="ui-border-t">
            <div class="ui-list-info">
                <h4>协议</h4>
            </div>
            <div class="ui-list-action"><em name="amount">$protocol</em></div>
        </li>
        <li class="ui-border-t">
            <div class="ui-list-info">
                <h4>混淆方式</h4>
            </div>
            <div class="ui-list-action"><em name="amount">$confusion</em></div>
        </li>
        <li class="ui-border-t">
            <div class="ui-list-info">
                <h4>SSR链接</h4>
            </div>
                 <form action="">
      <input type="text" class="share-input" size="10" value="$ssrurl" AutoComplete="off" id="copy-content"/>
      <button class="copy-button" type="button" onclick="copyContent();"> 点我复制 </button>
     </form>
     <script type="text/javascript">
     /*Copy function implementation */
        function copyContent(){ 
        var copyobject=document.getElementById("copy-content");
        copyobject.select();
        document.execCommand("Copy");
        alert("已复制成功哦~"); 
       };
      </script>
 
        </li>
        <li class="ui-border-t">
            <div class="ui-list-info">
                <h4>SSR二维码</h4>
            </div>
           <img src="http://s.jiathis.com/qrcode.php?url=$ssrurl" >
        </li>
    </ul>
    <div class="ui-txt-remark">
        <p>注：本页信息仅供参考使用，详细内容以登陆Arukas官网查询内容为准。</p>
    </div>
</div>

<!-- build:js js/lib/base.js -->
<script src="http://wap.10010hb.net/weixin/statics/js/lib/zepto.js"></script>
<script src="http://wap.10010hb.net/weixin/statics/js/lib/frozen.js"></script>
<script src="http://wap.10010hb.net/weixin/statics/js/lib/mustache.js"></script>
<script src="http://wap.10010hb.net/weixin/statics/js/common.js"></script>
<!-- endbuild -->

</body>
</html>
ht;

echo $html;
?>
