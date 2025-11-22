-- Database for Account Microservice
USE db_account_ms;

-- This script will be executed only when the container is built
-- automatically by Docker

CREATE TABLE IF NOT EXISTS Users (
    id                          INT AUTO_INCREMENT PRIMARY KEY,
    name                        VARCHAR(50) NOT NULL,
    surname                     VARCHAR(50) NOT NULL,
    password_hash               VARCHAR(255) NOT NULL,
    email                       VARCHAR(100) NOT NULL UNIQUE,
    cc_number                   VARCHAR(16) DEFAULT NULL UNIQUE,
    lastlogin_ts                TIMESTAMP DEFAULT NULL,
    session_token               CHAR(32) DEFAULT NULL,
    phone                       VARCHAR(15) NOT NULL UNIQUE,
    user_role                   ENUM('admin', 'user') DEFAULT 'user',
    account_status              BOOLEAN DEFAULT true
);

CREATE TABLE IF NOT EXISTS Review (
    id                          INT AUTO_INCREMENT PRIMARY KEY,
    review_date                 DATE NOT NULL,
    star                        INT NOT NULL CHECK (star >= 1 AND star <= 5),
    review_description          TEXT NOT NULL,
    writer_id                   INT NOT NULL,
    target_id                   INT NOT NULL,
    reservation_id              INT NOT NULL,
    CONSTRAINT fk_writer FOREIGN KEY (writer_id) REFERENCES Users(id),
    CONSTRAINT fk_target FOREIGN KEY (target_id) REFERENCES Users(id),
    UNIQUE (writer_id, target_id, reservation_id)
);


-- Creating some users for testing purposes
INSERT INTO Users (id, name, surname, password_hash, email, phone, user_role, account_status, cc_number) VALUES
(1, 'Matteo', 'Ventali', 
'scrypt:32768:8:1$d0OF8WM15UKJAoqf$62916af62533ab4ec4d7ae08541369a751f3eba6fb70a65a476182b6a6d3e17c9e8f6f1291310592052347a0344e88b3b949e71a8e104c87512e5ce0c8dce520', 
'matteo.ventali@gmail.com', '3463462160', 'admin', true, null),
(2, 'Valerio', 'Spagnoli', 
'scrypt:32768:8:1$d0OF8WM15UKJAoqf$62916af62533ab4ec4d7ae08541369a751f3eba6fb70a65a476182b6a6d3e17c9e8f6f1291310592052347a0344e88b3b949e71a8e104c87512e5ce0c8dce520', 
'valerio.spagnoli@gmail.com', '3454616365', 'admin', true, null),
(3, 'Serena', 'Ragaglia', 
'scrypt:32768:8:1$d0OF8WM15UKJAoqf$62916af62533ab4ec4d7ae08541369a751f3eba6fb70a65a476182b6a6d3e17c9e8f6f1291310592052347a0344e88b3b949e71a8e104c87512e5ce0c8dce520', 
'serena.ragaglia@gmail.com', '3343290262', 'user', true, '5254768913428815'),
(4, 'Pierluca', 'Grasso', 
'scrypt:32768:8:1$d0OF8WM15UKJAoqf$62916af62533ab4ec4d7ae08541369a751f3eba6fb70a65a476182b6a6d3e17c9e8f6f1291310592052347a0344e88b3b949e71a8e104c87512e5ce0c8dce520', 
'pierluca.grasso@gmail.com', '3898730182', 'user', true, '5407993422841603'),
(5, 'Federico', 'De Lullo', 
'scrypt:32768:8:1$d0OF8WM15UKJAoqf$62916af62533ab4ec4d7ae08541369a751f3eba6fb70a65a476182b6a6d3e17c9e8f6f1291310592052347a0344e88b3b949e71a8e104c87512e5ce0c8dce520', 
'federico.delullo@gmail.com', '3293321366', 'user', true, '378282246310005')