-- init.sql
-- This script will be executed only when the container is built
-- automatically by Docker

-- Each microservice of the system will have a dedicated database and and account
-- to access into the dbms. Each microservice will be allowed to interact only with
-- its own database

CREATE DATABASE IF NOT EXISTS db_account_ms;
CREATE DATABASE IF NOT EXISTS db_park_ms;
CREATE DATABASE IF NOT EXISTS db_payment_ms;
CREATE DATABASE IF NOT EXISTS db_notification_ms;

CREATE USER IF NOT EXISTS 'test'@'%' IDENTIFIED BY 'ezparking';
CREATE USER IF NOT EXISTS 'user_account_ms'@'%' IDENTIFIED BY 'ezparking';
CREATE USER IF NOT EXISTS 'user_park_ms'@'%' IDENTIFIED BY 'ezparking';
CREATE USER IF NOT EXISTS 'user_payment_ms'@'%' IDENTIFIED BY 'ezparking';
CREATE USER IF NOT EXISTS 'user_notification_ms'@'%' IDENTIFIED BY 'ezparking';

GRANT ALL PRIVILEGES ON *.* TO 'test'@'%';
GRANT ALL PRIVILEGES ON db_account_ms.* TO 'user_account_ms'@'%';
GRANT ALL PRIVILEGES ON db_park_ms.* TO 'user_park_ms'@'%';
GRANT ALL PRIVILEGES ON db_payment_ms.* TO 'user_payment_ms'@'%';
GRANT ALL PRIVILEGES ON db_notification_ms.* TO 'user_notification_ms'@'%';


FLUSH PRIVILEGES;