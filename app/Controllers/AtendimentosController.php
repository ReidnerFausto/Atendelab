<?php
// Controler da entidade de Atendimentos
// Sera responsavel pela criacao e agendamento de novos atendimentos

class AtendimentosController
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
        // Status Code que sera retornado, por padrao sera 200 mas pode ser alterado ao ser chamado dentro das funcoes
        http_response_code($status);
        // JSON_PRETTY_PRINT melhora leitura em desenvolvimento
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function listarAtendimentos(): void
    {
        $sql = 'SELECT a.id, a.data_atendimento, a.hora_atendimento, a.descricao, a.observacao, a.status, a.criado_em, p.nome AS pessoa_nome, u.nome AS usuario_id, t.nome AS tipo_atendimento 
                FROM atendimentos a
                INNER JOIN pessoas p ON a.pessoa_id = p.id
                INNER JOIN usuarios u ON a.usuario_id = u.id
                INNER JOIN tipos_atendimentos t ON a.tipo_atendimento = t.id
                ORDER BY a.id DESC';

        $stmt = $this->pdo->query($sql);
        $atendimentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->jsonResponse($atendimentos);
    }

    public function buscarAtendimento(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->jsonResponse(['erro' => 'ID invalido'], 400);
        }

        $sql = 'SELECT id, pessoa_id, usuario_id, data_atendimento, hora_atendimento,   descricao, observacao, status, criado_em
                FROM atendimentos
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $atendimentos = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$atendimentos) {
            $this->jsonResponse(['erro' => 'Atendimento não encontrado.'], 404);
        }
        $this->jsonResponse($atendimentos, 200);
    }

    public function criarNovoAtendimento(): void
    {
        // Forca o valor a ser int caso seja texto ou valores invalidos retorna false
        $pessoa_id = filter_input(INPUT_POST, 'pessoa_id', FILTER_VALIDATE_INT);
        $tipo_atendimento = filter_input(INPUT_POST, 'tipo_atendimento', FILTER_VALIDATE_INT);
        $usuario_id = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);

        // Limpeza de string como nos outros controllers
        $data_atendimento = trim($_POST['data_atendimento'] ?? '');
        $hora_atendimento = trim($_POST['hora_atendimento'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $observacao = trim($_POST['observacao'] ?? '');
        $status = $_POST['status'] ?? 'ativo';

        if (!$pessoa_id || !$tipo_atendimento || !$usuario_id || $data_atendimento === '') {
            $this->jsonResponse([
                'erro' => 'Os campos pessoa, tipo de atendimento, usuario e data de atendimento são obrigatórios.'
            ], 400);
        }
        try {
            $sql = 'INSERT INTO atendimentos (pessoa_id, tipo_atendimento, usuario_id, data_atendimento, hora_atendimento, descricao, observacao, status)
                    VALUES (:pessoa_id, :tipo_atendimento, :usuario_id, :data_atendimento, :hora_atendimento, :descricao, :observacao, :status)';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':pessoa_id', $pessoa_id, PDO::PARAM_INT);
            $stmt->bindValue(':tipo_atendimento', $tipo_atendimento, PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->bindValue(':data_atendimento', $data_atendimento);
            $stmt->bindValue(':hora_atendimento', $hora_atendimento);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->bindValue(':observacao', $observacao);
            $stmt->bindValue(':status', $status);
            $stmt->execute();

            $this->jsonResponse(['mensagem' => 'Atendimento cadastrado com sucesso.'], 201);
        } catch (PDOException $e) {
            $this->jsonResponse(['erro' => 'Erro ao cadastrar o Atendimento'], 500);

        }
    }

    public function atualizarAtendimento(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $pessoa_id = filter_input(INPUT_POST, 'pessoa_id', FILTER_VALIDATE_INT);
        $tipo_atendimento = filter_input(INPUT_POST, 'tipo_atendimento', FILTER_VALIDATE_INT);
        $usuario_id = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
        $data_atendimento = trim($_POST['data_atendimento'] ?? '');
        $hora_atendimento = trim($_POST['hora_atendimento'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $observacao = trim($_POST['observacao'] ?? '');
        $status = $_POST['status'] ?? 'ativo';

        if (!$id || $pessoa_id === false || $usuario_id === false) {
            $this->jsonResponse(['erro' => 'ID do atendimento, ID de pessoa e ID de usuário são obrigatorios.'], 400);
        }
        if (!in_array($status, ['ativo', 'inativo'], true)) {
            $this->jsonResponse(['erro' => 'Status invalido.'], 400);
        }

        try {
            $sql = 'UPDATE atendimentos
                    SET pessoa_id = :pessoa_id,
                        tipo_atendimento = :tipo_atendimento,
                        usuario_id = :usuario_id,
                        data_atendimento = :data_atendimento,
                        hora_atendimento = :hora_atendimento,
                        descricao = :descricao,
                        observacao = :observacao,
                        status = :status
                    WHERE id = :id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':pessoa_id', $pessoa_id, PDO::PARAM_INT);
            $stmt->bindValue(':tipo_atendimento', $tipo_atendimento, PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->bindValue(':data_atendimento', $data_atendimento);
            $stmt->bindValue(':hora_atendimento', $hora_atendimento);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->bindValue(':observacao', $observacao);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT); // Precisa bindar o ID
            $stmt->execute();

            $this->jsonResponse(['mensagem' => 'Atendimento atualizado com sucesso']);


        } catch (PDOException $e) {
            $this->jsonResponse(['erro' => 'Erro ao atualizar atendimento'], 500);

        }
    }

    public function excluirAtendimento(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->jsonResponse(['erro' => 'ID inválido'], 400);
        }
        try {
            $sql = 'DELETE FROM atendimentos WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->jsonResponse(['mensagem' => 'Atendimento excluida com sucesso']);
        } catch (PDOException $e) {
            $this->jsonResponse(['erro' => 'Erro ao deletar atendimento'], 500);
        }
    }
}

