<?php
if (!isset($_SESSION)) session_start();
require_once("query.php");
require_once('fb/autoload.php');

$fb = new Facebook\Facebook([
  'app_id' => '665949463585099',
  'app_secret' => '068f93bd72388961d7e8c3e3d6140831',
  'default_graph_version' => 'v2.2',
  ]);

$helper = $fb->getJavaScriptHelper();

try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  if (!empty($_SESSION["godMode"]) && $_SESSION["godMode"] === 1) echo 'Graph returned an error: ' . $e->getMessage();
  else header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista7/login.php");
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  if (!empty($_SESSION["godMode"]) && $_SESSION["godMode"] === 1) echo 'Facebook SDK returned an error: ' . $e->getMessage();
  else header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista7/login.php");  
  exit;
}

if (!isset($accessToken)) {
  if (!empty($_SESSION["godMode"]) && $_SESSION["godMode"] === 1) echo 'No cookie set or no OAuth data could be obtained from cookie.';
  exit;
}

try {
  $response = $fb->get('/me?fields=id', $accessToken);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  if (!empty($_SESSION["godMode"]) && $_SESSION["godMode"] === 1) echo 'Graph returned an error: ' . $e->getMessage();
  else header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista7/login.php");  
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  if (!empty($_SESSION["godMode"]) && $_SESSION["godMode"] === 1) echo 'Facebook SDK returned an error: ' . $e->getMessage();
  else header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista7/login.php");  
  exit;
}

$user = $response->getGraphUser();
$answer = ifExists($user['id'], "fb");

if ($answer == 1) {
  $_SESSION['fb_access_token'] = (string)$accessToken;
  $data = getLoginAndPassword($user['id'], "fb");
  $_SESSION['login'] = $data['login'];
  $_SESSION['pass'] = $data['pass'];
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista7/index.php");
} else {
  setcookie("fbErr", "1", time()+10);
  session_unset();
  session_destroy();
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista7/login.php");
  exit;
}

?>
