<?php

header("Access-Control-Allow-Origin: *");

include "../config/Connection.php";
$conn = new Connection();
$pdo = $conn->getConn();

$contactId = $_GET["id"];

$sql = "SELECT a.name, a.surname, a.email,
               b.state_initials, b.city, b.neighborhood, b.zip_code, b.public_area,
               b.number
        FROM contact a
        LEFT JOIN address b ON a.contact_id = b.contact_id
        WHERE a.contact_id = :contact_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":contact_id", $contactId);
$stmt->execute();

$contact = $stmt->fetch();

$sql = "SELECT phone_number
        FROM phone
        WHERE contact_id = :contact_id
        ORDER BY main_phone DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":contact_id", $contactId);
$stmt->execute();

$rs = $stmt->fetchAll();

$phones =  array();

foreach ($rs as $r) {
    array_push($phones, $r["phone_number"]);
}

$data = array(
    "name" => $contact["name"],
    "surname" => $contact["surname"],
    "email" => $contact["email"],
    "state_initials" => $contact["state_initials"],
    "city" => $contact["city"],
    "neighborhood" => $contact["neighborhood"],
    "zip_code" => $contact["zip_code"],
    "public_area" => $contact["public_area"],
    "number" => $contact["number"],
    "phones" => $phones
);

echo json_encode($data);
