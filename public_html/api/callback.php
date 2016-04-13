<?php
/**
 * Created by PhpStorm.
 * User: Kaleb
 * Date: 4/6/2016
 * Time: 01:09
 */

include("connections.php");
include 'environment.php';

define('CONSUMER_KEY', getenv('CONSUMER_KEY'));
define('CONSUMER_SECRET', getenv('CONSUMER_SECRET'));

session_start();

require '../../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

if (isset($_REQUEST['oauth_verifier'], $_REQUEST['oauth_token']) && $_REQUEST['oauth_token'] == $_SESSION['oauth_token']) {
    $request_token = [];
    $request_token['oauth_token'] = $_SESSION['oauth_token'];
    $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
    $access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
    $_SESSION['access_token'] = $access_token;
    
    addUserToDatabase($conn, $access_token);
    // redirect user back to index page
<<<<<<< HEAD
    header('Location: ../../');
}


function addUserToDatabase($conn, $access_token){
    
    if($access_token === NULL || $conn === NULL){
        return;
    }
    
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $user = $connection->get("account/verify_credentials");
    
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE id = ?");

    $stmt->bind_param("i", $user->id);
    $stmt->execute();
    $stmt->store_result();

    $addUser = $stmt->num_rows===0;
    
    $stmt->close();
    
    if($addUser){
        
        $stmt = $conn->prepare("INSERT INTO `users` (`id`, `name`, `oauth`) VALUES (?, ?, ?)");
        
        $stmt->bind_param("iss", $user->id, $user->name, $access_token['oauth_token']);
        
       if($stmt->execute()){
           echo "execute success!";
       } else {
           echo "execute failure!<br/>".$stmt->error;
       }
        
        $stmt->close();
    }
    

}
=======
    header('Location: ../../index.php');
}
>>>>>>> kaleb-dev
