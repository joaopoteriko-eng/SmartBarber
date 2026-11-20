<h1 class="h3 mb-3">Equipe de barbeiros</h1>

<form method="post" action="<?= url('equipe/salvar') ?>" class="card card-body mb-4">
    <?= csrf_campo() ?>
    <h2 class="h5">Novo barbeiro</h2>
    <div class="row g-3">
        <div class="col-md-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" id="nome" name="nome" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label for="cpf" class="form-label">CPF</label>
            <input type="text" id="cpf" name="cpf" class="form-control" inputmode="numeric" maxlength="14" required>
        </div>
        <div class="col-md-2">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" id="senha" name="senha" class="form-control" minlength="6" required autocomplete="new-password">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-dark w-100">Cadastrar</button>
        </div>
    </div>
</form>

<div class="table-responsive">
<table class="table table-striped align-middle">
    <caption class="visually-hidden">Barbeiros da barbearia</caption>
    <thead><tr><th scope="col">Nome</th><th scope="col">E-mail</th><th scope="col">Ações</th></tr></thead>
    <tbody>
    <?php foreach ($barbeiros as $b): ?>
        <tr>
            <td><?= e($b['nome']) ?></td>
            <td><?= e($b['email']) ?></td>
            <td>
                <form method="post" action="<?= url('equipe/excluir') ?>" onsubmit="return confirm('Excluir este barbeiro?')">
                    <?= csrf_campo() ?>
                    <input type="hidden" name="id_usuario" value="<?= e($b['id_usuario']) ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$barbeiros): ?><tr><td colspan="3">Nenhum barbeiro cadastrado.</td></tr><?php endif; ?>
    </tbody>
</table>
</div>