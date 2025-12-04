# EzParking

Laboratory of Advanced Programming project.

## Authors

-   Matteo Ventali (1985026)
-   Serena Ragaglia (1941007)
-   Valerio Spagnoli (1973484)
-   Pierluca Grasso (1950186)
-   Federico De Lullo (1935510)

## Accounts
| Email           | Password  |   Role
|------------------|---------------------------|---------------------|
| matteo.ventali@gmail.com       | ezparking        |admin|
| valerio.spagnoli@gmail.com     | ezparking        |admin|
| serena.ragaglia@gmail.com      | ezparking        |user|
| pierluca.grasso@gmail.com      | ezparking        |user|
| federico.delullo@gmail.com     | ezparking        |user|

------------------------------------------------------------------------

## Project Description

EzParking is a parking management system based on microservices. The
goal of this system is to create a parking sharing application that
enables users to find and share parking spaces in urban areas in order
to reduce traffic congestion and environmental impact, promoting
collaboration among users.

It includes modules for user accounts, notifications, payments, and
parking management, with a web-based user interface.

------------------------------------------------------------------------

## Microservices Structure

-   `account_ms` -- user account management\
-   `notification_ms` -- notifications management\
-   `park_ms` -- parking management\
-   `payment_ms` -- payments management\
-   `ui_ms` -- web user interface\
-   `dbms` -- central database with initialization scripts

------------------------------------------------------------------------

## Generate SSL Keys and Certificate (Required for HTTPS in `ui_ms`)

The web interface (`ui_ms`) uses HTTPS and requires:

-   `ezparking.crt` -- public certificate
-   `keyfile.key` -- private key

These files **must be placed in**:

    source/ui_ms/ssl/

### Generate Certificate and Key with OpenSSL

Run this command in your terminal:

``` bash
openssl req -x509 -nodes -newkey rsa:2048   -keyout keyfile.key   -out ezparking.crt   -days 365   -subj "/C=IT/ST=Italy/L=Rome/O=EzParking/OU=Dev/CN=localhost"
```

After generation, move the files into:
  `source/ui_ms/ssl/ezparking.crt`
  `source/ui_ms/ssl/keyfile.key`

They are automatically used by the Apache configuration in
`ui_ms/conf/default.conf`.

------------------------------------------------------------------------

## How to Start the System

The project uses Docker and Docker Compose to orchestrate microservices.

1.  Ensure Docker and Docker Compose are installed.

2.  Generate SSL keys and certificates (see section above).

3.  Put the GMAIL password per app inside the file `source/notification_ms/password.key`

4.  From the main project folder, run:

``` bash
cd source
docker-compose up -d
```

To rebuild containers explicitly:

``` bash
docker-compose up -d --build
```

5.  Check container status:

``` bash
docker-compose ps -a
```

## How to Stop the System

Stop and remove containers (data preserved):

``` bash
docker-compose down
```

Stop and delete also database volumes:

``` bash
docker-compose down -v
```

## Rebuilding After Updates

If microservices or Dockerfiles are updated:

``` bash
docker-compose build
docker-compose up -d
```

------------------------------------------------------------------------
## System IP Topology
Docker Compose manages a private internal network for the microservices. Each container can be accessed using its service name:

| Service           | Docker Internal Host  |   IP:Port
|------------------|---------------------------|---------------------|
| account_ms        | `account_ms`             |10.5.0.11:5000|
| notification_ms   | `notification_ms`        |10.5.0.12:5001|
| park_ms           | `park_ms`                |10.5.0.13:5002|
| payment_ms        | `payment_ms`             |10.5.0.14:5003|
| ui_ms             | `ui_ms`                  |10.5.0.16:80/443|
| dbms              | `dbms`                   |10.5.0.10:3306|

The ui_ms is also exposed to the external network on the port 80 (HTTP) and 443 (HTTPS)
