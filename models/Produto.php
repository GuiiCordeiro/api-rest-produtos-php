<?php

class Produto {

    private PDO $conexao;

    public function __construct(PDO $conexaoRecebida) {
        $this->conexao = $conexaoRecebida;
    }

    public function listar() {

        $sql = "SELECT * FROM produtos";

        $stmt = $this->conexao->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id) {

        $sql = "SELECT * FROM produtos WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->execute(["id" => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cadastrar(string $nome, float $preco, int $estoque) {

        $sql = "INSERT INTO produtos (nome, preco, estoque)
        VALUES (:nome, :preco, :estoque)";

        $stmt = $this->conexao->prepare($sql);

        $stmt->execute([
            "nome" => $nome,
            "preco" => $preco,
            "estoque" => $estoque
        ]);

        return $this->conexao->lastInsertId();
    }

    public function atualizar(int $id, string $nome, float $preco, int $estoque) {

        $sql = "UPDATE produtos
        SET nome = :nome, preco = :preco, estoque = :estoque
        WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        return $stmt->execute([
            "id" => $id,
            "nome" => $nome,
            "preco" => $preco,
            "estoque" => $estoque
        ]);
    }

    public function excluir(int $id) {

        $sql = "DELETE FROM produtos WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        return $stmt->execute([
            "id" => $id
        ]);
    }

}

