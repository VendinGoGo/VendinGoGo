<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include("connections.php");

// TODO: change this to defend against mysql injection

$result = $conn->query("INSERT INTO `vendinglocation` (`lat`, `lng`, `submittedBy`, `numOfMachines`, `howToFind`) VALUES ('".$_GET["lat"]."', '".$_GET["lng"]."', '0', '".$_GET["m"]."', '".$_GET["w"]."')");
if (!$result) {
    die('{"result": "failure"}');
} else {
    echo '{"result": "success"}';

}

$conn->close();