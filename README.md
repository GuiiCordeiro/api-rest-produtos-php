# API REST de Produtos

API REST desenvolvida em PHP puro para gerenciamento de produtos, utilizando programação orientada a objetos (POO), PDO e MySQL.

O projeto permite realizar as quatro operações básicas de um CRUD: cadastrar, consultar, atualizar e excluir produtos.

## Tecnologias utilizadas

- PHP
- MySQL
- PDO
- Programação Orientada a Objetos (POO)
- Apache (XAMPP)
- JSON
- HTTP

## Funcionalidades

- Listagem de produtos cadastrados
- Consulta de produtos por ID
- Cadastro de novos produtos
- Atualização de produtos existentes
- Exclusão de produtos
- Validação dos dados recebidos
- Tratamento de erros e respostas HTTP
- Utilização de consultas preparadas para maior segurança

## Endpoints da API

A API disponibiliza os seguintes endpoints:

| Método | Endpoint | Descrição |
|---|---|---|
| GET | `/produtos` | Lista todos os produtos |
| GET | `/produtos/{id}` | Consulta um produto pelo ID |
| POST | `/produtos` | Cadastra um novo produto |
| PUT | `/produtos/{id}` | Atualiza um produto existente |
| DELETE | `/produtos/{id}` | Exclui um produto |

**URL base:** `http://localhost/api-produtos`

### Exemplo de cadastro (POST)

**Requisição:**

```http
POST http://localhost/api-produtos/produtos
Content-Type: application/json
```

**Corpo JSON:**

```json
{
  "nome": "Mouse Gamer",
  "preco": 150.90,
  "estoque": 10
}
```

**Resposta de sucesso — HTTP 201:**

```json
{
  "mensagem": "Produto cadastrado com sucesso",
  "id": 1
}
```

O ID retornado é gerado automaticamente pelo banco de dados.

### Códigos HTTP utilizados

| Código | Significado |
|---|---|
| 200 | Requisição realizada com sucesso |
| 201 | Produto cadastrado com sucesso |
| 400 | Dados inválidos enviados pelo cliente |
| 404 | Produto ou rota não encontrado |
| 500 | Erro interno do servidor |

## Como executar o projeto

### Pré-requisitos

- PHP 8 ou superior
- MySQL ou MariaDB
- Apache com `mod_rewrite` habilitado
- XAMPP (recomendado para ambiente local)

### Instalação

1. Instale e configure o XAMPP.
2. Coloque a pasta `api-produtos` dentro do diretório `htdocs` do XAMPP.
3. Inicie os serviços Apache e MySQL no painel do XAMPP.
4. Acesse o phpMyAdmin em `http://localhost/phpmyadmin`.
5. Importe o arquivo `database.sql` para criar o banco de dados e a tabela de produtos.
6. Verifique as credenciais de conexão no arquivo `config/Database.php`, ajustando-as conforme seu ambiente.
7. Acesse a API pelo endereço:

   `http://localhost/api-produtos/produtos`

### Testando a API

O projeto inclui o arquivo `teste.http`, que contém exemplos de requisições GET, POST, PUT e DELETE.

Para utilizá-lo, instale a extensão **REST Client** no Visual Studio Code, abra o arquivo e clique em **Send Request** na requisição desejada.

Os testes incluem operações válidas, validações de campos e tratamento de erros.