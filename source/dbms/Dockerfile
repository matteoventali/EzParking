# Dockerfile
FROM mariadb:11.4

# Set the default character set
ENV MARIADB_CHARACTER_SET_SERVER=utf8mb4
ENV MARIADB_COLLATION_SERVER=utf8mb4_unicode_ci

COPY event_scheduler.cnf /etc/mysql/conf.d/event_scheduler.cnf
COPY event_scheduler.cnf /etc/mysql/mariadb.conf.d/event_scheduler.cnf

# Init script executed only the first time the container is created
COPY init/ /docker-entrypoint-initdb.d/
# Expose the port
EXPOSE 3306
