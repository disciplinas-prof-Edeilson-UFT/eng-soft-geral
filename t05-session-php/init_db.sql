CREATE SCHEMA IF NOT EXISTS conex;

CREATE TABLE conex.users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(15),
    password_hash VARCHAR(255) NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    bio TEXT
);

CREATE INDEX idx_users_email ON conex.users(email);
CREATE INDEX idx_users_username ON conex.users(username);