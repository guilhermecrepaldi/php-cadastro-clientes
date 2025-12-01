CREATE DATABASE IF NOT EXISTS clientes;
USE clientes;
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    telefone VARCHAR(20),
    cpf VARCHAR(14) UNIQUE,
    endereco TEXT,
    cidade VARCHAR(50),
    estado CHAR(2),
    cep VARCHAR(9),
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);
