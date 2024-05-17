<?php
require_once("../classes/Database.php");
require_once("../classes/Walidacja.php");
require_once("../classes/Tokens.php");

class GetRecipe {
    public function getRecipe(): void {
        $inputData = json_decode(file_get_contents("php://input"));
        $outputData = ["success" => true, "errors" => []];

        $conn = new Database();
        $connection = $conn->getConnection();
        $walidacja = new Walidacja($connection);

        $tokenManager = new TokenManager($connection);
        $isTokenCorrect = $tokenManager->checkToken($inputData->token ?? "");

        if (!$isTokenCorrect) {
            $outputData["errors"]["token"] = "Token jest nie poprawny!";
        }

        $id = $walidacja->walidujIdPrzepisu($inputData->id ?? "");
        
        if (!$id["isDataCorrect"]) {
            $outputData["errors"]["id"] = $id["errors"];
        }

        if ($id["isDataCorrect"] && $isTokenCorrect) {
            $id = $id["data"];

            $result = $connection->query("SELECT przepisy.id, uzytkownicy.nazwa_uzytkownika AS author_username, przepisy.nazwa AS name, opis AS description, opis_przygotowania AS preparation_description, kategorie.nazwa AS category, zdjecie AS image_url, data_utworzenia AS creation_date FROM przepisy INNER JOIN uzytkownicy ON autor=uzytkownicy.id INNER JOIN kategorie ON kategoria=kategorie.id WHERE przepisy.id='$id'");

            $outputData["data"] = $result->fetch_assoc();
        }

        echo json_encode($outputData);
    } 
}

header("Content-Type: application/json");
$getRecipe = new GetRecipe();
$getRecipe->getRecipe();