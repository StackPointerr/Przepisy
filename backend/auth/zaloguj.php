<?php
require_once("../classes/Database.php");
require_once("../classes/Walidacja.php");
require_once("../classes/Tokens.php");

class Zaloguj {
    public function __construct() {
        
    }

    public function zaloguj(): void {
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

            $result = $connection->query("SELECT * FROM uzytkownicy WHERE nazwa_uzytkownika='$username' AND haslo='$password'");

            if ($result->num_rows) {
                $tokenManager = new TokenManager($connection);
                $token = $tokenManager->generateToken();
                
                $id = $result->fetch_assoc()["id"];
                $connection->query("INSERT INTO tokeny VALUES($id, '$token', DEFAULT)"); 

                $outputData["success"] = true;
                $outputData["user_id"] = $id;
                $outputData["token"] = $token;
            } else {
                $outputData["errors"]["form"] = "Login lub hasło jest niepoprawne!";
            }
        }

        echo json_encode($outputData);
    } 
}

header("Content-Type: application/json");
$zaloguj = new Zaloguj();
$zaloguj->zaloguj();
