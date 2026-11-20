<h1 class="h3 mb-3">Meus agendamentos</h1>
<div class="table-responsive">
<table class="table table-striped align-middle">
    <caption class="visually-hidden">Seus agendamentos</caption>
    <thead>
        <tr><th scope="col">Data e hora</th><th scope="col">Barbearia</th><th scope="col">Serviço</th>
            <th scope="col">Barbeiro</th><th scope="col">Valor</th><th scope="col">Situação</th><th scope="col">Ação</th></tr>
    </thead>
    <tbody>
    <?php foreach ($agendamentos as $a): ?>
        <tr>
            <td><?= e(data_br($a['data_hora_marcada'])) ?></td>
            <td><?= e($a['barbearia']) ?></td>
            <td><?= e($a['servico']) ?></td>
            <td><?= e($a['barbeiro']) ?></td>
            <td><?= moeda($a['valor_final']) ?><?= $a['desconto'] > 0 ? ' <span class="badge text-bg-success">desconto</span>' : '' ?></td>
            <td><?= e(ucfirst($a['status'])) ?></td>
            <td>
                <?php if ($a['status'] === 'agendado'): ?>
                    <form method="post" action="<?= url('agendamento/cancelar') ?>" onsubmit="return confirm('Cancelar este agendamento?')">
                        <?= csrf_campo() ?>
                        <input type="hidden" name="id" value="<?= e($a['id_agendamento']) ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$agendamentos): ?><tr><td colspan="7">Você ainda não tem agendamentos.</td></tr><?php endif; ?>
    </tbody>
</table>
</div>
<a class="btn btn-dark" href="<?= url('agendamento/novo') ?>">Novo agendamento</a>