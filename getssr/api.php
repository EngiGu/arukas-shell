<?php


$cookie = dirname(__FILE__) . '/cookie_ss.txt';

//登录成功之后保存cookie
login($cookie);

//获取数据
get_content($cookie);

//删除cookie文件
@ unlink($cookie);


function login($cookie)
{
    $data = array(
        'email' => "451292130@qq.com", //此处输入您的arukas登录邮箱
        'password' => "gq19940507" //此处输入您的arukas登录密码
    );
    $loginUrl = "https://app.arukas.io/api/login";
    $ch_login = curl_init();
    curl_setopt($ch_login, CURLOPT_URL, $loginUrl);
    curl_setopt($ch_login, CURLOPT_POST, 1);
    curl_setopt($ch_login, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch_login, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch_login, CURLOPT_COOKIEJAR, $cookie);
    $result = curl_exec($ch_login);

    $content = json_decode($result);

    $status = $content->status;

    if ($status != "200") {
        echo $content->message;
        echo ' Please check the api.php file.';
        exit;
    };
    curl_close($ch_login);
}


//登录成功后获取数据
function get_content($cookie)
{
    //登录成功之后访问的页面
    $contextUrl = "https://app.arukas.io/api/containers";
    $ch_content = curl_init();
    curl_setopt($ch_content, CURLOPT_URL, $contextUrl);
    //curl_setopt($ch, CURLOPT_HEADER, 0);
    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
    curl_setopt($ch_content, CURLOPT_COOKIEFILE, $cookie); //读取cookie
    $result = curl_exec($ch_content);

    $httpcode = curl_getinfo($ch_content, CURLINFO_HTTP_CODE);
    if ($httpcode != "200") {
        echo "Oops!";
        exit;
    }

    return $result;
    curl_close($ch_content);
}


?>