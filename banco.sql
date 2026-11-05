CREATE DATABASE IF NOT EXISTS smart_barber
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE smart_barber;

CREATE TABLE barbearias (
    id_barbearia INT AUTO_INCREMENT PRIMARY KEY,
    nome_fantasia VARCHAR(100) NOT NULL,
    cnpj CHAR(14) UNIQUE NOT NULL,
    ativa BOOLEAN NOT NULL DEFAULT TRUE,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    id_barbearia INT NULL,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    cpf CHAR(11) UNIQUE NOT NULL,
    senha VARCHAR(100) NOT NULL,
    perfil ENUM('admin', 'dono', 'barbeiro', 'cliente') NOT NULL,

    UNIQUE (id_usuario, id_barbearia),
    FOREIGN KEY (id_barbearia) REFERENCES barbearias(id_barbearia)
);

CREATE TABLE servicos (
    id_servico INT AUTO_INCREMENT PRIMARY KEY,
    id_barbearia INT NOT NULL,
    descricao VARCHAR(100) NOT NULL,
    preco_base DECIMAL(10,2) NOT NULL,
    duracao_min INT NOT NULL DEFAULT 30,

    UNIQUE (id_servico, id_barbearia),
    FOREIGN KEY (id_barbearia) REFERENCES barbearias(id_barbearia) ON DELETE CASCADE
);

CREATE TABLE regras_desconto (
    id_regra INT AUTO_INCREMENT PRIMARY KEY,
    id_barbearia INT NOT NULL,
    dia_semana INT NOT NULL COMMENT '1=Domingo, 2=Segunda, 3=Terça, 4=Quarta, 5=Quinta, 6=Sexta, 7=Sábado',
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    porcentagem DECIMAL(5,2) NOT NULL COMMENT 'Ex: 20 para 20%',

    FOREIGN KEY (id_barbearia) REFERENCES barbearias(id_barbearia) ON DELETE CASCADE
);

CREATE TABLE agendamentos (
    id_agendamento INT AUTO_INCREMENT PRIMARY KEY,
    id_barbearia INT NOT NULL,
    id_cliente INT NOT NULL,
    id_barbeiro INT NOT NULL,
    id_servico INT NOT NULL,
    data_hora_marcada DATETIME NOT NULL,
    preco_base DECIMAL(10,2) NOT NULL,
    desconto DECIMAL(5,2) NOT NULL DEFAULT 0,
    valor_final DECIMAL(10,2) NOT NULL,
    status ENUM('agendado', 'concluido', 'cancelado') NOT NULL DEFAULT 'agendado',
    horario_ocupado DATETIME AS (IF(status = 'cancelado', NULL, data_hora_marcada)) STORED,
    UNIQUE (id_barbeiro, horario_ocupado),

    FOREIGN KEY (id_barbearia) REFERENCES barbearias(id_barbearia),
    FOREIGN KEY (id_cliente) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_barbeiro, id_barbearia) REFERENCES usuarios(id_usuario, id_barbearia),
    FOREIGN KEY (id_servico, id_barbearia) REFERENCES servicos(id_servico, id_barbearia)
);

INSERT INTO barbearias (nome_fantasia, cnpj)
VALUES ('Barbearia do Senai', '12345678000199');

INSERT INTO usuarios (id_barbearia, nome, email, cpf, senha, perfil) VALUES
(NULL, 'Admin Plataforma', 'admin@smartbarber.com', '52998224725', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(1, 'Carlos Dono',     'dono@senai.com',     '11144477735', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dono'),
(1, 'João Barbeiro',  'joao@senai.com',     '12345678909', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'barbeiro'),
(NULL, 'Neymar Cliente', 'neymar@senai.com',  '86288366757', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente');

INSERT INTO servicos (id_barbearia, descricao, preco_base, duracao_min)
VALUES (1, 'Corte Degradê', 40.00, 30);

INSERT INTO regras_desconto (id_barbearia, dia_semana, hora_inicio, hora_fim, porcentagem)
VALUES (1, 3, '14:00:00', '16:00:00', 20);

INSERT INTO agendamentos (id_barbearia, id_cliente, id_barbeiro, id_servico, data_hora_marcada, preco_base, desconto, valor_final)
VALUES (1, 4, 3, 1, '2026-11-03 14:30:00', 40.00, 20, 32.00);