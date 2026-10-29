-- Example setup for the lab. Review before running on an existing database.
-- Run as a MariaDB administrator on the Database Server.

CREATE DATABASE IF NOT EXISTS login_app;

CREATE TABLE IF NOT EXISTS login_app.users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- This account is for the lab's existing plaintext test credential.
-- Production systems should store password hashes, not plaintext passwords.
INSERT INTO login_app.users (username, password)
VALUES ('student', 'test123')
ON DUPLICATE KEY UPDATE username = VALUES(username);

-- Create the application account if it does not already exist.
CREATE USER IF NOT EXISTS 'webapp'@'10.61.0.247'
IDENTIFIED BY 'REPLACE_WITH_DB_PASSWORD';

GRANT SELECT, INSERT ON login_app.* TO 'webapp'@'10.61.0.247';

FLUSH PRIVILEGES;

-- Verify separately in MariaDB:
-- SHOW GRANTS FOR 'webapp'@'10.61.0.247';
-- USE login_app;
-- SELECT id, username FROM users;
