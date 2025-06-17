CREATE SCHEMA IF NOT EXISTS conex;

CREATE TABLE conex.users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(15),
    password_hash VARCHAR(255) NOT NULL,
    profile_pic_url VARCHAR(255),
    username VARCHAR(255) UNIQUE NOT NULL,
    count_followers INTEGER DEFAULT 0,
    count_following INTEGER DEFAULT 0,
    bio TEXT
);

CREATE TABLE conex.follow (
    id SERIAL PRIMARY KEY,
    following_id INTEGER NOT NULL,
    follower_id INTEGER NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (following_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE
    UNIQUE(following_id, follower_id) 
);

CREATE TABLE conex.posts (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    photo_url VARCHAR(255) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    description TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_users_email ON conex.users(email);
CREATE INDEX idx_users_username ON conex.users(username);
CREATE INDEX idx_posts_user_id ON posts(user_id);
CREATE INDEX idx_follow_following_id ON follow(following_id);
CREATE INDEX idx_follow_follower_id ON follow(follower_id);