<div class="row justify-content-center">
    <div class="col-md-7">
        <h1 class="h3 mb-3">Cadastrar minha barbearia</h1>
        <form method="post" action="<?= url('barbearia/registrar') ?>" class="card card-body">
            <?= csrf_campo() ?>
            <h2 class="h5">Dados da barbearia</h2>
            <div class="row">
                <div class="col-md-7 mb-3">
                    <label for="nome_fantasia" class="form-label">Nome da barbearia</label>
                    <input type="text" id="nome_fantasia" name="nome_fantasia" class="form-control" required>
                </div>
                <div class="col-md-5 mb-3">
                    <label for="cnpj" class="form-label">CNPJ</label>
                    <input type="text" id="cnpj" name="cnpj" class="form-control" inputmode="numeric" maxlength="18" required>
                </div>
            </div>
            <h2 class="h5">Dados do dono (acesso ao sistema)</h2>
            <div class="mb-3">
                <label for="nome" class="form-label">Seu nome</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cpf" class="form-label">CPF</label>
                    <input type="text" id="cpf" name="cpf" class="form-control" inputmode="numeric" maxlength="14" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha (mínimo 6 caracteres)</label>
                <input type="password" id="senha" name="senha" class="form-control" minlength="6" required autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-dark">Cadastrar barbearia</button>
        </form>
    </div>
</div>