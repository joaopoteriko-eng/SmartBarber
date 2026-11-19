<?php
// ===== Configurações do sistema =====
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: 3306);
define('DB_NAME', getenv('DB_NAME') ?: 'smart_barber');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

define('BASE_URL', ''); // nome da pasta dentro do htdocs

// Expediente usado para gerar os horários disponíveis (RF-05)
define('HORA_ABERTURA',   '09:00');
define('HORA_FECHAMENTO', '18:00');
define('INTERVALO_MIN',   30);      // de quanto em quanto tempo aparece um horário

date_default_timezone_set('America/Sao_Paulo');