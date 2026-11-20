<h1 class="h3 mb-3">Agendar horário</h1>

<!-- Passo 1: escolher barbearia, serviço, barbeiro e data (GET) -->
<form method="get" action="<?= BASE_URL ?>/index.php" class="card card-body mb-4">
    <input type="hidden" name="url" value="agendamento/novo">
    <div class="row g-3">
        <div class="col-md-3">
            <label for="barbearia" class="form-label">Barbearia</label>
            <select id="barbearia" name="barbearia" class="form-select" required>
                <option value="">Selecione...</option>
                <?php foreach ($barbearias as $b): ?>
                    <option value="<?= $b['id_barbearia'] ?>" <?= $sel['idBarbearia'] == $b['id_barbearia'] ? 'selected' : '' ?>>
                        <?= e($b['nome_fantasia']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="servico" class="form-label">Serviço</label>
            <select id="servico" name="servico" class="form-select" <?= $servicos ? 'required' : 'disabled' ?>>
                <option value=""><?= $servicos ? 'Selecione...' : 'Escolha a barbearia primeiro' ?></option>
                <?php foreach ($servicos as $s): ?>
                    <option value="<?= $s['id_servico'] ?>" <?= $sel['idServico'] == $s['id_servico'] ? 'selected' : '' ?>>
                        <?= e($s['descricao']) ?> - <?= moeda($s['preco_base']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="barbeiro" class="form-label">Barbeiro</label>
            <select id="barbeiro" name="barbeiro" class="form-select" <?= $barbeiros ? 'required' : 'disabled' ?>>
                <option value=""><?= $barbeiros ? 'Selecione...' : 'Escolha a barbearia primeiro' ?></option>
                <?php foreach ($barbeiros as $b): ?>
                    <option value="<?= $b['id_usuario'] ?>" <?= $sel['idBarbeiro'] == $b['id_usuario'] ? 'selected' : '' ?>>
                        <?= e($b['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="data" class="form-label">Data</label>
            <input type="date" id="data" name="data" class="form-control" min="<?= date('Y-m-d') ?>"
                   value="<?= e($sel['data']) ?>">
        </div>
    </div>
    <button type="submit" class="btn btn-dark mt-3 align-self-start">
        <?= $servicos ? 'Ver horários' : 'Continuar' ?>
    </button>
</form>

<!-- Passo 2: horários livres, já com o desconto (RF-04 / RN-02) -->
<?php if ($horarios !== null): ?>
    <h2 class="h5">Horários disponíveis</h2>
    <?php if (!$horarios): ?>
        <div class="alert alert-info" role="status">Não há horários livres nesta data. Tente outro dia ou barbeiro.</div>
    <?php else: ?>
        <div class="row g-3">
        <?php foreach ($horarios as $h): ?>
            <div class="col-6 col-md-3">
                <form method="post" action="<?= url('agendamento/salvar') ?>" class="card card-body text-center h-100">
                    <?= csrf_campo() ?>
                    <input type="hidden" name="barbearia" value="<?= e($sel['idBarbearia']) ?>">
                    <input type="hidden" name="servico"   value="<?= e($sel['idServico']) ?>">
                    <input type="hidden" name="barbeiro"  value="<?= e($sel['idBarbeiro']) ?>">
                    <input type="hidden" name="data_hora" value="<?= e($h['data_hora']) ?>">
                    <strong class="fs-5"><?= e($h['hora']) ?></strong>
                    <?php if ($h['desconto'] > 0): ?>
                        <span class="text-decoration-line-through text-muted"><?= moeda($h['preco_base']) ?></span>
                        <span class="fw-bold text-success"><?= moeda($h['valor_final']) ?></span>
                        <span class="badge text-bg-success mb-2"><?= e(rtrim(rtrim(number_format($h['desconto'], 2, ',', ''), '0'), ',')) ?>% de desconto</span>
                    <?php else: ?>
                        <span class="fw-bold"><?= moeda($h['valor_final']) ?></span>
                        <span class="mb-2">&nbsp;</span>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-dark btn-sm mt-auto">
                        Agendar<span class="visually-hidden"> às <?= e($h['hora']) ?></span>
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>