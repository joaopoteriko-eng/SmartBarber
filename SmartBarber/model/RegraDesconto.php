<?php
class RegraDesconto
{
    public static function listar(int $idBarbearia): array
    {
        $st = Database::conectar()->prepare(
            'SELECT * FROM regras_desconto WHERE id_barbearia = ? ORDER BY dia_semana, hora_inicio'
        );
        $st->execute([$idBarbearia]);
        return $st->fetchAll();
    }

    public static function criar(int $idBarbearia, int $dia, string $inicio, string $fim, float $porcentagem): void
    {
        $st = Database::conectar()->prepare(
            'INSERT INTO regras_desconto (id_barbearia, dia_semana, hora_inicio, hora_fim, porcentagem)
             VALUES (?, ?, ?, ?, ?)'
        );
        $st->execute([$idBarbearia, $dia, $inicio, $fim, $porcentagem]);
    }

    public static function excluir(int $id, int $idBarbearia): void
    {
        $st = Database::conectar()->prepare('DELETE FROM regras_desconto WHERE id_regra = ? AND id_barbearia = ?');
        $st->execute([$id, $idBarbearia]);
    }

    // RN-02: maior desconto que cobre o dia/horário (0 se não houver regra)
    public static function melhorPorcentagem(int $idBarbearia, string $dataHora): float
    {
        $dia  = (int)date('w', strtotime($dataHora)) + 1;   // PHP: 0=domingo -> banco: 1=domingo
        $hora = date('H:i:s', strtotime($dataHora));
        $st = Database::conectar()->prepare(
            'SELECT MAX(porcentagem) FROM regras_desconto
              WHERE id_barbearia = ? AND dia_semana = ? AND ? >= hora_inicio AND ? < hora_fim'
        );
        $st->execute([$idBarbearia, $dia, $hora, $hora]);
        return (float)$st->fetchColumn();
    }
}