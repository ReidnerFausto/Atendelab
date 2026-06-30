<?php
$tituloPagina = 'Pessoas atendidas';
require __DIR__ . '/../layout/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h3 mb-1">Pessoas atendidas</h1>
        <p class="text-secondary mb-0">Cadastro, edição e inativação sem excluir o histórico</p>
    </div>
    <button class="btn btn-success" type="button" onclick="novaPessoa()">Nova pessoa</button>
</div>

<div id="alerta"></div>

<div class="card border-0 shadow-sm mb-4 d-none" id="cardFormulario">
    <div class="card-body">
        <h2 class="h5" id="tituloFormulario">Nova pessoa</h2>
        <form id="formPessoa">
            <input type="hidden" name="id" id="pessoaId">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Nome *</label><input class="form-control" name="nome"
                        required></div>
                <div class="col-md-3"><label class="form-label">Documento *</label><input class="form-control"
                        autocomplete="off" maxlength="14" oninput="formatarDocumento(this)" name="documento" required>
                </div>
                <div class="col-md-3"><label class="form-label">Telefone</label><input class="form-control"
                        name="telefone" maxlength="15" oninput="formatarTelefone(this)" placeholder="(51)95151-5151">
                </div>
                <div class="col-md-3"><label class="form-label">Curso</label><input class="form-control" name="curso">
                </div>
                <div class="col-md-3"><label class="form-label">Período</label><input class="form-control"
                        name="periodo"></div>
                <div class="col-12"><label class="form-label">Observação</label><textarea class="form-control"
                        name="observacoes" rows="2"></textarea></div>
                <div class="col-md-3"><label class="form-label">Status</label><select class="form-select" name="status">
                        <option value="ativo">Ativo</option>
                        <option value="inativo">Inativo</option>
                    </select>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-success" type="submit">Salvar</button>
                    <button class="btn btn-outline-secondary" type="button"
                        onclick="fecharFormulario()">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Documento</th>
                    <th>Curso</th>
                    <th>Período</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody id="tabelaPessoas">
                <tr>
                    <td colspan="7" class="text-center py-4">Carregando...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    const formPessoa = document.getElementById('formPessoa');
    const cardFormulario = document.getElementById('cardFormulario');

    function abrirFormulario() {
        cardFormulario.classList.remove('d-none');
        window.scrollTo({ top: 0, behavior: 'smooth' })
    }
    function fecharFormulario() {
        cardFormulario.classList.add('d-none');
        formPessoa.reset();
        document.getElementById('pessoaId').value = '';
    }
    function novaPessoa() {
        fecharFormulario();
        document.getElementById('tituloFormulario').textContent = 'Nova pessoa';
        abrirFormulario();
    }


    async function carregarPessoas() {
        try {
            const dados = AtendeLabApi.toList(await AtendeLabApi.get('pessoas', 'listar'));
            const tbody = document.getElementById('tabelaPessoas');
            if (!dados.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4">Nenhuma pessoa cadastrada.</td></tr>'; return }
            tbody.innerHTML = dados.map(p => `<tr>
            <td>${AtendeLabApi.escape(p.nome)}</td>
            <td>${AtendeLabApi.escape(p.documento)}</td>
            <td>${AtendeLabApi.escape(p.curso || '')}</td>
            <td>${AtendeLabApi.escape(p.periodo || '')}</td>
            <td><span class="badge ${p.status === 'ativo' ? 'text-bg-success' : 'text-bg-secondary'}">${AtendeLabApi.escape(p.status)}</span></td>
            <td class="text-end"><button class="btn btn-sm btn-outline-primary" onclick="editarPessoa(${Number(p.id)})">Editar</button> <button class="btn btn-sm btn-outline-danger" onclick="inativarPessoa(${Number(p.id)})">Inativar </button> </td>
            </tr>`).join('');
        } catch (error) {
            AtendeLabApi.showAlert('alerta', error.message, 'danger');
        }
    }

    function formatarTelefone(input) {
        // Remove tudo o que não for número
        let v = input.value.replace(/\D/g, '');

        // Impede que o usuário digite mais de 11 números base
        if (v.length > 11) {
            v = v.substring(0, 11);
        }

        // Formatação progressiva
        v = v.replace(/^(\d{2})(\d)/g, '($1) $2'); // Isola o DDD em parênteses e adiciona um espaço
        v = v.replace(/(\d)(\d{4})$/, '$1-$2');    // Coloca o hífen sempre antes dos últimos 4 dígitos

        // Atualiza o valor do input
        input.value = v;
    }

    function formatarDocumento(input) {
        // Remove tudo o que não for número
        let v = input.value.replace(/\D/g, '');

        // Impede que o usuário digite mais de 14 números base
        if (v.length > 14) {
            v = v.substring(0, 14);
        }

        // Formatação progressiva
        v = v.replace(/(\d{3})(\d)/, '$1.$2'); // Adiciona o 1º ponto
        v = v.replace(/(\d{3})(\d)/, '$1.$2'); // Adiciona o 2º ponto
        v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2'); // Adiciona o traço

        // Atualiza o valor do input
        input.value = v;
    }

    async function editarPessoa(id) {
        try {
            const p = AtendeLabApi.toObject(await AtendeLabApi.get('pessoas', 'buscar', { id }));
            novaPessoa();
            document.getElementById('tituloFormulario').textContent = 'Editar pessoa';
            for (const [key, value] of Object.entries(p)) {
                const field = formPessoa.elements.namedItem(key);
                if (field) field.value = value ?? '';
            }
        } catch (error) {
            AtendeLabApi.showAlert('alerta', error.message, 'danger');
        }
    }

    formPessoa.addEventListener('submit', async event => {
        event.preventDefault();
        const id = document.getElementById('pessoaId').value;
        try {
            await AtendeLabApi.post('pessoas', id ? 'atualizar' : 'cadastrar', new FormData(formPessoa));
            AtendeLabApi.showAlert('alerta', id ? 'Pessoa atualizada com sucesso.' : 'Pessoa cadastrada com sucesso');
            fecharFormulario();
            await carregarPessoas();
        } catch (error) {
            AtendeLabApi.showAlert('alerta', error.message, 'danger')
        }
    });

    async function inativarPessoa(id) {
        if (!confirm('Deseja inativar essa pessoa?')) return;
        try {
            await AtendeLabApi.post('pessoas', 'inativar', { id: id, status: 'inativo' });
            AtendeLabApi.showAlert('alerta', 'Pessoa inativada com sucesso.');
            await carregarPessoas();
        } catch (error) {
            AtendeLabApi.showAlert('alerta', error.message, 'danger');
        }
    }

    document.addEventListener('DOMContentLoaded', carregarPessoas);

</script>
<?php require __DIR__ . '/../layout/footer.php';