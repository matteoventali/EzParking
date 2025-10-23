-- Database for Notification Microservice
USE db_notification_ms;

-- This script will be executed only when the container is built
-- automatically by Docker

CREATE TABLE IF NOT EXISTS Users (
    id                          INT PRIMARY KEY,
    name                        VARCHAR(50) NOT NULL,
    surname                     VARCHAR(50) NOT NULL,
    email                        VARCHAR(100) NOT NULL UNIQUE,
    lastlogin_ts                TIMESTAMP DEFAULT NULL,
    last_position               POINT
);

-- Creating some users for testing purposes
INSERT INTO Users (id, name, surname, email, lastlogin_ts, last_position) VALUES
(3, 'Serena', 'Ragaglia', 'serena.ragaglia@gmailcom', NULL, NULL),
(4, 'Pierluca', 'Grasso', 'pierluca.grasso@gmailcom', NULL, NULL),
(5, 'Federico', 'De Lullo', 'federico.delullo@gmailcom', NULL, NULL)