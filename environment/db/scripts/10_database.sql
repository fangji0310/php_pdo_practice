CREATE DATABASE IF NOT EXISTS demo DEFAULT CHARSET utf8mb4 DEFAULT COLLATE 'utf8mb4_bin';
CREATE USER demo@localhost IDENTIFIED BY 'demo';
CREATE USER demo IDENTIFIED BY 'demo';
grant select, insert, update, delete on demo.* to demo@localhost;
grant select, insert, update, delete on demo.* to demo@'%';
