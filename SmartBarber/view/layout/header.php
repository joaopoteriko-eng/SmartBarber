<?php $u = usuario_logado(); $flash = pegar_flash(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartBarber</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<a class="visually-hidden-focusable" href="#conteudo">Pular para o conteúdo</a>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4" aria-label="Menu principal">
    <div class="container">
        <a class="navbar-brand" href="<?= url('painel/index') ?>">✂ SmartBarber</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu"
                aria-controls="menu" aria-expanded="false" aria-label="Abrir ou fechar o menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <?php if ($u && $u['perfil'] === 'dono'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= url('agendamento/agenda') ?>">Agenda</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('servico/index') ?>">Serviços</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('regra/index') ?>">Descontos</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('equipe/index') ?>">Equipe</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('relatorio/index') ?>">Relatório</a></li>
                <?php elseif ($u && $u['perfil'] === 'barbeiro'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= url('agendamento/agenda') ?>">Minha agenda</a></li>
                <?php elseif ($u && $u['perfil'] === 'cliente'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= url('agendamento/novo') ?>">Agendar</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('agendamento/meus') ?>">Meus agendamentos</a></li>
                <?php elseif ($u && $u['perfil'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= url('admin/barbearias') ?>">Barbearias</a></li>
                <?php endif; ?>
            </ul>
            <?php if ($u): ?>
                <span class="navbar-text me-3">Olá, <?= e($u['nome']) ?> (<?= e($u['perfil']) ?>)</span>
                <a class="btn btn-outline-light btn-sm" href="<?= url('auth/sair') ?>">Sair</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main id="conteudo" class="container pb-5">
    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['tipo']) ?>" role="alert"><?= e($flash['msg']) ?></div>
    <?php endif; ?>