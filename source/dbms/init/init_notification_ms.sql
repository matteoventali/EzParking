-- Database for Notification Microservice
USE db_notification_ms;

-- This script will be executed only when the container is built
-- automatically by Docker

CREATE TABLE IF NOT EXISTS Users (
    id                          INT PRIMARY KEY,
    name                        VARCHAR(50) NOT NULL,
    surname                     VARCHAR(50) NOT NULL,
    mail                        VARCHAR(100) NOT NULL UNIQUE,
    lastlogin_ts                TIMESTAMP DEFAULT NULL,
    last_position               POINT
);