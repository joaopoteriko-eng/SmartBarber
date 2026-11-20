<h1 class="h3 mb-3">Regras de desconto</h1>
<p>Informe os dias e horários de pouco movimento. O desconto é aplicado automaticamente quando o cliente escolhe esse horário.</p>

<form method="post" action="<?= url('regra/salvar') ?>" class="card card-body mb-4">
    <?= csrf_campo() ?>
    <div class="row g-3">
        <div class="col-md-3">
            <label for="dia_semana" class="form-label">Dia da semana</label>
            <select id="dia_semana" name="dia_semana" class="form-select" required>
                <?php foreach (DIAS_SEMANA as $n => $nome): ?>
                    <option value="<?= $n ?>"><?= e($nome) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label for="hora_inicio" class="form-label">Das</label>
            <input type="time" id="hora_inicio" name="hora_inicio" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label for="hora_fim" class="form-label">Até</label>
            <input type="time" id="hora_fim" name="hora_fim" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label for="porcentagem" class="form-label">Desconto (%)</label>
            <input type="number" id="porcentagem" name="porcentagem" class="form-control" min="1" max="100" step="0.01" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-dark w-100">Adicionar</button>
        </div>
    </div>
</form>

<div class="table-responsive">
<table class="table table-striped align-middle">
    <caption class="visually-hidden">Regras de desconto cadastradas</caption>
    <thead><tr><th scope="col">Dia</th><th scope="col">Horário</th><th scope="col">Desconto</th><th scope="col">Ações</th></tr></thead>
    <tbody>
    <?php foreach ($regras as $r): ?>
        <tr>
            <td><?= e(DIAS_SEMANA[$r['dia_semana']]) ?></td>
            <td><?= e(substr($r['hora_inicio'], 0, 5)) ?> às <?= e(substr($r['hora_fim'], 0, 5)) ?></td>
            <td><?= e(rtrim(rtrim(number_format($r['porcentagem'], 2, ',', ''), '0'), ',')) ?>%</td>
            <td>
                <form method="post" action="<?= url('regra/excluir') ?>" onsubmit="return confirm('Excluir esta regra?')">
                    <?= csrf_campo() ?>
                    <input type="hidden" name="id_regra" value="<?= e($r['id_regra']) ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$regras): ?><tr><td colspan="4">Nenhuma regra cadastrada.</td></tr><?php endif; ?>
    </tbody>
</table>
</div>