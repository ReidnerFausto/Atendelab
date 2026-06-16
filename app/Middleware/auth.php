<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function usuarioAutenticado(): bool
{
    return isset($_SESSION['usuario']) && is_array($_SESSION['usuario']);
}

function exigirAutenticacao(): void
{
    if (!usuarioAutenticado()) {
        $_SESSION['mensagem'] = ['Faca login para acessar a area restrita.'];

        header('Location: ?controller=auth&action=login');
        exit;
    }
}

// Precisa criar essa funcao caso contrario fica retornando para o html no postman
function exigirAutenticacaoApi(): void
{
    // Se o usuário não estiver logado, encerra e devolve um erro JSON 401
    if (!usuarioAutenticado()) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(401); // Unauthorized
        echo json_encode([
            'erro' => 'Acesso negado. Autenticação necessária.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

function usuarioAtual(): ?array
{
    return $_SESSION['usuario'] ?? null;
}