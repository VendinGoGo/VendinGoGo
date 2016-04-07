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

define('CONSUMER_KEY', 'zPyBq87D3vU3HChwsoViwvxCC');
define('CONSUMER_SECRET', 'BSJWSrEqRHjA5j6a6dgLwRNFqQlbxOoF51ze0GkDPSHq6Ns6qg');
define('OAUTH_CALLBACK', 'http://vendingogo.com/index.html');

if (!isset($_SESSION['access_token'])) {
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
    $_SESSION['oauth_token'] = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token'];
    $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
    echo $url;
} else {
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $user = $connection->get("account/verify_credentials");
    echo $user->screen_name;
}
