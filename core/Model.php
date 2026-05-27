<?php

namespace Config;

use PDO;
use PDOException;

class Database{
    private $host = 'localhost';
    private $db_name = 'boutique-rl';
    private $username = 'root';
    private $password = '';

    private ?PDO $connection = null;

    public function getConnection(): PDO{
        if($this->connection === null){
            try{
                $this->connection = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
                
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch(PDOException $e){
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }
        return $this->connection;
    }
}