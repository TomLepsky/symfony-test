#!/usr/bin/env bash

mysql --user=root --password=root <<-SQL
    CREATE DATABASE IF NOT EXISTS '$DB_DATABASE';
    GRANT ALL PRIVILEGES ON \`testing%\`.* TO '$MYSQL_USER'@'%';
SQL
