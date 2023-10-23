<?php
ini_set('display_errors', 1);
include 'NetUtils.php';

$rootDomain = '.epacific.net';
$cookieName = 'ai_open_access_token';
$cookieName2 = 'ai_open_refesh_token';

$username = "shayn@vinova.com.sg";
$password = '123';

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

?>

