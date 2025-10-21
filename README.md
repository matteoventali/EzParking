# EzParking

Laboratory of Advanced Programming project.

## Authors
- Matteo Ventali (1985026)
- Serena Ragaglia (1941007)
- Valerio Spagnoli (1973484)
- Pierluca Grasso (1950186)
- Federico De Lullo (1935510)

## Project Description
EzParking is a parking management system based on microservices.
The goal of this system is to create a parking sharing application that enables
users to find and share parking spaces in urban areas in order to reduce traffic congestion 
and environmental impact, and promoting collaboration among users.
It includes modules for user accounts, notifications, payments, and parking management, with a web-based user interface.

## Microservices Structure
- `account_ms` – user account management.
- `notification_ms` – notifications management.
- `park_ms` – parking management.
- `payment_ms` – payments management.
- `ui_ms` – web user interface.
- `dbms` – central database with initialization scripts.

## How to Start the System
The project uses Docker and Docker Compose to orchestrate microservices.

1. Ensure Docker and Docker Compose are installed.

2. From the main project folder, run:
   
   ```bash
   cd source
   docker-compose up -d
    ```

   This command builds (if necessary) and starts all containers in the background.
   To explicitly build the containers, run:
   
   ```bash
   docker-compose up -d --build
   ```

3. To check the status of containers:
   
   ```bash
   docker-compose ps -a
   ```

## How to Stop the System
To stop all services:

```bash
docker-compose down
```

This command stops and removes the containers but keeps the database data intact in the volume.
If you want to delete also the data into volumes, run:

```bash
docker-compose down -v
```


## Rebuilding After Updates
If microservices or Dockerfiles are updated, rebuild the images before restarting:

```bash
docker-compose build
docker-compose up -d
```

## System IP Topology
Docker Compose manages a private internal network for the microservices. Each container can be accessed using its service name:

| Service           | Docker Internal Host  |   IP:Port
|------------------|---------------------------|---------------------|
| account_ms        | `account_ms`             |10.5.0.11:5000|
| notification_ms   | `notification_ms`        |10.5.0.11:5001|
| park_ms           | `park_ms`                |10.5.0.11:5002|
| payment_ms        | `payment_ms`             |10.5.0.11:5003|
| ui_ms             | `ui_ms`                  |10.5.0.16:80/443|
| dbms              | `dbms`                   |10.5.0.10:3306|

The ui_ms is also exposed to the external network on the port 80 (HTTP) and 443 (HTTPS)