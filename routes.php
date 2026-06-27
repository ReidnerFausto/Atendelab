<?php
//Carrega o controller responsável pelos endpoints de usuários.
require_once __DIR__ . '/app/Controllers/UsuariosController.php';
require_once __DIR__ . '/app/Controllers/AtendimentosController.php';
require_once __DIR__ . '/app/Controllers/PessoasController.php';
require_once __DIR__ . '/app/Controllers/TiposAtendimentosController.php';
require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Controllers/FrontEndController.php';
require_once __DIR__ . '/app/Controllers/DashboardController.php';
require_once __DIR__ . '/app/Middleware/auth.php';

// Define o controller e action por query string.
// Ex: ?controller=usuarios&action=listar
$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

switch ($controller) {
    case 'auth':
        $authController = new AuthController();

        // Escolhe qual método do controller executar
        switch ($action) {
            case 'login':
                $authController->exibirLogin();
                break;
            case 'entrar':
                $authController->entrar();
                break;
            case 'dashboard':
                $authController->dashboard();
                break;
            case 'logout':
                $authController->logout();
                break;

            default:
                // Retorno padrão para action invalido
                http_response_code(404);
                echo 'Ação de Autenticação não encontrada.';
                break;
        }
        break;

    case 'frontend':
        $frontEndController = new FrontEndController();

        // Escolhe qual método do controller executar
        switch ($action) {
            case 'pessoas':
                $frontEndController->pessoas();
                break;
            case 'tipos':
                $frontEndController->tipos();
                break;
            case 'atendimentos':
                $frontEndController->atendimentos();
                break;
            default:
                // Retorno padrão para action invalido
                http_response_code(404);
                echo 'Ação de Autenticação não encontrada.';
                break;
        }
        break;


    case 'usuarios':
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
            case 'alterarStatus':
                $usuariosController->alterarStatus();
                break;
            case 'excluir':
                $usuariosController->excluir();
                break;
            default:
                // Retorno padrão para action invalido
                http_response_code(404);
                echo 'Ação de usuários não encontrada.';
                break;
        }
        break;

    case 'tiposatendimentos':
        $tiposAtendimentosController = new TiposAtendimentosController();

        switch ($action) {
            case 'criarTipoAtendimento':
                $tiposAtendimentosController->criarTipoAtendimento();
                break;
            case 'listarTipoAtendimento':
                $tiposAtendimentosController->listarTipoAtendimento();
                break;
            case 'buscarAtendimento':
                $tiposAtendimentosController->buscarAtendimento();
                break;
            case 'atualizarAtendimento':
                $tiposAtendimentosController->atualizarAtendimento();
                break;
            case 'inativarTipoAtendimento':
                $tiposAtendimentosController->inativarTipoAtendimento();
                break;
            case 'excluirAtendimento':
                $tiposAtendimentosController->excluirAtendimento();
                break;
            default:
                // Retorno padrão para action invalido
                http_response_code(404);
                echo 'Ação não encontrada.';
                break;
        }
        break;

    case 'pessoas':
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
            case 'inativar':
                $pessoasController->inativar();
                break;
            case 'excluir':
                $pessoasController->excluir();
                break;
            default:
                // Retorno padrão para action invalido
                http_response_code(404);
                echo 'Ação não encontrada.';
                break;
        }
        break;

    case 'atendimentos':
        $atendimentosController = new AtendimentosController();

        switch ($action) {
            case 'listarAtendimentos':
                $atendimentosController->listarAtendimentos();
                break;
            case 'buscarAtendimento':
                $atendimentosController->buscarAtendimento();
                break;
            case 'criarNovoAtendimento':
                $atendimentosController->criarNovoAtendimento();
                break;
            case 'atualizarAtendimento':
                $atendimentosController->atualizarAtendimento();
                break;
            case 'alterarStatus':
                $atendimentosController->alterarStatus();
                break;
            case 'excluirAtendimento':
                $atendimentosController->excluirAtendimento();
                break;
            default:
                // Retorno padrão para action invalido
                http_response_code(404);
                echo 'Ação não encontrada.';
                break;
        }
        break;

    default:
        http_response_code(404);
        echo 'Controller não encontrado.';
        break;
}