<?php
require_once("../classes/Database.php");
require_once("../classes/Walidacja.php");
require_once("../classes/Tokens.php");

class ZwrocKsiazke {
    public function zwrocKsiazke(): void {
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
        $id_uzytkownika = $tokenManager->getTokenOwnerId($token);

        if ($isTokenCorrect) {
            $result = $connection->query("SELECT przepisy.id, nazwa AS name, opis AS description, zdjecie AS image_url, uzytkownicy.nazwa_uzytkownika AS author_username FROM ksiazka INNER JOIN przepisy ON id_przepisu=przepisy.id INNER JOIN uzytkownicy ON id_uzytkownika=uzytkownicy.id WHERE id_uzytkownika='$id_uzytkownika'");

            while ($row = $result->fetch_assoc()) {
                $outputData["data"][] = $row;
            }
            
            $outputData["success"] = true;
        }

        echo json_encode($outputData);
    } 
}

header("Content-Type: application/json");
$zwrocKsiazke = new ZwrocKsiazke();
$zwrocKsiazke->zwrocKsiazke();