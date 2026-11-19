<?php

session_start();
define('ROOT', __DIR__);

require ROOT . '/config/config.php';
require ROOT . '/core/helpers.php';

spl_autoload_register(function (string $classe) {
    foreach (['controller', 'model', 'core'] as $pasta) {
        $arquivo = ROOT . "/$pasta/$classe.php";
        if (is_file($arquivo)) {
            require $arquivo;
            return;
        }
    }
});

$partes = explode('/', trim($_GET['url'] ?? '', '/'));
$nomeController = $partes[0] !== '' ? $partes[0] : (usuario_logado() ? 'painel' : 'auth');
$acao = $partes[1] ?? ($nomeController === 'auth' ? 'login' : 'index');

$classe = ucfirst(strtolower($nomeController)) . 'Controller';

if (preg_match('/^[a-z]+$/i', $nomeController) && preg_match('/^[a-z_]+$/i', $acao)
    && class_exists($classe) && is_callable([$obj = new $classe(), $acao])) {
    $obj->$acao();
} else {
    http_response_code(404);
    (new class extends Controller {
        public function erro404() { $this->view('erro', ['mensagem' => 'Página não encontrada.']); }
    })->erro404();
}