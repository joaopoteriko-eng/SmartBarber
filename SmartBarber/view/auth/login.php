<div class="row justify-content-center">
    <div class="col-md-5">
        <h1 class="h3 mb-3">Entrar no SmartBarber</h1>
        <form method="post" action="<?= url('auth/entrar') ?>" class="card card-body">
            <?= csrf_campo() ?>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" required autocomplete="email">
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" id="senha" name="senha" class="form-control" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-dark w-100">Entrar</button>
        </form>
        <p class="mt-3 text-center">
            <a href="<?= url('auth/cadastro') ?>">Sou cliente: criar conta</a> |
            <a href="<?= url('barbearia/cadastro') ?>">Tenho uma barbearia</a>
        </p>
    </div>
</div>