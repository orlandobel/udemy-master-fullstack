DROP DATABASE IF EXISTS api_rest_laravel;
CREATE DATABASE IF NOT EXISTS api_rest_laravel;
USE api_rest_laravel;

CREATE TABLE users(
    id int(255) auto_increment not null,
    name varchar(50) not null,
    surname varchar(100),
    role varchar(20),
    email varchar(255) not null,
    password varchar(255) not null,
    description text,
    image varchar(255),
    created_at datetime DEFAULT NULL,
    updated_at datetime DEFAULT NULL,
    remember_token varchar(255),

    CONSTRAINT pk_users PRIMARY KEY (id)
)ENGINE=InnoDb;

CREATE TABLE categories(
    id int(255) auto_increment not null,
    name varchar(100),
    created_at datetime DEFAULT NULL,
    updated_at datetime DEFAULT NULL,

    CONSTRAINT pk_categories PRIMARY KEY (id)
)ENGINE=InnoDb;

CREATE TABLE posts(
    id int(255) auto_increment not null,
    user_id int(255) not null,
    category_id int(255) not null,
    title varchar(255) not null,
    content text not null,
    image varchar(255),
    created_at datetime DEFAULT NULL,
    updated_at datetime DEFAULT NULL,

    CONSTRAINT pk_posts PRIMARY KEY (id),
    CONSTRAINT fk_post_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_post_category FOREIGN KEY (category_id) REFERENCES categories(id)
)ENGINE=InnoDb;

INSERT INTO users (name, surname, role, email, password,description, created_at, updated_at) VALUES ('admin', 'admin', 'ROLE_ADMIN', 'admin@admin.com', 'admin', 'description admin', '2021-08-25 00:00:00', '2021-08-25 00:00:00');

INSERT INTO categories (name, created_at, updated_at) VALUES ('ordenadores', '2021-08-25 00:00:00', '2021-08-25 00:00:00');
INSERT INTO categories (name, created_at, updated_at) VALUES ('moviles y tablets', '2021-08-25 00:00:00', '2021-08-25 00:00:00');

INSERT INTO posts(user_id, category_id, title, content, created_at, updated_at) VALUES (1, 2, 'Samsung Galaxy S8', 'contenido Samsung Galaxy S8', '2021-08-25 00:00:00', '2021-08-25 00:00:00');
INSERT INTO posts(user_id, category_id, title, content, created_at, updated_at) VALUES (1, 1, 'Asus rog Strix', 'contenido Asus rog strix', '2021-08-25 00:00:00', '2021-08-25 00:00:00');
INSERT INTO posts(user_id, category_id, title, content, created_at, updated_at) VALUES (1, 1, 'MSI power', 'contenido MSI power', '2021-08-25 00:00:00', '2021-08-25 00:00:00');