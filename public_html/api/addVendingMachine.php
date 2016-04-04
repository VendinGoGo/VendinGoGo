<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include("connections.php");

$stmt = $conn->prepare("INSERT INTO `vendinglocation` (`lat`, `lng`, `submittedBy`, `numOfMachines`, `howToFind`) VALUES (?, ?, 0, ?, ?)");

$lat = filter_input(INPUT_GET, "lat", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$lng = filter_input(INPUT_GET, "lng", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$numOfMachines = filter_input(INPUT_GET, "m", FILTER_SANITIZE_NUMBER_INT);
$howToFind = filter_input(INPUT_GET, "w", FILTER_SANITIZE_STRING);

$stmt->bind_param("ddis", $lat, $lng, $numOfMachines, $howToFind);

if (!$stmt->execute()) {
    die('{"result": "failure"}');
} else {
    echo '{"result": "success"}';
}

$conn->close();