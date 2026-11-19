<?php

const DIAS_SEMANA = [1 => 'Domingo', 2 => 'Segunda', 3 => 'Terça', 4 => 'Quarta',
                     5 => 'Quinta', 6 => 'Sexta', 7 => 'Sábado'];

function e($valor): string           
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

function url(string $rota = ''): string
{
    return BASE_URL . '/index.php?url=' . $rota;
}

function usuario_logado(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

function flash(string $tipo, string $mensagem): void  
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'msg' => $mensagem];
}

function pegar_flash(): ?array
{
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}


function csrf_campo(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return '<input type="hidden" name="csrf" value="' . $_SESSION['csrf'] . '">';
}

function csrf_valido(): bool
{
    return hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '');
}

function so_numeros(string $v): string
{
    return preg_replace('/\D/', '', $v);
}

function moeda($v): string
{
    return 'R$ ' . number_format((float)$v, 2, ',', '.');
}

function data_br(string $dataHora): string
{
    return date('d/m/Y H:i', strtotime($dataHora));
}

function numero_decimal(string $v): float     
{
    return (float)str_replace(',', '.', trim($v));
}

function data_valida(string $data): bool
{
    $d = DateTime::createFromFormat('Y-m-d', $data);
    return $d && $d->format('Y-m-d') === $data;
}

function validar_cpf(string $cpf): bool
{
    $cpf = so_numeros($cpf);
    if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
        return false;                                   
    }
    for ($t = 9; $t < 11; $t++) {
        $soma = 0;
        for ($i = 0; $i < $t; $i++) {
            $soma += (int)$cpf[$i] * (($t + 1) - $i);
        }
        $dv = ((10 * $soma) % 11) % 10;
        if ((int)$cpf[$t] !== $dv) {
            return false;
        }
    }
    return true;
}

function validar_usuario(array $p): array
{
    $erros = [];
    if (trim($p['nome'] ?? '') === '') {
        $erros[] = 'Informe o nome.';
    }
    if (!filter_var($p['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'E-mail inválido.';
    }
    if (!validar_cpf($p['cpf'] ?? '')) {
        $erros[] = 'CPF inválido.';
    }
    if (strlen($p['senha'] ?? '') < 6) {
        $erros[] = 'A senha deve ter pelo menos 6 caracteres.';
    }
    return $erros;
}