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
  CONSTRAINT fk_following FOREIGN KEY (following_id) REFERENCES conex.users(id) ON DELETE CASCADE,
  CONSTRAINT fk_follower  FOREIGN KEY (follower_id)  REFERENCES conex.users(id) ON DELETE CASCADE,
  CONSTRAINT unique_follow UNIQUE (following_id, follower_id)
);

CREATE TABLE conex.posts (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL,
  photo_url VARCHAR(255) NOT NULL,
  upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  description TEXT,
  CONSTRAINT fk_post_user FOREIGN KEY (user_id) REFERENCES conex.users(id) ON DELETE CASCADE
);

CREATE INDEX idx_users_email ON conex.users(email);
CREATE INDEX idx_users_username ON conex.users(username);
CREATE INDEX idx_posts_user_id ON conex.posts(user_id);
CREATE INDEX idx_follow_following ON conex.follow(following_id);
CREATE INDEX idx_follow_follower ON conex.follow(follower_id);