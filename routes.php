<?php
//Carrega o controller responsável pelos endpoints de usuários.
require_once __DIR__ . '/app/Controllers/UsuariosController.php';
require_once __DIR__ . '/app/Controllers/PessoasController.php';
require_once __DIR__ . '/app/Controllers/TiposAtendimentos.php';

// Define o controller e action por query string.
// Ex: ?controller=usuarios&action=listar
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Este roteador é simples: só reconhece o controller "usuarios.
if ($controller === 'usuarios') {
    $usuariosController = new UsuariosController();

    // Escolhe qual método do controller executar
    switch ($action) {
        case 'listar':
            $usuariosController->listar();
            break;

        case 'buscar':
            $usuariosController->buscarPorId();
            break;

        case 'criar':
            $usuariosController->criar();
            break;

        case 'atualizar':
            $usuariosController->atualizar();
            break;

        case 'excluir':
            $usuariosController->excluir();
            break;

        default:
            // Retorno padrão para action invalido
            echo 'Ação de usuários não encontrada.';
            break;
    }
} elseif ($controller === 'tiposatendimentos') {
    $tiposAtendimentos = new TiposAtendimentos();

    switch ($action) {
        case 'criarTipoAtendimento':
            $tiposAtendimentos->criarTipoAtendimento();
            break;
        case 'buscarAtendimento':
            $tiposAtendimentos->buscarAtendimento();
            break;
        case 'atualizarAtendimento':
            $tiposAtendimentos->atualizarAtendimento();
            break;
        case 'excluirAtendimento':
            $tiposAtendimentos->excluirAtendimento();
            break;
        default:
            // Retorno padrão para action invalido
            echo 'Ação não encontrada.';
            break;
    }
} elseif ($controller === 'pessoas') {
    $pessoasController = new PessoasController();

    switch ($action) {
        case 'listar':
            $pessoasController->listar();
            break;
        case 'buscar':
            $pessoasController->buscarPorId();
            break;
        case 'cadastrar':
            $pessoasController->cadastrar();
            break;
        case 'atualizar':
            $pessoasController->atualizar();
            break;
        case 'excluir':
            $pessoasController->excluir();
            break;
        default:
            // Retorno padrão para action invalido
            echo 'Ação não encontrada.';
            break;
    }

} else {
    // Resposta básica para indicar que a aplicação está no ar.
    echo '<h1>AtendeLabb</h1>';
    echo '<p>Projeto em execução, Use ?controller=usuarios&action=listar para testar</p>';
}