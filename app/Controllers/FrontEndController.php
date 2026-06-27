<?php

// Importa a conexão com o banco de dados
require_once __DIR__ . "/../../config/database.php";

// Importa funções auxiliares de autenticação e sessão
require_once __DIR__ . "/../Middleware/auth.php";


class FrontEndController
{
    function pessoas()
    {

        //Exige autenticacao
        exigirAutenticacaoApi();
        // Titulo da pagina caso necessario
        $tituloPagina = "Pessoas";

        //Caminho para o arquivo do front end
        $caminho = __DIR__ . "/../Views/pessoas/index.php";

        if (file_exists($caminho)) {
            require_once $caminho;
        } else {
            http_response_code(404);
            echo "Erro: O arquivo de front-end de pessoas não foi encontrado em: " . $caminho;
        }
    }

    function tipos()
    {

        //Exige autenticacao
        exigirAutenticacaoApi();
        // Titulo da pagina caso necessario
        $tituloPagina = "tipos";

        //Caminho para o arquivo do front end
        $caminho = __DIR__ . "/../Views/tipos-atendimentos/index.php";

        if (file_exists($caminho)) {
            require_once $caminho;
        } else {
            http_response_code(404);
            echo "Erro: O arquivo de front-end de tipos de atendimento não foi encontrado em: " . $caminho;
        }
    }

    function atendimentos()
    {

        //Exige autenticacao
        exigirAutenticacaoApi();
        // Titulo da pagina caso necessario
        $tituloPagina = "atendimentos";

        //Caminho para o arquivo do front end
        $caminho = __DIR__ . "/../Views/atendimentos/index.php";

        if (file_exists($caminho)) {
            require_once $caminho;
        } else {
            http_response_code(404);
            echo "Erro: O arquivo de front-end de atendimentos não foi encontrado em: " . $caminho;
        }
    }
}