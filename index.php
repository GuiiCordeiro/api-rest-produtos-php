<?php

// Carrega as classes do banco e dos produtos.
require_once "config/Database.php";
require_once "models/Produto.php";
require_once "helpers/validarProduto.php";


// Define o formato JSON e a codificação da resposta.
header("Content-Type: application/json; charset=utf-8");

// Identifica o método HTTP da requisição.
$metodo = $_SERVER["REQUEST_METHOD"];

// Extrai o caminho da URL, sem os parâmetros.
$rota = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

try{

    // Cria a instância do banco de dados.
    $banco = new Database();

    // Abre a conexão com o banco.
    $conexao = $banco->conectar();

    // Cria o modelo de produtos com a conexão.
    $produto = new Produto($conexao);

    if ($metodo === "GET" && $rota === "/api-produtos/") {
        // Retorna a mensagem inicial da API.

        echo json_encode([
            "mensagem" => "Bem-vindo à API de Produtos!"
        ], JSON_UNESCAPED_UNICODE);

    } elseif ($metodo === "GET" && $rota === "/api-produtos/produtos") {

        // Busca todos os produtos e retorna em JSON.
        $produtos = $produto->listar();
        echo json_encode($produtos, JSON_UNESCAPED_UNICODE);

    } elseif ($metodo === "GET" && preg_match("#^/api-produtos/produtos/([0-9]+)$#", $rota, $matches)) {

        // Extrai o ID da URL e converte para inteiro.
        $id = (int) $matches[1];

        // Busca o produto pelo ID informado.
        $produtoEncontrado = $produto->buscarPorId($id);

        // Retorna o produto ou informa que não foi encontrado.
        if ($produtoEncontrado) {
            echo json_encode($produtoEncontrado, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(["erro" => "Produto não encontrado"], JSON_UNESCAPED_UNICODE);
        } 

    } elseif ($metodo === "POST" && $rota === "/api-produtos/produtos") {
        // Trata a requisição de cadastro de produto.

        // Lê o corpo enviado na requisição.
        $json = file_get_contents("php://input");

        // Converte o JSON recebido em um array.
        $dados = json_decode($json, true);

        // Rejeita dados que não foram convertidos em array.
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($dados)) {   
            http_response_code(400);

            echo json_encode([
                "erro" => "JSON inválido"
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }  
        
        // Chama a função que válida os campos de nome, preço e estoque
        $erro = validarProduto($dados);
        if ($erro !== null) {
            http_response_code(400);
            
            echo json_encode([
                "erro" => $erro
                ], JSON_UNESCAPED_UNICODE);
                
            exit;
        }

        // Recupera os dados após a validação.
        $nome = $dados["nome"] ?? null;
        $preco = $dados["preco"] ?? null;
        $estoque = $dados["estoque"] ?? null;

        // Salva o produto e recebe o ID gerado.
        $id = $produto->cadastrar(
            trim($nome),
            (float) $preco,
            $estoque
        );

        // Informa que o produto foi criado com sucesso.
        http_response_code(201);

        echo json_encode([
            "mensagem" => "Produto cadastrado com sucesso",
            "id" => (int) $id
        ], JSON_UNESCAPED_UNICODE);

    } elseif ($metodo === "PUT" && preg_match("#^/api-produtos/produtos/([0-9]+)$#", $rota, $matches)) {
        // Trata a requisição de atualização de produto.

        // Extrai o ID da URL e converte para inteiro.
        $id = (int) $matches[1];

        // Lê o corpo enviado na requisição.
        $json = file_get_contents("php://input");
        // Converte o JSON recebido em um array.
        $dados = json_decode($json, true); 

        // Rejeita dados que não foram convertidos em array.
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($dados)) {
            http_response_code(400);
            echo json_encode(["erro" => "JSON inválido"], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Chama a função que válida os campos de nome, preço e estoque
        $erro = validarProduto($dados);

        if ($erro !== null) {
            http_response_code(400);

            echo json_encode([
                "erro" => $erro
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // Recupera os dados após a validação.
        $nome = $dados["nome"];
        $preco = $dados["preco"];
        $estoque = $dados["estoque"];

        // Verifica se o produto existe antes de atualizar.
        $produtoExistente = $produto->buscarPorId($id);

        // Retorna erro 404 se o produto não existir.
        if (!$produtoExistente) {
            http_response_code(404);

            echo json_encode([
                "erro" => "Produto não encontrado"
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // Atualiza os dados do produto no banco.
        $produto->atualizar(
            $id,
            trim($nome),
            (float) $preco,
            $estoque
        );

        echo json_encode([
            "mensagem" => "Produto atualizado com sucesso"
        ], JSON_UNESCAPED_UNICODE);

    } elseif ($metodo === "DELETE" && preg_match("#^/api-produtos/produtos/([0-9]+)$#", $rota, $matches)) {

        $id = (int) $matches[1];

        $produtoExistente = $produto->buscarPorId($id);

        if (!$produtoExistente) {
            http_response_code(404);

            echo json_encode([
                "erro" => "Produto não encontrado"
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $produto->excluir($id);
        echo json_encode([
            "mensagem" => "Produto excluído com sucesso"
        ], JSON_UNESCAPED_UNICODE);

    }else{
        // Retorna erro 404 para rotas ou métodos não atendidos.
        
        http_response_code(404);

        echo json_encode([
            "erro" => "Rota não encontrada"
        ], JSON_UNESCAPED_UNICODE);
        
    }
  
} catch (Throwable $erro) {

    error_log($erro);
    http_response_code(500);

    echo json_encode([
        "erro" => "Erro interno do servidor"
    ], JSON_UNESCAPED_UNICODE);

}
