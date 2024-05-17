<?php
require_once("../classes/Database.php");
require_once("../classes/Walidacja.php");
require_once("../classes/Tokens.php");

class Zarejestruj {
    public function zarejestruj(): void {
        $inputData = json_decode(file_get_contents("php://input"));
        $outputData = ["success" => false, "errors" => []];

        $conn = new Database();
        $connection = $conn->getConnection();
        $walidacja = new Walidacja($connection);

        $username = $walidacja->walidujNazweUzytkownika($inputData->username ?? "");
        $password = $walidacja->walidujHaslo($inputData->password ?? "");

        if (!$username["isDataCorrect"]) {
            $outputData["errors"]["username"] = $username["errors"];
        }

        if (!$password["isDataCorrect"]) {
            $outputData["errors"]["password"] = $password["errors"];
        }

        if ($username["isDataCorrect"] && $password["isDataCorrect"]) {
            $username = $username["data"];
            $password = $password["data"];

            $result = $connection->query("INSERT INTO uzytkownicy VALUES(NULL, '$username', '$password')");

            $tokenManager = new TokenManager($connection);
            $token = $tokenManager->generateToken();

            $id = $connection->insert_id;
            $connection->query("INSERT INTO tokeny VALUES($id, '$token', DEFAULT)"); 

            $outputData["success"] = true;
            $outputData["user_id"] = $id;
            $outputData["token"] = $token;
        }

        echo json_encode($outputData);
    } 
}

header("Content-Type: application/json");
$zarejestruj = new Zarejestruj();
$zarejestruj->zarejestruj();
