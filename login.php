<?php
ini_set('display_errors', 1);
include 'NetUtils.php';

$rootDomain = '.epacific.net';
$cookieName = 'ai_open_access_token';
$cookieName2 = 'ai_open_refesh_token';

$logout = isset($_REQUEST['logout']) ? $_REQUEST['logout'] : 0;
$login = isset($_REQUEST['login']) ? $_REQUEST['login'] : 1;
if($logout == 1){
    $cookie_options = array(
        'expires' => time() - 60*60*24*30,
        'path' => '/',
        'domain' => $rootDomain, // leading dot for compatibility or use subdomain
        'secure' => false, // or false
        'httponly' => false, // or false
        'samesite' => 'None' // None || Lax || Strict
      );

      
    // setcookie($cookieName, $cookieValue, time() + 3600, '/', $rootDomain);
    setcookie($cookieName, '', $cookie_options);

    setcookie($cookieName2, '', $cookie_options);
}else{
    if($login == 1){
        $username = "shayn@vinova.com.sg";
        $password = '123';
    }elseif($login == 2){

        $username = "xuanthac123@gmail.com";
        $password = '123';

    }else{
        $username = "namdoel1412";
        $password = '123';
    }

    print_r($username);
    
    

    $url = 'https://identity-stg.epacific.net/realms/epacific/protocol/openid-connect/token';
    $scope = 'offline_access API';
    $grant_type = 'password';
    $client_id = 'workflowai';
    

    $REACT_APP_IDENTITY_URL = ' https://identity-stg.epacific.net';
    $REACT_APP_IDENTITY_REALM = 'epacific';
    $EREACT_APP_IDENTITY_CLIENT_ID = 'workflowai';
    

    //$username = "long@gmail.com";
    

    $client_secret = 'sFGPSe75luRg70vRmg0iTjJ6e9oroYMc';


    // $post = "grant_type=$grant_type&client_id=$client_id&username=$username&password=$password&client_secret=$client_secret";

    $post = "username=$username&password=$password&client_id=$client_id&grant_type=$grant_type&REACT_APP_IDENTITY_URL=$REACT_APP_IDENTITY_URL&REACT_APP_IDENTITY_REALM=$REACT_APP_IDENTITY_REALM&EREACT_APP_IDENTITY_CLIENT_ID=$EREACT_APP_IDENTITY_CLIENT_ID";

    $header_type = array("Content-Type: application/x-www-form-urlencoded");

    $result = NetUtils::curlWithCookie($url, $post, '', $header_type, false, 120);

    $result_array = json_decode($result['content'], true);
    var_dump($result_array);
    
    $cookieValue = $result_array['access_token'];
    $cookieValue2 = $result_array['refresh_token'];
    $cookie_options = array(
        'expires' => time() + 60*60*24*30,
        'path' => '/',
        'domain' => $rootDomain, // leading dot for compatibility or use subdomain
        'secure' => false, // or false
        'httponly' => false, // or false

      );

      
    // setcookie($cookieName, $cookieValue, time() + 3600, '/', $rootDomain);
    setcookie($cookieName, $cookieValue, $cookie_options);
    setcookie($cookieName2,$cookieValue2, $cookie_options);
    setcookie('tewt', $cookieValue, array(
        'domain'=>'.epacific.net',
        'expires' => time() + 60*60*24*30,
        'path' => '/',
        'secure' => false, // or false
        'httponly' => false, // or false
    ));
}

?>

<html>
    <head></head>
    <body>
        <form action="?logout=1" method="post" >
            <input type="submit" name="submit" value="Logout">
        </form>
        <form action="?login=1" method="post" >
            <input type="submit" name="submit" value="Login 1">
        </form>
        <form action="?login=2" method="post" >
            <input type="submit" name="submit" value="Login 2">
        </form>
        <form action="?login=3" method="post" >
            <input type="submit" name="submit" value="Login 3">
        </form>

    </body>

</html>
