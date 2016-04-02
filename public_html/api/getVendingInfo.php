<?php

include("connections.php");

$vId = (int)$_GET["id"];

if ($vId !== null && $vId !== 0) {

    getVendingInfo($conn, $vId);
    
}

function getVendingInfo($conn, $id){
    $stmt = $conn->prepare("SELECT lat, lng, numOfMachines, howToFind FROM vendinglocation WHERE id = ?;");

    $stmt->bind_param("i", $id);

    $dib = $stmt->execute();

    $stmt->bind_result($lat, $lng, $numOfMachines, $howToFind);


    /* fetch values */
    while ($stmt->fetch()) {
        $row = array();

        $row['lat'] = utf8_encode($lat);
        $row['lng'] = utf8_encode($lng);
        $row['numOfMachines'] = utf8_encode($numOfMachines);
        $row['howToFind'] = utf8_encode($howToFind);

        print json_encode($row);

    }

    $stmt->close();
}

$conn->close();
