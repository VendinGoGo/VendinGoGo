<?php

include("connections.php");

$vId = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if ($vId !== null && $vId !== 0) {

    getVendingInfo($conn, $vId);
    
}

function getVendingInfo($conn, $vendingId){
    $stmt = $conn->prepare("SELECT id, lat, lng, numOfMachines, howToFind FROM vendinglocation WHERE id = ?;");
    $stmt->bind_param("i", $vendingId);

    if($stmt->execute()){

        $stmt->bind_result($id, $lat, $lng, $numOfMachines, $howToFind);

        $row = array();

        /* fetch values */
        while ($stmt->fetch()) {

            $row['id'] = utf8_encode($id);
            $row['lat'] = utf8_encode($lat);
            $row['lng'] = utf8_encode($lng);
            $row['numOfMachines'] = utf8_encode($numOfMachines);
            $row['howToFind'] = utf8_encode($howToFind);

        }

        $stmt->close();

        $row['statuses'] = getVendingStatus($conn, $vendingId);

        print json_encode($row);

    } else {
        echo '{"result": "failure"}';
    }
    
}


function getVendingStatus($conn, $id){
    
    $stmt = $conn->prepare("SELECT id, userId, comment, date FROM statuses WHERE vendingId = ?  ORDER BY date DESC;");
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
    
        $stmt->bind_result($statusId, $userId, $comment, $date);

        /* fetch values */
        $rows = array();
        while ($stmt->fetch()) {
            $row = array();

            $row['statusId'] = utf8_encode($statusId);
            $row['userId'] = utf8_encode($userId);
            $row['comment'] = utf8_encode($comment);
            $row['date'] = utf8_encode($date);

            $rows[] = $row;
        }

        $stmt->close();
        
        return $rows;
    }
    
    return "";
}

$conn->close();
