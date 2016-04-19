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

$stmt = $conn->prepare("INSERT INTO `vendinglocation` (`lat`, `lng`, `submittedBy`, `numOfMachines`, `howToFind`) VALUES (?, ?, ?, ?, ?)");

$lat = filter_input(INPUT_GET, "lat", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$lng = filter_input(INPUT_GET, "lng", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$numOfMachines = min(max(filter_input(INPUT_GET, "m", FILTER_SANITIZE_NUMBER_INT), 1), 9);
$howToFind = filter_input(INPUT_GET, "w", FILTER_SANITIZE_STRING);

$stmt->bind_param("ddiis", $lat, $lng, $_SESSION['access_token']['user_id'], $numOfMachines, $howToFind);

if (!$stmt->execute()) {
    die('{"result": "failure", "reason": "'.$stmt->error.'"}');
} else {
    
    
    echo '{"result": "success",';
    echo '"id":'.$stmt->insert_id;
    echo '}';
}

$conn->close();