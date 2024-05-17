<?php
require_once("../classes/Database.php");
require_once("../classes/Walidacja.php");
require_once("../classes/Tokens.php");

class AddRecipe {
    public function addRecipe(): void {
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

        $tytul = $walidacja->walidujTytulPrzepisu($inputData->name ?? "");
        $tresc = $walidacja->walidujTrescPrzepisu($inputData->description ?? "");
        $opis_przygotowania = $walidacja->walidujTrescPrzepisu($inputData->preparation_description ?? "");
        $category = $walidacja->walidujIdKategori($inputData->category ?? "");

        $imagesInput = $inputData->image ?? null;
        $parsedImages = [];
    
        $photosCorrect = true;
        if ($imagesInput) {
            $imageData = base64_decode(preg_replace("#^data:image/\w+;base64,#i", '', $imagesInput));

            $imageType = $walidacja->czyZdjecie($imagesInput);
            if (!$imageType) {
                $photosCorrect = false;
            } else {
                array_push($parsedImages, ["imageData" => $imageData, "extension" => $imageType]);
            }
        } else {
            $photosCorrect = false;
        } 

        if (!$tytul["isDataCorrect"]) {
            $outputData["errors"]["title"] = $tytul["errors"];
        }

        if (!$tresc["isDataCorrect"]) {
            $outputData["errors"]["description"] = $tresc["errors"];
        }

        if (!$photosCorrect) {
            $outputData["errors"]["photos"] = "Upewnij sie że wybrany plik jest poprawnym zdjęciem, obsługiwane typy zdjeć to png, jpeg i gif";
        }

        if (!$category["isDataCorrect"]) {
            $outputData["errors"]["category"] = $category["errors"];
        }

        if (!$opis_przygotowania["isDataCorrect"]) {
            $outputData["errors"]["preparation_description"] = $opis_przygotowania["errors"];
        }

        if ($tytul["isDataCorrect"] && $tresc["isDataCorrect"] && $isTokenCorrect && $photosCorrect && $category["isDataCorrect"] && $opis_przygotowania["isDataCorrect"]) {
            $token = $inputData->token;
            $user_id = $tokenManager->getTokenOwnerId($token);
            $tytul = $tytul["data"];
            $tresc = $tresc["data"];
            $category = $category["data"];
            $opis_przygotowania = $opis_przygotowania["data"];

            foreach ($parsedImages as $index => $photo) {
                $sciezka = "zdjecia/" . uniqid() . "." . $photo["extension"];
                $filename = "../" . $sciezka;

                file_put_contents($filename, $photo["imageData"]);
            }
            
            $result = $connection->query("INSERT INTO przepisy VALUES(NULL, $user_id, '$tytul', '$tresc', '$opis_przygotowania', '$sciezka', '$category', DEFAULT)");

            $outputData["success"] = true;
        }

        echo json_encode($outputData);
    } 
}

header("Content-Type: application/json");
$addRecipe = new AddRecipe();
$addRecipe->addRecipe();

