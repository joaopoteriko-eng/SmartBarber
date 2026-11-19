<div class="row justify-content-center">
    <div class="col-md-6">
        <h1 class="h3 mb-3">Criar conta de cliente</h1>
        <form method="post" action="<?= url('auth/registrar') ?>" class="card card-body">
            <?= csrf_campo() ?>
            <div class="mb-3">
                <label for="nome" class="form-label">Nome completo</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" id="cpf" name="cpf" class="form-control" inputmode="numeric" maxlength="14" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha (mínimo 6 caracteres)</label>
                <input type="password" id="senha" name="senha" class="form-control" minlength="6" required autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-dark">Cadastrar</button>
        </form>
    </div>
</div>