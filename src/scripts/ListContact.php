<?php

header("Access-Control-Allow-Origin: *");

include "../config/Connection.php";
$conn = new Connection();
$pdo = $conn->getConn();

$data = array();

$sql = "SELECT DISTINCT a.contact_id, (a.name || ' ' || a.surname) full_name, 
                        a.email, (SELECT phone_number 
                                  FROM phone 
                                  WHERE contact_id = a.contact_id
                                  ORDER BY main_phone DESC LIMIT 1) phone_number
        FROM contact a
        LEFT JOIN phone b
        ON a.contact_id = b.contact_id";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$rs = $stmt->fetchAll();

foreach ($rs as $r) {
    array_push($data, array(
        "contact_id" => $r["contact_id"],
        "full_name" => $r["full_name"],
        "email" => $r["email"],
        "phone_number" => $r["phone_number"]
    ));
}

echo json_encode($data);
