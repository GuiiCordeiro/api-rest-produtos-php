<?php

function validarProduto($dados) {

    $nome = $dados["nome"] ?? null;
    $preco = $dados["preco"] ?? null;
    $estoque = $dados["estoque"] ?? null;

    if (!is_string($nome) || trim($nome) === "") {
        return "Nome inválido";
    }

    if (mb_strlen(trim($nome), "UTF-8") > 100) {
        return "O nome deve ter no máximo 100 caracteres";
    }

    if (!is_numeric($preco) || $preco <= 0) {
        return "O preço deve ser maior que 0";
    }

    if (!is_int($estoque) || $estoque < 0) {
        return "O estoque deve ser um número inteiro maior ou igual a 0";
    }

    return null;
}