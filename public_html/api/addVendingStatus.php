<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include("connections.php");

$stmt = $conn->prepare("INSERT INTO `statuses` (`userId`, `vendingId`,`comment`) VALUES ('1', ? ,?)");

$id = filter_input(INPUT_GET, "id");
$comment =  filter_input(INPUT_GET, "comment");

$stmt->bind_param("is", $id, $comment);
$dib = $stmt->execute();

if (!$dib) {
    die('{"result": "failure"}');
} else {
    
    echo '{"result": "success"}';
}

$conn->close();