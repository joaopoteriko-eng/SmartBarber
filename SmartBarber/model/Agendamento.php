<?php
class Agendamento
{
    // RN-02: aplica o desconto dinâmico sobre o preço base
    public static function calcularValor(int $idBarbearia, float $precoBase, string $dataHora): array
    {
        $desconto = RegraDesconto::melhorPorcentagem($idBarbearia, $dataHora);
        return [
            'preco_base'  => $precoBase,
            'desconto'    => $desconto,
            'valor_final' => round($precoBase * (1 - $desconto / 100), 2),
        ];
    }

    // RF-05: horários livres do barbeiro no dia, já com o desconto de cada um
    public static function horariosLivres(int $idBarbearia, int $idBarbeiro, int $idServico, string $data): array
    {
        $servico = Servico::buscar($idServico, $idBarbearia);
        if (!$servico || !Usuario::barbeiroDaBarbearia($idBarbeiro, $idBarbearia)) {
            return [];
        }
        $duracao = (int)$servico['duracao_min'] * 60;

        // agendamentos que já ocupam o barbeiro nesse dia (cancelados não contam)
        $st = Database::conectar()->prepare(
            "SELECT a.data_hora_marcada AS ini,
                    DATE_ADD(a.data_hora_marcada, INTERVAL s.duracao_min MINUTE) AS fim
               FROM agendamentos a
               JOIN servicos s ON s.id_servico = a.id_servico
              WHERE a.id_barbearia = ? AND a.id_barbeiro = ?
                AND a.status <> 'cancelado' AND DATE(a.data_hora_marcada) = ?"
        );
        $st->execute([$idBarbearia, $idBarbeiro, $data]);
        $ocupados = $st->fetchAll();

        $horarios = [];
        $fechamento = strtotime("$data " . HORA_FECHAMENTO);
        for ($t = strtotime("$data " . HORA_ABERTURA); $t + $duracao <= $fechamento; $t += INTERVALO_MIN * 60) {
            if ($t <= time()) {
                continue;                                   // horário que já passou
            }
            $livre = true;
            foreach ($ocupados as $o) {
                if (strtotime($o['ini']) < $t + $duracao && strtotime($o['fim']) > $t) {
                    $livre = false;                         // RN-04: sobrepõe outro agendamento
                    break;
                }
            }
            if ($livre) {
                $dataHora = date('Y-m-d H:i:s', $t);
                $horarios[] = ['hora' => date('H:i', $t), 'data_hora' => $dataHora]
                            + self::calcularValor($idBarbearia, (float)$servico['preco_base'], $dataHora);
            }
        }
        return $horarios;
    }

    // Cria o agendamento. Lança RuntimeException com a mensagem para o usuário.
    public static function criar(int $idBarbearia, int $idCliente, int $idBarbeiro, int $idServico, string $dataHora): void
    {
        // confere de novo no servidor se o horário ainda está livre (não confia no formulário)
        $slot = null;
        foreach (self::horariosLivres($idBarbearia, $idBarbeiro, $idServico, substr($dataHora, 0, 10)) as $h) {
            if ($h['data_hora'] === $dataHora) {
                $slot = $h;
            }
        }
        if (!$slot) {
            throw new RuntimeException('Este horário não está mais disponível.');
        }

        try {
            $st = Database::conectar()->prepare(
                'INSERT INTO agendamentos
                    (id_barbearia, id_cliente, id_barbeiro, id_servico, data_hora_marcada, preco_base, desconto, valor_final)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $st->execute([$idBarbearia, $idCliente, $idBarbeiro, $idServico, $dataHora,
                          $slot['preco_base'], $slot['desconto'], $slot['valor_final']]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {                // índice único do banco (RN-04)
                throw new RuntimeException('Este horário acabou de ser reservado por outro cliente.');
            }
            throw $e;
        }
    }

    public static function doCliente(int $idCliente): array
    {
        $st = Database::conectar()->prepare(
            "SELECT a.id_agendamento, a.data_hora_marcada, a.status, a.desconto, a.valor_final,
                    br.nome_fantasia AS barbearia, b.nome AS barbeiro, s.descricao AS servico
               FROM agendamentos a
               JOIN barbearias br ON br.id_barbearia = a.id_barbearia
               JOIN usuarios b    ON b.id_usuario    = a.id_barbeiro
               JOIN servicos s    ON s.id_servico    = a.id_servico
              WHERE a.id_cliente = ?
              ORDER BY a.data_hora_marcada DESC"
        );
        $st->execute([$idCliente]);
        return $st->fetchAll();
    }

    public static function cancelarDoCliente(int $id, int $idCliente): bool
    {
        $st = Database::conectar()->prepare(
            "UPDATE agendamentos SET status = 'cancelado'
              WHERE id_agendamento = ? AND id_cliente = ? AND status = 'agendado'"
        );
        $st->execute([$id, $idCliente]);
        return $st->rowCount() > 0;
    }
}