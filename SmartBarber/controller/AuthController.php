<?php
class AuthController extends Controller
{
    public function login(): void
    {
        if (usuario_logado()) {
            $this->redirecionar('painel/index');
        }
        $this->view('auth/login');
    }

    public function entrar(): void
    {
        $this->somentePost();
        $u = Usuario::buscarPorEmail(trim($_POST['email'] ?? ''));

        if (!$u || !password_verify($_POST['senha'] ?? '', $u['senha'])) {
            flash('danger', 'E-mail ou senha inválidos.');
            $this->redirecionar('auth/login');
        }
        if ($u['id_barbearia'] && !Barbearia::estaAtiva((int)$u['id_barbearia'])) {
            flash('danger', 'Esta barbearia está desativada. Fale com o administrador da plataforma.');
            $this->redirecionar('auth/login');
        }

        session_regenerate_id(true);
        $_SESSION['usuario'] = [
            'id'           => (int)$u['id_usuario'],
            'nome'         => $u['nome'],
            'perfil'       => $u['perfil'],
            'id_barbearia' => $u['id_barbearia'] ? (int)$u['id_barbearia'] : null,
        ];
        $this->redirecionar('painel/index');
    }

    public function sair(): void
    {
        $_SESSION = [];
        session_destroy();
        session_start();
        flash('success', 'Você saiu do sistema.');
        $this->redirecionar('auth/login');
    }

    public function cadastro(): void
    {
        $this->view('auth/cadastro');
    }

    public function registrar(): void
    {
        $this->somentePost();
        $erros = validar_usuario($_POST);
        if ($erros) {
            flash('danger', implode(' ', $erros));
            $this->redirecionar('auth/cadastro');
        }
        try {
            Usuario::criar([
                'id_barbearia' => null,
                'nome'   => trim($_POST['nome']),
                'email'  => trim($_POST['email']),
                'cpf'    => $_POST['cpf'],
                'senha'  => $_POST['senha'],
                'perfil' => 'cliente',
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                flash('danger', 'E-mail ou CPF já cadastrado.');
                $this->redirecionar('auth/cadastro');
            }
            throw $e;
        }
        flash('success', 'Conta criada! Faça login para agendar.');
        $this->redirecionar('auth/login');
    }
}