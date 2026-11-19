<?php
abstract class Controller
{
    protected function view(string $arquivo, array $dados = []): void
    {
        extract($dados);
        require ROOT . '/view/layout/header.php';
        require ROOT . '/view/' . $arquivo . '.php';
        require ROOT . '/view/layout/footer.php';
    }

    protected function redirecionar(string $rota): void
    {
        header('Location: ' . url($rota));
        exit;
    }

    protected function exigir(array $perfis = []): array
    {
        $u = usuario_logado();
        if (!$u) {
            flash('warning', 'Faça login para continuar.');
            $this->redirecionar('auth/login');
        }
        if ($perfis && !in_array($u['perfil'], $perfis, true)) {
            http_response_code(403);
            $this->view('erro', ['mensagem' => 'Você não tem permissão para acessar esta página.']);
            exit;
        }
        return $u;
    }

    protected function somentePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido()) {
            http_response_code(400);
            $this->view('erro', ['mensagem' => 'Requisição inválida.']);
            exit;
        }
    }
}