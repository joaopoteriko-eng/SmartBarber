<?php
class BarbeariaController extends Controller
{
    public function cadastro(): void
    {
        $this->view('barbearia/cadastro');
    }

    public function registrar(): void
    {
        $this->somentePost();
        $erros = validar_usuario($_POST);
        $cnpj = so_numeros($_POST['cnpj'] ?? '');
        if (trim($_POST['nome_fantasia'] ?? '') === '') {
            $erros[] = 'Informe o nome da barbearia.';
        }
        if (strlen($cnpj) !== 14) {
            $erros[] = 'O CNPJ deve ter 14 números.';
        }
        if ($erros) {
            flash('danger', implode(' ', $erros));
            $this->redirecionar('barbearia/cadastro');
        }

        try {
            Barbearia::criarComDono(
                ['nome_fantasia' => trim($_POST['nome_fantasia']), 'cnpj' => $cnpj],
                ['nome' => trim($_POST['nome']), 'email' => trim($_POST['email']),
                 'cpf' => $_POST['cpf'], 'senha' => $_POST['senha']]
            );
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                flash('danger', 'CNPJ, e-mail ou CPF já cadastrado.');
                $this->redirecionar('barbearia/cadastro');
            }
            throw $e;
        }
        flash('success', 'Barbearia cadastrada! Faça login para configurar os serviços.');
        $this->redirecionar('auth/login');
    }
}