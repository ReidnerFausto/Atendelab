<?php
// Controller da entidade de usuários.
// Em uma arquitetura MVC, ele recebe a requisição, valida e acessa o banco.

class UsuariosController
{
    // Conexão PDO reutilizada em todos os métodos.
    private PDO $pdo;

    public function __construct()
    {
        //Importa o arquivo que inicializa o objeto $pdo.
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }


    // Centraliza as chamadas em Json abstraindo e tornando o codigo mais limpo
    public function jsonResponse(mixed $data, int $status = 200): void
    {
        // Define saída em JSON para APIs/consumo por fron-end.
        header('Content-Type: application/json; charset=utf-8');
        // Status Code que sera retornado
        http_response_code($status);
        // JSON_PRETTY_PRINT melhora leitura em desenvolvimento
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function listar(): void
    {
        // Consulta todos os usuários com ordenação decrescente por ID
        $sql = 'SELECT id, nome, email, perfil, status, criado_em
                FROM usuarios
                ORDER BY id DESC';

        $stmt = $this->pdo->query($sql);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $this->jsonResponse($usuarios);
    }

    public function buscarPorId(): void
    {
        // Lê e valida o ID recebido por GET.
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->jsonResponse(['erro' => 'ID invalido'], 400);
        }

        // Consulta parametrizada evita SQL injection
        $sql = 'SELECT id, nome, email, perfil, status, criado_em
                FROM usuarios
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $usuarios = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuarios) {
            $this->jsonResponse(['erro' => 'Usuário não encontrado.'], 404);
        }

        echo json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    }
    public function criar(): void
    {
        // Define saída em JSON para APIs/consumo por fron-end.
        header('Content-Type: application/json; charset=utf-8');

        // Coleta dados do formulario (POST)
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $perfil = $_POST['perfil'] ?? 'atendente';
        $status = $_POST['status'] ?? 'ativo';

        // Regras minimas de validacção de entrada
        if ($nome === '' || $email === '' || $senha === '') {
            $this->jsonResponse(['erro' => 'Nome, e-mail e senha são obrigatorios.'], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['erro' => 'E-mail invalido.'], 400);
        }

        //Whitelist de valores válidos para campo de domínio
        if (!in_array($perfil, ['admin', 'aluno', 'atendente'], true)) {
            $this->jsonResponse(['erro' => 'Perfil inválido.'], 400);
        }

        if (!in_array($status, ['ativo', 'inativo'], true)) {
            $this->jsonResponse(['erro' => 'Status Invalido'], 400);
        }

        // Nunca armazenar senha em texto puro
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        try {
            $sql = 'INSERT INTO usuarios (nome, email, senha, perfil, status)
                    VALUES (:nome, :email, :senha, :perfil, :status)';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':senha', $senhaHash);
            $stmt->bindValue(':perfil', $perfil);
            $stmt->bindValue(':status', $status);
            $stmt->execute();

            $this->jsonResponse(['mensagem' => 'Usuário cadastrado com sucesso', 'id' => $this->pdo->lastInsertId()], 201);
        } catch (PDOException $e) {
            // Em produção, registre $e em log em vez de expor detalhes.
            $this->jsonResponse(['erro' => 'Erro ao cadastrar usuário'], 500);
        }
    }
    public function atualizar(): void
    {
        // ID vem no POST para operação de update.
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $perfil = $_POST['perfil'] ?? 'atendente';
        $status = $_POST['status'] ?? 'ativo';

        if (!$id || $nome === '' || $email === '') {
            $this->jsonResponse(['erro' => 'ID, nome e e-mail são obrigatorios'], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['erro' => 'E-mail invalido'], 400);

        }
        if (!in_array($perfil, ['admin', 'aluno', 'atendente'], true)) {
            $this->jsonResponse(['erro' => 'Perfil inválido.'], 400);
        }
        if (!in_array($status, ['ativo', 'inativo'], true)) {
            $this->jsonResponse(['erro' => 'Status invalido'], 400);
        }

        try {
            $sql = 'UPDATE usuarios
                    SET nome = :nome,
                        email = :email,
                        perfil = :perfil,
                        status = :status
                    WHERE id = :id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':perfil', $perfil);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->jsonResponse(['mensagem' => 'Usuário atualizado com sucesso']);

        } catch (PDOException $e) {
            $this->jsonResponse(['erro' => 'Erro ao atualizar usuário'], 500);
        }
    }

    public function excluir(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->jsonResponse(['erro' => 'ID inválido'], 400);
        }

        try {
            $sql = 'DELETE FROM usuarios WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->jsonResponse(['mensagem' => 'Usuário excluido com sucesso']);
        } catch (PDOException $e) {
            $this->jsonResponse(['erro' => 'Erro ao deletar usuario'], 500);
        }
    }
}