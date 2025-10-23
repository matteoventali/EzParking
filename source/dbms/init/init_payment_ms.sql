-- Database for Payment Microservice
USE db_payment_ms;

-- This script will be executed only when the container is built
-- automatically by Docker

CREATE TABLE IF NOT EXISTS Users (
    id                          INT PRIMARY KEY,
    name                        VARCHAR(50) NOT NULL,
    surname                     VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS Reservations (
    id                          INT PRIMARY KEY,
    reservation_ts              TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS Payments (
    id                          INT AUTO_INCREMENT PRIMARY KEY,
    payment_ts                  TIMESTAMP NOT NULL,
    amount                      VARCHAR(50) NOT NULL,
    payment_status              ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    method                      ENUM('credit_card', 'paypal', 'bank_transfer') NOT NULL,
    reservation_id              INT NOT NULL,
    user_id                     INT NOT NULL,
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES Users(id),
    CONSTRAINT fk_reservation FOREIGN KEY (reservation_id) REFERENCES Reservations(id)
);

INSERT INTO Users (id, name, surname) VALUES
(3, 'Serena', 'Ragaglia'),
(4, 'Pierluca', 'Grasso'),
(5, 'Federico', 'De Lullo')