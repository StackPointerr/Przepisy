<?php
require_once("../classes/Database.php");

class GetCategories {
    public function getCategories(): void {
        $outputData = ["success" => true, "data" => []];

        $conn = new Database();
        $connection = $conn->getConnection();

        $result = $connection->query("SELECT * FROM kategorie");
        
        while ($row = $result->fetch_assoc()) {
            $outputData["data"][] = ["id" => $row["id"], "name" => $row["nazwa"]];
        }        
        
        echo json_encode($outputData);
    } 
}

header("Content-Type: application/json");
$getCategories = new GetCategories();
$getCategories->getCategories();