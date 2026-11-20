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
        public static function criarComDono(array $barbearia, array $dono): void
        {
            $pdo = Database::conectar();
            $pdo->beginTransaction();
            try {
                $st = $pdo->prepare('INSERT INTO barbearias (nome_fantasia, cnpj) VALUES (?, ?)');
                $st->execute([$barbearia['nome_fantasia'], $barbearia['cnpj']]);
                $idBarbearia = (int)$pdo->lastInsertId();
    
                $dono['id_barbearia'] = $idBarbearia;
                $dono['perfil'] = 'dono';
                Usuario::criar($dono);
    
                $pdo->commit();
            } catch (Throwable $e) {
                $pdo->rollBack();
                throw $e;
            }
        }
}