<?php
ini_set('display_errors', 1);
include 'NetUtils.php';

$url = 'http://sim.net.vn/tools/toplogin/cookie.php';

$result = NetUtils::curlWithCookie($url, '', '', '', false, 120);

print_r($result);

?>
