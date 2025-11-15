<?php
use \App\DB\MySQL;
use \App\{Core, Auth, Config};
use \App\Api\RestApi as Api;

$sql = new MySQL();
$dash = new Core();
$config = new Config();
$auth = new Auth();
$api = new Api();
$core = $dash;

$type = 'api';
$types = $config->getTypes();
$menus = $config->getMenus();

$thisUriArray = explode('/', $_SERVER['REQUEST_URI']);
$api_version = $thisUriArray[2];

$authHeader = explode(' ', $api->getRequestHeaders()['Authorization']);

//if logged in and has bearer token, allow data access
if (
    $authHeader[0] == 'Bearer' &&
    $access_token = $authHeader[1]
) {
    $currentUser = $auth->getCurrentUser($access_token);
    include_once __DIR__ . '/' . $api_version . '/data.php';
} else if ( //authenticate if not logged in
    strtoupper($_SERVER['REQUEST_METHOD']) == 'POST' &&
    $authHeader[0] == 'Basic' &&
    ($userpass = explode(':', base64_decode($authHeader[1])))
) {
    include_once __DIR__ . "/$api_version/auth.php";
} else { //Access denied
    $api->send(401);
}
