<?php
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    // Configurações para localhost
    define('DB_HOST', '127.0.0.1');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'smart_barber');
    define('BASE_URL', '');
} else {
    // Configurações para InfinityFree
    define('DB_HOST', 'sqlXXX.infinityfree.com'); // Substitua pelo host fornecido
    define('DB_USER', 'epiz_XXXXXXXX'); // Substitua pelo usuário fornecido
    define('DB_PASS', 'sua_senha'); // Substitua pela senha fornecida
    define('DB_NAME', 'epiz_XXXXXXXX_smartbarber'); // Substitua pelo nome do banco fornecido
    define('BASE_URL', '/'); // Ajuste conforme necessário
}