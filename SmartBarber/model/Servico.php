<?php
class Servico
{
    public static function listar(int $idBarbearia): array
    {
        $st = Database::conectar()->prepare('SELECT * FROM servicos WHERE id_barbearia = ? ORDER BY descricao');
        $st->execute([$idBarbearia]);
        return $st->fetchAll();
    }

    public static function buscar(int $id, int $idBarbearia): ?array
    {
        $st = Database::conectar()->prepare('SELECT * FROM servicos WHERE id_servico = ? AND id_barbearia = ?');
        $st->execute([$id, $idBarbearia]);
        return $st->fetch() ?: null;
    }

    public static function criar(int $idBarbearia, string $descricao, float $preco, int $duracao): void
    {
        $st = Database::conectar()->prepare(
            'INSERT INTO servicos (id_barbearia, descricao, preco_base, duracao_min) VALUES (?, ?, ?, ?)'
        );
        $st->execute([$idBarbearia, $descricao, $preco, $duracao]);
    }

    public static function atualizar(int $id, int $idBarbearia, string $descricao, float $preco, int $duracao): void
    {
        $st = Database::conectar()->prepare(
            'UPDATE servicos SET descricao = ?, preco_base = ?, duracao_min = ?
              WHERE id_servico = ? AND id_barbearia = ?'
        );
        $st->execute([$descricao, $preco, $duracao, $id, $idBarbearia]);
    }

    public static function excluir(int $id, int $idBarbearia): void
    {
        $st = Database::conectar()->prepare('DELETE FROM servicos WHERE id_servico = ? AND id_barbearia = ?');
        $st->execute([$id, $idBarbearia]);
    }
}