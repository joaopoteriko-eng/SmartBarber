<?php
class Barbearia
{
    public static function buscar(int $id): ?array
    {
        $st = Database::conectar()->prepare('SELECT * FROM barbearias WHERE id_barbearia = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public static function estaAtiva(int $id): bool
    {
        $st = Database::conectar()->prepare('SELECT ativa FROM barbearias WHERE id_barbearia = ?');
        $st->execute([$id]);
        return (bool)$st->fetchColumn();
    }

    public static function listarAtivas(): array
    {
        return Database::conectar()
            ->query('SELECT id_barbearia, nome_fantasia FROM barbearias WHERE ativa = 1 ORDER BY nome_fantasia')
            ->fetchAll();
    }
}