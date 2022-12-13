FROM mysql:latest
ENV MYSQL_DATABASE carParking
COPY init.sql /docker-entrypoint-initdb.d/
EXPOSE 3306