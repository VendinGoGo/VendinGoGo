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

    $stmt = $conn->prepare("SELECT id, name, signup FROM users WHERE id = ?");

    if($stmt === False){
        return '{"result": "failure", "reason":"'.$conn->error.'"}';
    }
    
    $stmt->bind_param("i", $id);
    

    if ($stmt->execute()) {

        $stmt->bind_result($uid, $username, $signup);

        $stmt->fetch();
        $row['id'] = utf8_encode($uid);
        $row['username'] = utf8_encode($username);
        $row['signup'] = utf8_encode($signup);
        
        $stmt->close();

    } else {

        $reason = $stmt->error;
        $stmt->close();

        return '{"result": "failure", "reason":"'.$reason.'"}';

    }
    
    
    $row['locations'] = getUserLocations($conn, $id);
    $row['statuses'] = getUserStatuses($conn, $id);

    print json_encode($row);
}


/**
 * Grabs all vending locations in which the user has submitted
 * Returns an array if succesful.
 * Retruns a json string if unseccesful.
 * 
 * @param type $conn mysqli connection
 * @param type $id id of the user
 * @return string || array
 */
function getUserLocations($conn, $id) {

    $stmt = $conn->prepare("SELECT id, lat, lng, numOfMachines, howToFind FROM vendinglocation WHERE submittedBy = ?");
    
    if($stmt === False){
        return '{"result": "failure", "reason":"'.$conn->error.'"}';
    }
    
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
        
    } else {

        $reason = $stmt->error;
        $stmt->close();

        return '{"result": "failure", "reason":"'.$reason.'"}';

    }

    return "None";
}


function getUserStatuses($conn, $id) {

    $stmt = $conn->prepare("select id, vendingId, comment, date FROM statuses WHERE userId = ?;");
    
    if($stmt === False){
        return '{"result": "failure", "reason":"'.$conn->error.'"}';
    }
    
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
    } else {

        $reason = $stmt->error;
        $stmt->close();

        return '{"result": "failure", "reason":"'.$reason.'"}';

    }

    return "None";
}
