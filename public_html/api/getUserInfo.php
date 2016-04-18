<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include("connections.php");


$uId = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);


if ($uId !== null && $uId !== 0) {

    getUserInfo($conn, $uId);
}

function getUserInfo($conn, $id) {
    $row = array();

    $row['locations'] = getUserLocations($conn, $id);
    $row['statuses'] = getUserStatuses($conn, $id);

    print json_encode($row);
}

function getUserLocations($conn, $id) {

    $stmt = $conn->prepare("SELECT id, lat, lng, numOfMachines, howToFind FROM vendinglocation WHERE submittedBy = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        $stmt->bind_result($id, $lat, $lng, $numOfMachines, $howToFind);


        $rows = array();


        /* fetch values */
        while ($stmt->fetch()) {
            $row = array();

            $row['id'] = utf8_encode($id);
            $row['lat'] = utf8_encode($lat);
            $row['lng'] = utf8_encode($lng);
            $row['numOfMachines'] = utf8_encode($numOfMachines);
            $row['howToFind'] = utf8_encode($howToFind);

            $rows[] = $row;
        }

        $stmt->close();

        return $rows;
    }

    return "None";
}

function getUserStatuses($conn, $id) {

    $stmt = $conn->prepare("select id, vendingId, comment, date FROM statuses WHERE userId = ?;");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        $stmt->bind_result($id, $vendId, $comment, $date);


        $rows = array();

        /* fetch values */
        while ($stmt->fetch()) {

            $row = array();

            $row['id'] = utf8_encode($id);
            $row['vendId'] = utf8_encode($vendId);
            $row['comment'] = utf8_encode($comment);
            $row['date'] = utf8_encode($date);

            $rows[] = $row;
        }

        $stmt->close();

        return $rows;
    }

    return "None";
}
