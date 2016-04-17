<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();

if (!isset($_SESSION['access_token'])) {
    die('{"result": "failure", "reason":"Your not logged in"}');
    
}

include("connections.php");

$stmt = $conn->prepare("INSERT INTO `statuses` (`userId`, `vendingId`,`comment`) VALUES (?, ? ,?)");

$id = filter_input(INPUT_GET, "id");
$comment =  filter_input(INPUT_GET, "comment");

$stmt->bind_param("iis", $_SESSION['access_token']['user_id'], $id, $comment);
$dib = $stmt->execute();

if (!$dib) {
    die('{"result": "failure"}');
} else {
    
    echo '{"result": "success"}';
}

$conn->close();