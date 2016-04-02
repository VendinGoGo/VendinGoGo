<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include("connections.php");

getVendingLocations($conn);

function getVendingLocations($conn){
    $stmt = $conn->prepare("SELECT id, lat, lng, numOfMachines, createdOn FROM vendinglocation;");

    $stmt->execute();

    $stmt->bind_result($id, $lat, $lng, $numOfMachines, $createdOn);

    /* fetch values */
    $rows = array();
    while ($stmt->fetch()) {
        $row = array();

        $row['id'] = utf8_encode($id);
        $row['lat'] = utf8_encode($lat);
        $row['lng'] = utf8_encode($lng);
        $row['numOfMachines'] = utf8_encode($numOfMachines);
        $row['createdOn'] = utf8_encode($createdOn);

        $rows[] = $row;
    }

    print json_encode($rows);

    $stmt->close();
}

$conn->close();