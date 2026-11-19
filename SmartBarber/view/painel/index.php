<h1 class="h3 mb-4">Bem-vindo, <?= e($u['nome']) ?>!</h1>
<div class="row g-3">
<?php
$cards = [];
switch ($u['perfil']) {
    case 'dono':
        $cards = [
            ['Agenda', 'Veja e atualize os agendamentos do dia.', 'agendamento/agenda'],
            ['Serviços', 'Cadastre serviços e preços base.', 'servico/index'],
            ['Descontos', 'Defina dias e horários com desconto.', 'regra/index'],
            ['Equipe', 'Cadastre os barbeiros da barbearia.', 'equipe/index'],
            ['Relatório', 'Resumo financeiro mensal em PDF.', 'relatorio/index'],
        ];
        break;
    case 'barbeiro':
        $cards = [['Minha agenda', 'Seus atendimentos do dia.', 'agendamento/agenda']];
        break;
    case 'cliente':
        $cards = [
            ['Agendar horário', 'Escolha barbearia, serviço, barbeiro e horário.', 'agendamento/novo'],
            ['Meus agendamentos', 'Acompanhe ou cancele seus horários.', 'agendamento/meus'],
        ];
        break;
    case 'admin':
        $cards = [['Barbearias', 'Gerencie as barbearias da plataforma.', 'admin/barbearias']];
        break;
}
foreach ($cards as [$titulo, $texto, $rota]): ?>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5 card-title"><?= e($titulo) ?></h2>
                <p class="card-text"><?= e($texto) ?></p>
                <a href="<?= url($rota) ?>" class="btn btn-dark stretched-link">Abrir</a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>