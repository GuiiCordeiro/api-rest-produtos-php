<?php

class Database {

    private $host = "localhost";
    private $dbname = "api_produtos";
    private $username = "root";
    private $password = "";

    public function conectar() {
    try {
        $pdo = new PDO(
            "mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4",
            $this->username,
            $this->password
        );

        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        return $pdo;

    } catch (PDOException $erro) {
        throw new RuntimeException(
            "Não foi possível conectar ao banco de dados.",
            0,
            $erro
        );
    }
}

}