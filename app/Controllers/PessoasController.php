<?php
// Controler da entidade de pessoas
// Sera responsavel pelo cadastro e acompanhamento das pessoas atendidadas

class PessoasController
{
    private PDO $pdo;

    public function __construct()
    {
        //Importa o arquivo que inicializa o objeto $pdo.
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    public function listar(): void
    {
        // Define saída em JSON para APIs/consumo por fron-end.
        header('Content-Type: application/json; charset=utf-8');

        // Consulta todos as pessoas com ordenação decrescente por ID
        $sql = 'SELECT id, nome, documento, telefone, curso, periodo, status
                FROM pessoas
                ORDER BY id DESC';

        $stmt = $this->pdo->query($sql);
        $pessoas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($pessoas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    }
    public function buscarPorId(): void
    {
        // Define saída em JSON para APIs/consumo por fron-end.
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID invalido']);
            return;
        }

        $sql = 'SELECT id, nome, documento, telefone, curso, periodo, status
                FROM pessoas
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $pessoas = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pessoas) {
            http_response_code(404);
            echo json_encode(['erro' => 'Pessoa não encontrado.']);
            return;
        }
        echo json_encode($pessoas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function cadastrar(): void
    {
        // Define saída em JSON para APIs/consumo por fron-end.
        header('Content-Type: application/json; charset=utf-8');

        $nome = trim($_POST['nome'] ?? '');
        $documento = trim($_POST['documento'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $curso = trim($_POST['curso'] ?? '');
        $periodo = trim($_POST['periodo'] ?? '');
        $status = $_POST['status'] ?? 'ativo';

        // Regras minimas de validacção de entrada
        if ($nome === '' || $documento === '' || $curso === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'Nome, documento e curso são obrigatorios.']);
            return;
        }

        //Whitelist de valores válidos para campo de domínio
        if (!in_array($status, ['ativo', 'inativo'], true)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Status Invalido']);
            return;
        }

        try {
            $sql = 'INSERT INTO pessoas(nome, documento, telefone, curso, periodo, status)
                    VALUES (:nome, :documento, :telefone, :curso, :periodo, :status)';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':documento', $documento);
            $stmt->bindValue(':telefone', $telefone);
            $stmt->bindValue(':curso', $curso);
            $stmt->bindValue(':periodo', $periodo);
            $stmt->bindValue(':status', $status);
            $stmt->execute();

            http_response_code(201);
            echo json_encode(['mensagem' => 'Pessoa Cadastrada com sucesso', 'id' => $this->pdo->lastInsertId()], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            // Em produção, registre $e em log em vez de expor detalhes.
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao cadastrar pessoa']);
        }
    }
    public function atualizar(): void
    {
        // Define saída em JSON para APIs/consumo por fron-end.
        header('Content-Type: application/json; charset=utf-8');

        // ID vem no POST para operação de update.
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nome = trim($_POST['nome'] ?? '');
        $documento = trim($_POST['documento'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $curso = trim($_POST['curso'] ?? '');
        $periodo = trim($_POST['periodo'] ?? '');
        $status = $_POST['status'] ?? 'ativo';

        if (!$id || $nome === '' || $documento === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'ID, nome e documento são obrigatorios']);
            return;
        }
        if (!in_array($status, ['ativo', 'inativo'], true)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Status invalido']);
            return;
        }

        try {
            $sql = 'UPDATE pessoas
                    SET nome = :nome,
                        documento = :documento,
                        telefone = :telefone,
                        curso = :curso,
                        periodo = :periodo,
                        status = :status
                    WHERE id = :id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':documento', $documento);
            $stmt->bindValue(':telefone', $telefone);
            $stmt->bindValue(':curso', $curso);
            $stmt->bindValue(':periodo', $periodo);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['mensagem' => 'Pessoa atualizada com sucesso'], JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao atualizar pessoa']);
        }

    }
    public function excluir(): void
    {
        // Define saída em JSON para APIs/consumo por fron-end.
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido']);
            return;
        }

        try {
            $sql = 'DELETE FROM pessoas WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['mensagem' => 'Pessoa excluida com sucesso'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao deletar pessoa']);
        }
    }
}