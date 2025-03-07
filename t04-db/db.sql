CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(15) UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    profile_pic_url VARCHAR(255),
    username VARCHAR(255) NOT NULL,
    count_followers INTEGER DEFAULT 0,
    count_following INTEGER DEFAULT 0,
    bio TEXT
);
