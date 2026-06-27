<?php
$tituloPagina = "Dashboard";
require __DIR__ . "/../layout/header.php";
?>

<div class="container mt-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h1 class="h3 mb-1">Dashboard</h1>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p class="mb-1">
                Bem Vindo, <strong><?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?> </strong>.
            </p>
            <p class="text-muted mb-0">Você possui acesso de nivel:
                <strong>
                    <?= htmlspecialchars($usuario['perfil'], ENT_QUOTES, 'UTF-8') ?>
                </strong>
            </p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small">Pessoas Cadastradas</div>
                    <div class="display-6 fw-semibold" id="totalPessoas">-</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small">Tipos de Atendimentos</div>
                    <div class="display-6 fw-semibold" id="totalTipos">-</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small">Atendimentos Registrados</div>
                    <div class="display-6 fw-semibold" id="totalAtendimentos">-</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h5">Acesso rápido</h2>
            <p class="text-secondary">Use os módulos abaixo para cadastrar e consultar dados reais do banco.</p>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-success" href="<?= $baseUrl ?>?controller=frontend&action=pessoas">Gerenciar
                    pessoas</a>
                <a class="btn btn-outline-success" href="<?= $baseUrl ?>?controller=frontend&action=tipos">Gerenciar
                    tipos</a>
                <a class="btn btn-outline-success"
                    href="<?= $baseUrl ?>?controller=frontend&action=atendimentos">Gerenciar
                    atendimentos</a>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const requisicoes = [
            {
                controller: 'pessoas',
                action: 'listar',
                elemento: document.getElementById('totalPessoas')
            },
            {
                controller: 'tiposatendimentos',
                action: 'listarTipoAtendimento',
                elemento: document.getElementById('totalTipos')
            },
            {
                controller: 'atendimentos',
                action: 'listarAtendimentos',
                elemento: document.getElementById('totalAtendimentos')
            }
        ];
        for (const req of requisicoes) {
            try {
                const response = await AtendeLabApi.get(req.controller, req.action);
                // Correção: req.elemento em vez de req.element
                req.elemento.textContent = AtendeLabApi.toList(response).length;
            } catch (error) {
                // Correção: req.elemento nas duas linhas
                req.elemento.textContent = '!';
                req.elemento.title = error.message;
            }
        }
    });
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>