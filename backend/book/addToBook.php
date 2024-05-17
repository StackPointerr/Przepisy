<?php
require_once("../classes/Database.php");
require_once("../classes/Walidacja.php");
require_once("../classes/Tokens.php");

class UsunPrzepis {
    public function usunPrzepis(): void {
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

            try {
                $result = $connection->query("INSERT INTO ksiazka VALUES(NULL, '$id_uzytkownika', '$id_przepisu')");
                $outputData["success"] = true;
            } catch (\Throwable $th) {
                $outputData["errors"]["exists"] = "Wybrany przepis jest już posiadany w twojej książce kucharskiej!";
            }
        }

        echo json_encode($outputData);
    } 
}

header("Content-Type: application/json");
$usunPrzepis = new UsunPrzepis();
$usunPrzepis->usunPrzepis();

