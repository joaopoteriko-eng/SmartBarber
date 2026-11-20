<?php
class ServicoController extends Controller
{
    public function index(): void
    {
        $u = $this->exigir(['dono']);
        $editar = isset($_GET['id']) ? Servico::buscar((int)$_GET['id'], $u['id_barbearia']) : null;
        $this->view('servico/index', [
            'servicos' => Servico::listar($u['id_barbearia']),
            'editar'   => $editar,
        ]);
    }

    public function salvar(): void
    {
        $u = $this->exigir(['dono']);
        $this->somentePost();

        $id        = (int)($_POST['id_servico'] ?? 0);
        $descricao = trim($_POST['descricao'] ?? '');
        $preco     = numero_decimal($_POST['preco_base'] ?? '0');
        $duracao   = (int)($_POST['duracao_min'] ?? 0);

        if ($descricao === '' || $preco <= 0 || $duracao < 5 || $duracao > 480) {
            flash('danger', 'Preencha a descrição, um preço maior que zero e uma duração entre 5 e 480 minutos.');
            $this->redirecionar('servico/index');
        }

        if ($id > 0) {
            Servico::atualizar($id, $u['id_barbearia'], $descricao, $preco, $duracao);
            flash('success', 'Serviço atualizado.');
        } else {
            Servico::criar($u['id_barbearia'], $descricao, $preco, $duracao);
            flash('success', 'Serviço cadastrado.');
        }
        $this->redirecionar('servico/index');
    }

    // RN-03: exclusão só para o administrador da barbearia (o dono)
    public function excluir(): void
    {
        $u = $this->exigir(['dono']);
        $this->somentePost();
        try {
            Servico::excluir((int)($_POST['id_servico'] ?? 0), $u['id_barbearia']);
            flash('success', 'Serviço excluído.');
        } catch (PDOException $e) {
            flash('danger', 'Este serviço já tem agendamentos e não pode ser excluído.');
        }
        $this->redirecionar('servico/index');
    }
}