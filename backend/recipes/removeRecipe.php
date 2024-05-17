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
        $czyJestWlascicielem = $walidacja->sprawdzCzyJestWlascicielemPrzepisu($id_uzytkownika, $inputData->id ?? "");

        if (!$id_przepisu["isDataCorrect"]) {
            $outputData["errors"]["id"] = $id_przepisu["errors"];
        }

        if(!$czyJestWlascicielem || !$czyJestWlascicielem) {
            $outputData["errors"]["id_uzytkownika"] = "Podany użytkownik nie jest włascicielem tego przepisu!";
        }

        if ($id_przepisu["isDataCorrect"] && $czyJestWlascicielem && $isTokenCorrect) {
            $id_przepisu = $id_przepisu["data"];

            $result = $connection->query("DELETE FROM przepisy WHERE id='$id_przepisu'");
            
            $outputData["success"] = true;
        }

        echo json_encode($outputData);
    } 
}

header("Content-Type: application/json");
$usunPrzepis = new UsunPrzepis();
$usunPrzepis->usunPrzepis();

