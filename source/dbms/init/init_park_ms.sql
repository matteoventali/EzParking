-- Database for Parking Microservice
USE db_park_ms;

-- This script will be executed only when the container is built
-- automatically by Docker

CREATE TABLE IF NOT EXISTS Users (
    id                          INT PRIMARY KEY,
    name                        VARCHAR(50) NOT NULL,
    surname                     VARCHAR(50) NOT NULL,
    session_token               CHAR(32) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS Labels (
    id                          INT AUTO_INCREMENT PRIMARY KEY,
    name                        VARCHAR(50) NOT NULL,
    label_description           TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS Parking_Spots (
    id                          INT AUTO_INCREMENT PRIMARY KEY,
    name                        VARCHAR(50) NOT NULL,
    spot_location               POINT NOT NULL,
    rep_treshold                INT NOT NULL DEFAULT 0 CHECK (rep_treshold >= 0 AND rep_treshold <= 5),       
    slot_price                  DOUBLE NOT NULL CHECK (slot_price >= 0),
    user_id                     INT NOT NULL,
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES Users(id)
);

CREATE TABLE IF NOT EXISTS Availability_Slots (
    id                          INT AUTO_INCREMENT PRIMARY KEY,
    slot_date                   DATE NOT NULL,
    start_time                  TIME NOT NULL,
    end_time                    TIME NOT NULL,
    parking_spot_id             INT NOT NULL,
    UNIQUE (slot_date, start_time, parking_spot_id),
    CONSTRAINT fk_parking_spot FOREIGN KEY (parking_spot_id) REFERENCES Parking_Spots(id),
    CHECK (end_time > start_time)
);

CREATE TABLE IF NOT EXISTS Parking_Spot_Labels (
    parking_spot_id             INT NOT NULL,
    label_id                    INT NOT NULL,
    PRIMARY KEY (parking_spot_id, label_id),
    CONSTRAINT fk2_parking_spot FOREIGN KEY (parking_spot_id) REFERENCES Parking_Spots(id),
    CONSTRAINT fk2_label FOREIGN KEY (label_id) REFERENCES Labels(id)
);

CREATE TABLE IF NOT EXISTS Reservations (
    id                          INT AUTO_INCREMENT PRIMARY KEY,
    reservation_ts              TIMESTAMP NOT NULL,
    reservation_status          ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    car_plate                   CHAR(7) NOT NULL,
    slot_id                     INT NOT NULL,
    user_id                     INT NOT NULL,
    CONSTRAINT fk_slot FOREIGN KEY (slot_id) REFERENCES Availability_Slots(id),
    CONSTRAINT fk2_user FOREIGN KEY (user_id) REFERENCES Users(id)
);