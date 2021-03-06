<?php
/**
 * Created by PhpStorm.
 * User: Kaleb
 * Date: 4/3/2016
 * Time: 00:21
 */
session_start();
require "../../vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;
define('CONSUMER_KEY', getenv('CONSUMER_KEY'));
define('CONSUMER_SECRET', getenv('CONSUMER_SECRET'));
define('OAUTH_CALLBACK', getenv('CALLBACKURL'));

if (!isset($_SESSION['access_token'])) {
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
    $_SESSION['oauth_token'] = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token'];
    $url = $connection->url('oauth/authenticate', array('oauth_token' => $request_token['oauth_token']));
    echo '<script type="text/javascript"> window.location = "'.$url.'" </script>';
} else {
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $user = $connection->get("account/verify_credentials");
    $userName = $access_token['screen_name'];
    echo "<pre>";
    print_r($access_token);
    echo "</pre>";
}
