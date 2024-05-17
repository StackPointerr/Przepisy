<?php
require_once("../classes/Database.php");
require_once("../classes/Walidacja.php");
require_once("../classes/Tokens.php");

class UsunZKsiazki {
    public function usun(): void {
        $inputData = json_decode(file_get_contents("php://input"));
        $outputData = ["success" => false, "errors" => []];

        $conn = new Database();
        $connection = $conn->getConnection();
        $walidacja = new Walidacja($connection);

        $tokenManager = new TokenManager($connection);
        $isTokenCorrect = $tokenManager->checkToken($inputData->token ?? "");

        if (!$isTokenCorrect) {
            $outputData["errors"]["token"] = "Token jest nie poprawny!";
        }

        $token = $inputData->token ?? "";
        $id_przepisu = $walidacja->walidujIdPrzepisu($inputData->id ?? "");
        $id_uzytkownika = $tokenManager->getTokenOwnerId($token);

        if (!$id_przepisu["isDataCorrect"]) {
            $outputData["errors"]["id"] = $id_przepisu["errors"];
        }

        if ($id_przepisu["isDataCorrect"] && $isTokenCorrect) {
            $id_przepisu = $id_przepisu["data"];

            $result = $connection->query("DELETE FROM ksiazka WHERE id_przepisu='$id_przepisu' AND id_uzytkownika='$id_uzytkownika'");
            
            $outputData["success"] = true;
        }

        echo json_encode($outputData);
    } 
}

header("Content-Type: application/json");
$usun = new UsunZKsiazki();
$usun->usun();