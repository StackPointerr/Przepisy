<?php
class WalidatorReturnStatus {
    public bool $isDataCorrect;
    public $data;
    public array $errors;
}

class Walidacja {
    private mysqli $connection;

    public function __construct(mysqli $connection) {
        $this->connection = $connection;
    }

    public function walidujNazweUzytkownika(string $username): array {
        $returnData = ["isDataCorrect" => true];

        $username = trim($username);

        if(strlen($username) < 3 || strlen($username) > 20) {
            $returnData["isDataCorrect"] = false;
            $returnData["errors"] = "Nazwa użytkownika musi posiadać od 3 do 20 znaków!";
        }

        if ($returnData["isDataCorrect"]) {
            $returnData["data"] = $this->connection->escape_string($username);
        }
        
        return $returnData;
    }

    public function walidujHaslo(string $password): array {
        $returnData = ["isDataCorrect" => true];

        $password = trim($password);

        if ((strlen($password) < 8) || (strlen($password) > 20)) {
            $returnData["isDataCorrect"] = false;
            $returnData["errors"] = "Hasło musi zawierać od 8 do 20 znaków!";
        }

        if ($returnData["isDataCorrect"]) {
            $returnData["data"] = $this->connection->escape_string($password);
        }
        
        return $returnData;
    }

    public function walidujEmail(string $email): array {
        $returnData = ["isDataCorrect" => true];

        $email = trim($email);
        
        $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

        if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($email != $emailB)) {
            $returnData["isDataCorrect"] = false;
            $returnData["errors"] = "Adres E-mail jest niepoprawny!";
        }

        if($returnData["isDataCorrect"]) {
            $returnData["data"] = $this->connection->escape_string($email);
        }

        return $returnData;
    }

    public function walidujTytulPrzepisu(string $tytul): array {
        $returnData = ["isDataCorrect" => true];

        $tytul = trim($tytul);
        
        if (!strlen($tytul)) {
            $returnData["isDataCorrect"] = false;
            $returnData["errors"] = "Podaj tytuł przepisu!";
        } else if (strlen($tytul) <= 5) {
            $returnData["isDataCorrect"] = false;
            $returnData["errors"] = "Tytuł przepisu powinien zawierać conajmniej 5 znaków!";
        } else if (strlen($tytul) >= 50) {
            $returnData["isDataCorrect"] = false;
            $returnData["errors"] = "Tytuł przepisu powinien być nie dłuższy niż 50 znaków!";
        } 
            
        if($returnData["isDataCorrect"]) {
            $returnData["data"] = $this->connection->escape_string($tytul);
        }

        return $returnData;
    }

    public function walidujTrescPrzepisu(string $tresc): array {
        $returnData = ["isDataCorrect" => true];
        
        $tresc = trim($tresc);

        if (!strlen($tresc)) {
            $returnData["isDataCorrect"] = false;
            $returnData["errors"] = "Podaj treść przepisu!";
        } else if (strlen($tresc) <= 5) {
            $returnData["isDataCorrect"] = false;
            $returnData["errors"] = "Treść przepisu powinna zawierać conajmniej 5 znaków!";
        } else if (strlen($tresc) >= 500) {
            $returnData["isDataCorrect"] = false;
            $returnData["errors"] = "Treść przepisu powinna być nie dłuższa niż 500 znaków!";
        } 
            
        if($returnData["isDataCorrect"]) {
            $returnData["data"] = $this->connection->escape_string($tresc);
        }

        return $returnData;
    }

    public function walidujTrescKomentarza(string $komentarz): array {
        $returnData = ["isDataCorrect" => true];

        $komentarz = trim($komentarz);
        
        if (!strlen($komentarz)) {
            $returnData["isDataCorrect"] = false;
            $returnData["errors"][] = "Wpisz komentarz!";
        }
            
        if($returnData["isDataCorrect"]) {
            $returnData["data"] = $this->connection->escape_string($komentarz);
        }

        return $returnData;
    }

    public function walidujIdPrzepisu(string $id_przepisu): array {
        $returnData = ["isDataCorrect" => true];
        $id_przepisu = $this->connection->escape_string($id_przepisu);

        $result = $this->connection->query("SELECT * FROM przepisy WHERE id='$id_przepisu'");
        if (!$result->num_rows) {
            $returnData["isDataCorrect"] = false;
            $returnData["errors"] = "Podane id jest niepoprawne!"; 
        }

        if($returnData["isDataCorrect"]) {
            $returnData["data"] = $id_przepisu;
        }

        return $returnData;
    }

    public function walidujIdKategori(string $id_kategori): array {
        $returnData = ["isDataCorrect" => true];
        $id_kategori = $this->connection->escape_string($id_kategori);

        $result = $this->connection->query("SELECT * FROM kategorie WHERE id='$id_kategori'");
        if (!$result->num_rows) {
            $returnData["isDataCorrect"] = false;
            $returnData["errors"] = "Podane id jest niepoprawne!"; 
        }

        if($returnData["isDataCorrect"]) {
            $returnData["data"] = $id_kategori;
        }

        return $returnData;
    }
    public function walidujIdKsiazki(string $id_ksiazki): array {
        $returnData = ["isDataCorrect" => true];
        $id_ksiazki = $this->connection->escape_string($id_ksiazki);

        $result = $this->connection->query("SELECT * FROM kategorie WHERE id='$id_ksiazki'");
        if (!$result->num_rows) {
            $returnData["isDataCorrect"] = false;
            $returnData["errors"] = "Podane id jest niepoprawne!"; 
        }

        if($returnData["isDataCorrect"]) {
            $returnData["data"] = $id_ksiazki;
        }

        return $returnData;
    }

    public function sprawdzCzyJestWlascicielemPosta(string $id_uzytkownika, string $id_posta): bool {
        $id_uzytkownika = $this->connection->escape_string($id_uzytkownika);
        $id_posta = $this->connection->escape_string($id_posta);

        $result = $this->connection->query("SELECT * FROM posts WHERE id='$id_posta' AND id_uzytkownika='$id_uzytkownika'");

        if(!$result->num_rows) {
            return false;
        } else {
            return true;
        }
    }

    public function sprawdzCzyJestWlascicielemPrzepisu(string $id_uzytkownika, string $id_przepisu): bool {
        $id_uzytkownika = $this->connection->escape_string($id_uzytkownika);
        $id_przepisu = $this->connection->escape_string($id_przepisu);

        $result = $this->connection->query("SELECT * FROM przepisy WHERE id='$id_przepisu' AND autor='$id_uzytkownika'");

        if(!$result->num_rows) {
            return false;
        } else {
            return true;
        }
    }

    public function czyAdmin(string $id_uzytkownika): bool {
        $id_uzytkownika = $this->connection->escape_string($id_uzytkownika);

        $result = $this->connection->query("SELECT * FROM users WHERE id='$id_uzytkownika' AND admin=TRUE");
        if (!$result->num_rows) {
            return false;
        } else {
            return true;
        }
    }

    public function czyZdjecie(string $path)
    {
        $supported = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];
        $type = exif_imagetype($path);

        if (!in_array($type, $supported)) {
            return false;
        }

        $image = false;
        $extension = false;
        switch ($type) {
            case IMAGETYPE_GIF:
                $image = @imagecreatefromgif($path);
                $extension = "gif";
                break;
            case IMAGETYPE_PNG:
                $image = @imagecreatefrompng($path);
                $extension = "png";
                break;
            case IMAGETYPE_JPEG:
                $image = @imagecreatefromjpeg($path);
                $extension = "jpeg";
                break;
        }

        if (!!$image) {
            return $extension;
        } else {
            return (!!$image);
        }
    }

    public function walidujSzukane(string $szukane): string
    {
        $szukane = trim($szukane);
        $szukane = $this->connection->escape_string($szukane);

        return $szukane;
    }
}
