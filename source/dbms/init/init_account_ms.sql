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
    lastlogin_ts                TIMESTAMP DEFAULT NULL,
    session_token               CHAR(32) DEFAULT NULL,
    phone                       VARCHAR(15) NOT NULL UNIQUE,
    user_role                   ENUM('admin', 'user') DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS Reservation (
    id                          INT AUTO_INCREMENT PRIMARY KEY,
    resident_name               VARCHAR(50) NOT NULL,
    resident_surname            VARCHAR(50) NOT NULL,
    reservation_ts              TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS Review (
    review_date                 DATE NOT NULL,
    star                        INT NOT NULL CHECK (star >= 1 AND star <= 5),
    review_description          TEXT NOT NULL,
    user_id                     INT NOT NULL,
    reservation_id              INT NOT NULL,
    PRIMARY KEY (user_id, reservation_id),
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES Users(id),
    CONSTRAINT fk_reservation FOREIGN KEY (reservation_id) REFERENCES Reservation(id)
);