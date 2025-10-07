# Dockerfile
FROM mariadb:11.4

# Set the default character set (opzionale ma consigliato)
ENV MARIADB_CHARACTER_SET_SERVER=utf8mb4
ENV MARIADB_COLLATION_SERVER=utf8mb4_unicode_ci

# Copia gli script di inizializzazione
COPY init/ /docker-entrypoint-initdb.d/

# Espone la porta
EXPOSE 3306
