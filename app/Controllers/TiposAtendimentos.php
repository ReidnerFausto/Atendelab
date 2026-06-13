<?php
// Controler da entidade de TipoAtendimento
// Sera responsavel pela criacao dos diferentes tipos de atendimentos

class TiposAtendimentos
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

    public function listarTipoAtendimento(): void
    {
        $sql = 'SELECT id, nome, descricao, status
                FROM tipos_atendimentos
                ORDER BY id DESC';

        $stmt = $this->pdo->query($sql);
        $tipos_atendimentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->jsonResponse($tipos_atendimentos);
    }

    public function buscarAtendimento(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->jsonResponse(['erro' => 'ID invalido'], 400);
        }

        $sql = 'SELECT id, nome, descricao, status
                FROM tipos_atendimentos
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $tipos_atendimentos = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$tipos_atendimentos) {
            $this->jsonResponse(['erro' => 'Tipo de atendimentos não encontrado.'], 404);
        }
        $this->jsonResponse($tipos_atendimentos, 200);
    }

    public function criarTipoAtendimento(): void
    {
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $status = $_POST['status'] ?? 'ativo';

        if ($nome === '') {
            $this->jsonResponse(['erro' => 'Nome do atendimento é obrigatorio'], 400);
        }

        try {
            $sql = 'INSERT INTO tipos_atendimentos(nome, descricao, status)
                    VALUES (:nome, :descricao, :status)';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->bindValue(':status', $status);
            $stmt->execute();

            $this->jsonResponse(['mensagem' => 'Tipo de Atendimento cadastrado com sucesso.'], 201);
        } catch (PDOException $e) {
            $this->jsonResponse(['erro' => 'Erro ao cadastrar Tipo de Atendimento'], 500);

        }
    }

    public function atualizarAtendimento(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $status = $_POST['status'] ?? 'ativo';

        if (!$id) {
            $this->jsonResponse(['erro' => 'ID é obrigatorio.'], 400);
        }
        if (!in_array($status, ['ativo', 'inativo'], true)) {
            $this->jsonResponse(['erro' => 'Status invalido.'], 400);
        }

        try {
            $sql = 'UPDATE tipos_atendimentos
                    SET nome = :nome,
                        descricao = :descricao,
                        status = :status
                    WHERE id = :id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->jsonResponse(['mensagem' => 'Tipo de atendimento atualizado com sucesso']);
        } catch (PDOException $e) {
            $this->jsonResponse(['erro' => 'Erro ao atualizar tipo de atendimento.'], 500);
        }
    }

    public function excluirAtendimento(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->jsonResponse(['erro' => 'ID inválido'], 400);
        }

        try {
            $sql = 'DELETE FROM tipos_atendimentos WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->jsonResponse(['mensagem' => 'Tipo de atendimento excluido com sucesso']);
        } catch (PDOException $e) {
            $this->jsonResponse(['erro' => 'Erro ao deletar tipo de atendimento'], 500);
        }
    }
}
