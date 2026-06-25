<?php
$tituloPagina = "Dashboard";
require __DIR__ . "/../layout/header.php";
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h1 class="h3 mb-1">Dashboard</h1>
</div>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <p class="mb-1">
                Bem Vindo, <strong><?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?> </strong>.
            </p>

            <p class="text-muted">Você possui acesso de nivel:
                <strong><?= htmlspecialchars($usuario['perfil'], ENT_QUOTES, 'UTF-8') ?> </strong>
            </p>
        </div>
    </div>
</div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const targets = {
            pessoas: document.getElementById('totalPessoas'),
            tipos: document.getElementById('totalTipos'),
            atendimentos: document.getElementById('totalAtendimentos')
        };

        for (const [controller, element] of Object.entries(targets)) {
            try {
                const response = await AtendeLabApi.get(controller, 'listar');
                element.textContent = AtendeLabApi.toList(response).length;
            } catch (error) {
                element.texContent = '!';
                element.title = error.message;
            }
        }
    });
</script>

</html>