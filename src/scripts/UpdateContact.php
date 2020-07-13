<?php

header("Access-Control-Allow-Origin: *");

include "../config/Connection.php";
$conn = new Connection();
$pdo = $conn->getConn();

try {
    $contactId =  $_GET["id"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $email = $_POST["email"];
    if (isset($_POST["state_initials"])) {
        $stateInitials = $_POST["state_initials"];
    }
    if (isset($_POST["city"])) {
        $city = $_POST["city"];
    }
    $neighborhood = $_POST["neighborhood"];
    $zipCode = $_POST["zip_code"];
    $publicArea = $_POST["public_area"];
    $number = $_POST["number"];
    if (isset($_POST['phones'])) {
        $phones = json_decode($_POST["phones"]);
    }

    if (empty($email)) {
        $email = null;
    }

    $sql = "SELECT * FROM contact
            WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue("email", $email);
    $stmt->execute();

    $rs = $stmt->fetch();

    $sql = "SELECT email FROM contact
            WHERE contact_id = :contact_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue("contact_id", $contactId);
    $stmt->execute();

    $selfEmail = $stmt->fetchColumn();

    if (!empty($name)) {
        if (($rs) && ($rs['email'] != $selfEmail)) {
            $data = array(
                "response" => 400,
                "message" => "Este e-mail já está cadastrado!",
            );
            echo json_encode($data);
        } else {

            // UPDATES contact
            $sql = "UPDATE contact SET
                           name = ?, 
                           surname = ?, 
                           email = ?
                     WHERE contact_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($name, $surname, $email, $contactId));

            // UPDATES address
            if (
                !empty($stateInitials) || !empty($city) || !empty($neighborhood) ||
                !empty($zipCode) || !empty($publicArea) || !empty($number)
            ) {
                $sql = "UPDATE address 
                        SET state_initials = ?,
                            city = ?,
                            neighborhood = ?,
                            zip_code = ?,
                            public_area = ?,
                            number = ?
                         WHERE contact_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    $stateInitials, $city, $neighborhood,
                    $zipCode, $publicArea, $number, $contactId
                ));
            }

            // UPDATES phone

            $sql = "DELETE FROM phone
                    WHERE contact_id = :contact_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue("contact_id", $contactId);
            $stmt->execute();

            $i = 0;

            foreach ($phones as $phone) {
                if (!empty($phone)) {


                    $sql = "INSERT INTO phone(contact_id, phone_number, main_phone)
                            VALUES(?, ?, 0)";

                    if ($i == 0) {
                        $sql = "INSERT INTO phone(contact_id, phone_number, main_phone)
                                VALUES(?, ?, 1)";
                    }
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array($contactId, $phone));

                    $i++;
                }
            }

            $data = array(
                "response" => 200,
                "message" => "Contato atualizado com sucesso!",
            );
            echo json_encode($data);
        }
    }
} catch (Exception $e) {
    $data = array(
        "response" => 500,
        "message" => "Erro na requisição ao servidor!",
    );
    echo json_encode($data);
}
