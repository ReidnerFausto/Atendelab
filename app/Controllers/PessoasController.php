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
        // Consulta todos as pessoas com ordenação decrescente por ID
        $sql = 'SELECT id, nome, documento, telefone, curso, periodo, status
                FROM pessoas
                ORDER BY id DESC';

        $stmt = $this->pdo->query($sql);
        $pessoas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->jsonResponse($pessoas);

    }
    public function buscarPorId(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->jsonResponse(['erro' => 'ID invalido'], 400);
        }

        $sql = 'SELECT id, nome, documento, telefone, curso, periodo, status
                FROM pessoas
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $pessoas = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pessoas) {
            $this->jsonResponse(['erro' => 'Pessoa não encontrado.'], 404);
        }
        $this->jsonResponse($pessoas, 200);
    }

    public function cadastrar(): void
    {
        $nome = trim($_POST['nome'] ?? '');
        $documento = trim($_POST['documento'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $curso = trim($_POST['curso'] ?? '');
        $periodo = trim($_POST['periodo'] ?? '');
        $status = $_POST['status'] ?? 'ativo';

        // Regras minimas de validacção de entrada
        if ($nome === '' || $documento === '' || $curso === '') {
            $this->jsonResponse(['erro' => 'Nome, documento e curso são obrigatorios.'], 400);
        }

        //Whitelist de valores válidos para campo de domínio
        if (!in_array($status, ['ativo', 'inativo'], true)) {
            $this->jsonResponse(['erro' => 'Status invalido.'], 400);
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

            $this->jsonResponse(['mensagem' => 'Pessoa Cadastrada com sucesso', 'id' => $this->pdo->lastInsertId()], 201);
        } catch (PDOException $e) {
            // Em produção, registre $e em log em vez de expor detalhes.
            $this->jsonResponse(['erro' => 'Erro ao cadastrar pessoa'], 500);
        }
    }
    public function atualizar(): void
    {
        // ID vem no POST para operação de update.
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nome = trim($_POST['nome'] ?? '');
        $documento = trim($_POST['documento'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $curso = trim($_POST['curso'] ?? '');
        $periodo = trim($_POST['periodo'] ?? '');
        $status = $_POST['status'] ?? 'ativo';

        if (!$id || $nome === '' || $documento === '') {
            $this->jsonResponse(['erro' => 'ID, nome e documento são obrigatorios.'], 400);
        }
        if (!in_array($status, ['ativo', 'inativo'], true)) {
            $this->jsonResponse(['erro' => 'Status invalido.'], 400);
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

            $this->jsonResponse(['mensagem' => 'Pessoa atualizada com sucesso']);

        } catch (PDOException $e) {
            $this->jsonResponse(['erro' => 'Erro ao atualizar pessoa'], 500);
        }

    }
    public function excluir(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->jsonResponse(['erro' => 'ID inválido'], 400);
        }

        try {
            $sql = 'DELETE FROM pessoas WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->jsonResponse(['mensagem' => 'Pessoa excluida com sucesso']);
        } catch (PDOException $e) {
            $this->jsonResponse(['erro' => 'Erro ao deletar pessoa'], 500);
        }
    }
}