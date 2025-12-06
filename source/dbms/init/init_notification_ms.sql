-- Database for Notification Microservice
USE db_notification_ms;

-- This script will be executed only when the container is built
-- automatically by Docker

CREATE TABLE IF NOT EXISTS Users (
    id                          INT PRIMARY KEY,
    name                        VARCHAR(50) NOT NULL,
    surname                     VARCHAR(50) NOT NULL,
    email                       VARCHAR(100) NOT NULL UNIQUE,
    lastlogin_ts                TIMESTAMP DEFAULT NULL,
    last_position               POINT,
    phone                       VARCHAR(15) NOT NULL UNIQUE
);

-- Creating some users for testing purposes
INSERT INTO Users (id, name, surname, email, lastlogin_ts, last_position, phone) VALUES
(3, 'Serena', 'Ragaglia', 'serena.ragaglia@gmail.com', NULL, NULL, '3343290262'),
(4, 'Pierluca', 'Grasso', 'pierluca.grasso@gmail.com', NULL, NULL, '3898730182'),
(5, 'Federico', 'De Lullo', 'federico.delullo@gmail.com', NULL, NULL, '3293321366')