<?php
ini_set('display_errors', 1);
include 'NetUtils.php';

$rootDomain = '.epacific.net';
$cookieName = 'ai_open_access_token';
$cookieName2 = 'ai_open_refresh_token';

$username = "shayn@vinova.com.sg";
$password = '123';

$url = 'https://identity-stg.epacific.net/realms/epacific/protocol/openid-connect/logout';
$scope = 'offline_access API';
$grant_type = 'password';
$client_id = 'workflowai';
    

$REACT_APP_IDENTITY_URL = ' https://identity-stg.epacific.net';
$REACT_APP_IDENTITY_REALM = 'epacific';
$EREACT_APP_IDENTITY_CLIENT_ID = 'workflowai';
$access_token = $_COOKIE['ai_open_access_token'];
$refresh_token = $_COOKIE['ai_open_refresh_token'];



print_r($_COOKIE['ai_open_access_token'] . "<br><br><br>");

print_r($_COOKIE['ai_open_refresh_token'] . "<br><br><br>");

    //$username = "long@gmail.com";
    

$client_secret = 'sFGPSe75luRg70vRmg0iTjJ6e9oroYMc';


// $post = "grant_type=$grant_type&client_id=$client_id&username=$username&password=$password&client_secret=$client_secret";

$post = "client_id=$client_id&refresh_token=$refresh_token";

$header_type = array(
    "Content-Type: application/x-www-form-urlencoded",
    "Authorization: Bearer " . $access_token,
);

$result = NetUtils::curlWithCookie($url, $post, '', $header_type, false, 120);


var_dump($result);

?>

