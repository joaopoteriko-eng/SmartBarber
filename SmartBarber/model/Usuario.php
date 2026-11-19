<?php
class Usuario
{
    public static function buscarPorEmail(string $email): ?array
    {
        $st = Database::conectar()->prepare('SELECT * FROM usuarios WHERE email = ?');
        $st->execute([$email]);
        return $st->fetch() ?: null;
    }

    public static function criar(array $d): int
    {
        $pdo = Database::conectar();
        $st = $pdo->prepare(
            'INSERT INTO usuarios (id_barbearia, nome, email, cpf, senha, perfil) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $st->execute([
            $d['id_barbearia'] ?? null,
            $d['nome'],
            $d['email'],
            so_numeros($d['cpf']),
            password_hash($d['senha'], PASSWORD_BCRYPT),
            $d['perfil'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function listarBarbeiros(int $idBarbearia): array
    {
        $st = Database::conectar()->prepare(
            "SELECT id_usuario, nome, email, cpf FROM usuarios
              WHERE id_barbearia = ? AND perfil = 'barbeiro' ORDER BY nome"
        );
        $st->execute([$idBarbearia]);
        return $st->fetchAll();
    }

    public static function barbeiroDaBarbearia(int $id, int $idBarbearia): bool
    {
        $st = Database::conectar()->prepare(
            "SELECT 1 FROM usuarios WHERE id_usuario = ? AND id_barbearia = ? AND perfil = 'barbeiro'"
        );
        $st->execute([$id, $idBarbearia]);
        return (bool)$st->fetchColumn();
    }

    public static function excluirBarbeiro(int $id, int $idBarbearia): bool
    {
        $st = Database::conectar()->prepare(
            "DELETE FROM usuarios WHERE id_usuario = ? AND id_barbearia = ? AND perfil = 'barbeiro'"
        );
        $st->execute([$id, $idBarbearia]);
        return $st->rowCount() > 0;
    }
}