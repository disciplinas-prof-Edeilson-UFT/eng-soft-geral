CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(15) UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    profile_pic varchar(255),NULL,
    bio TEXT
);


CREATE TABLE user_photos (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    photo_url VARCHAR(255) NOT NULL,
    upload_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    description TEXT NULL,
    PRIMARY KEY (id),
    KEY user_idx (user_id),
    FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
);


CREATE TABLE seguidores (
    id INT(11) NOT NULL AUTO_INCREMENT,
    usuario_id INT(11) NOT NULL,
    seguindo_id INT(11) NOT NULL,
    data_seguindo TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY usuario_idx (usuario_id),
    KEY seguindo_idx (seguindo_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (seguindo_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
