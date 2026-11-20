<?php
class AgendamentoController extends Controller
{
    // Tela em etapas: barbearia -> serviço/barbeiro/data -> horários com desconto
    public function novo(): void
    {
        $this->exigir(['cliente']);

        $idBarbearia = (int)($_GET['barbearia'] ?? 0);
        $idServico   = (int)($_GET['servico'] ?? 0);
        $idBarbeiro  = (int)($_GET['barbeiro'] ?? 0);
        $data        = $_GET['data'] ?? '';

        $servicos = $barbeiros = [];
        $horarios = null;                                   // null = ainda não pesquisou

        if ($idBarbearia && Barbearia::estaAtiva($idBarbearia)) {
            $servicos  = Servico::listar($idBarbearia);
            $barbeiros = Usuario::listarBarbeiros($idBarbearia);
            if ($idServico && $idBarbeiro && data_valida($data) && $data >= date('Y-m-d')) {
                $horarios = Agendamento::horariosLivres($idBarbearia, $idBarbeiro, $idServico, $data);
            }
        }

        $this->view('agendamento/novo', [
            'barbearias' => Barbearia::listarAtivas(),
            'servicos'   => $servicos,
            'barbeiros'  => $barbeiros,
            'horarios'   => $horarios,
            'sel'        => compact('idBarbearia', 'idServico', 'idBarbeiro', 'data'),
        ]);
    }

    public function salvar(): void
    {
        $u = $this->exigir(['cliente']);
        $this->somentePost();

        $idBarbearia = (int)($_POST['barbearia'] ?? 0);
        $idServico   = (int)($_POST['servico'] ?? 0);
        $idBarbeiro  = (int)($_POST['barbeiro'] ?? 0);
        $dataHora    = $_POST['data_hora'] ?? '';

        try {
            if (!Barbearia::estaAtiva($idBarbearia)) {
                throw new RuntimeException('Barbearia indisponível.');
            }
            Agendamento::criar($idBarbearia, $u['id'], $idBarbeiro, $idServico, $dataHora);
            flash('success', 'Agendamento realizado! O pagamento é feito na barbearia.');
            $this->redirecionar('agendamento/meus');
        } catch (RuntimeException $e) {
            flash('danger', $e->getMessage());
            $this->redirecionar("agendamento/novo&barbearia=$idBarbearia&servico=$idServico&barbeiro=$idBarbeiro&data=" . substr($dataHora, 0, 10));
        }
    }

    public function meus(): void
    {
        $u = $this->exigir(['cliente']);
        $this->view('agendamento/meus', ['agendamentos' => Agendamento::doCliente($u['id'])]);
    }

    public function cancelar(): void
    {
        $u = $this->exigir(['cliente']);
        $this->somentePost();
        $ok = Agendamento::cancelarDoCliente((int)($_POST['id'] ?? 0), $u['id']);
        flash($ok ? 'success' : 'danger', $ok ? 'Agendamento cancelado.' : 'Não foi possível cancelar.');
        $this->redirecionar('agendamento/meus');
    }
}