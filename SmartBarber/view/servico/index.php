<h1 class="h3 mb-3">Serviços</h1>

<form method="post" action="<?= url('servico/salvar') ?>" class="card card-body mb-4">
    <?= csrf_campo() ?>
    <input type="hidden" name="id_servico" value="<?= e($editar['id_servico'] ?? 0) ?>">
    <h2 class="h5"><?= $editar ? 'Editar serviço' : 'Novo serviço' ?></h2>
    <div class="row g-3">
        <div class="col-md-5">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" id="descricao" name="descricao" class="form-control" required
                   value="<?= e($editar['descricao'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label for="preco_base" class="form-label">Preço base (R$)</label>
            <input type="text" id="preco_base" name="preco_base" class="form-control" inputmode="decimal" required
                   value="<?= isset($editar['preco_base']) ? e(number_format($editar['preco_base'], 2, ',', '')) : '' ?>">
        </div>
        <div class="col-md-2">
            <label for="duracao_min" class="form-label">Duração (min)</label>
            <input type="number" id="duracao_min" name="duracao_min" class="form-control" min="5" max="480" required
                   value="<?= e($editar['duracao_min'] ?? 30) ?>">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-dark w-100">Salvar</button>
        </div>
    </div>
</form>

<div class="table-responsive">
<table class="table table-striped align-middle">
    <caption class="visually-hidden">Lista de serviços da barbearia</caption>
    <thead><tr><th scope="col">Descrição</th><th scope="col">Preço base</th><th scope="col">Duração</th><th scope="col">Ações</th></tr></thead>
    <tbody>
    <?php foreach ($servicos as $s): ?>
        <tr>
            <td><?= e($s['descricao']) ?></td>
            <td><?= moeda($s['preco_base']) ?></td>
            <td><?= e($s['duracao_min']) ?> min</td>
            <td class="d-flex gap-2">
                <a class="btn btn-sm btn-outline-secondary" href="<?= url('servico/index&id=' . $s['id_servico']) ?>">Editar</a>
                <form method="post" action="<?= url('servico/excluir') ?>" onsubmit="return confirm('Excluir este serviço?')">
                    <?= csrf_campo() ?>
                    <input type="hidden" name="id_servico" value="<?= e($s['id_servico']) ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$servicos): ?><tr><td colspan="4">Nenhum serviço cadastrado.</td></tr><?php endif; ?>
    </tbody>
</table>
</div>