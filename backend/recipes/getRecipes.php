<?php
require_once("../classes/Database.php");

class GetRecipes {
    public function getRecipes(): void {
        $inputData = json_decode(file_get_contents("php://input"));
        $outputData = ["success" => true, "errors" => []];

        $conn = new Database();
        $connection = $conn->getConnection();

        $nazwa = $connection->escape_string($inputData->name ?? "");
        $kategoria = $connection->escape_string($inputData->category ?? "kategoria");

        $kwerenda = "SELECT przepisy.id, nazwa AS name, opis AS description, zdjecie AS image_url, uzytkownicy.nazwa_uzytkownika AS author_username FROM przepisy INNER JOIN uzytkownicy ON autor=uzytkownicy.id WHERE nazwa LIKE '%$nazwa%'";
        if ($kategoria != "") $kwerenda = $kwerenda . " AND kategoria=$kategoria";

        $result = $connection->query($kwerenda);

        while ($row = $result->fetch_assoc()) {
            $outputData["data"][] = $row;
        }        

        echo json_encode($outputData);
    } 
}

header("Content-Type: application/json");
$getRecipes = new GetRecipes();
$getRecipes->getRecipes();