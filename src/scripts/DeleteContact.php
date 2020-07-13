<?php

header("Access-Control-Allow-Origin: *");

include "../config/Connection.php";
$conn = new Connection();
$pdo = $conn->getConn();

$contactId = $_GET["id"];

$sql = "DELETE
        FROM phone
        WHERE contact_id = :contact_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":contact_id", $contactId);
$stmt->execute();

$sql = "DELETE
        FROM address
        WHERE contact_id = :contact_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":contact_id", $contactId);
$stmt->execute();

$sql = "DELETE
        FROM contact
        WHERE contact_id = :contact_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":contact_id", $contactId);
$stmt->execute();
