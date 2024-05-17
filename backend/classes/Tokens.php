<?php
class TokenManager { 
    private mysqli $mysqli;

    public function __construct(mysqli $connection) {
        $this->mysqli = $connection;
    }

    public function generateToken(): string {
        $token = bin2hex(random_bytes(20));

        return $token;
    } 

    public function checkToken(string $token): bool {
        $token = $this->mysqli->escape_string($token);
        $result = $this->mysqli->query("SELECT * FROM tokeny WHERE token='$token'");
        
        if ($result->num_rows) {
            return true;
        } else {
            return false;
        }
    }

    public function invalidateToken(string $token): void {
        $result = $this->mysqli->query("DELETE FROM tokeny WHERE token='$token'");
    }

    public function getTokenOwnerId(string $token): string | bool {
        $token = $this->mysqli->escape_string($token);
        $result = $this->mysqli->query("SELECT * FROM tokeny WHERE token='$token'");

        if ($result->num_rows) {
            $row = $result->fetch_assoc();
            return $row["id_uzytkownika"];
        } else {
            return false;
        }
    }
}
