<?php
class EquipeController extends Controller
{
    public function index(): void
    {
        $u = $this->exigir(['dono']);
        $this->view('equipe/index', ['barbeiros' => Usuario::listarBarbeiros($u['id_barbearia'])]);
    }

    public function salvar(): void
    {
        $u = $this->exigir(['dono']);
        $this->somentePost();

        $erros = validar_usuario($_POST);
        if ($erros) {
            flash('danger', implode(' ', $erros));
            $this->redirecionar('equipe/index');
        }
        try {
            Usuario::criar([
                'id_barbearia' => $u['id_barbearia'],
                'nome'   => trim($_POST['nome']),
                'email'  => trim($_POST['email']),
                'cpf'    => $_POST['cpf'],
                'senha'  => $_POST['senha'],
                'perfil' => 'barbeiro',
            ]);
            flash('success', 'Barbeiro cadastrado.');
        } catch (PDOException $e) {
            if ($e->getCode() !== '23000') {
                throw $e;
            }
            flash('danger', 'E-mail ou CPF já cadastrado.');
        }
        $this->redirecionar('equipe/index');
    }

    public function excluir(): void
    {
        $u = $this->exigir(['dono']);
        $this->somentePost();
        try {
            Usuario::excluirBarbeiro((int)($_POST['id_usuario'] ?? 0), $u['id_barbearia']);
            flash('success', 'Barbeiro excluído.');
        } catch (PDOException $e) {
            flash('danger', 'Este barbeiro já tem agendamentos e não pode ser excluído.');
        }
        $this->redirecionar('equipe/index');
    }
}