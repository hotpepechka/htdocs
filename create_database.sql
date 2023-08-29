-- Создание таблицы для записей
CREATE TABLE posts (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255),
    body TEXT
);

-- Создание таблицы для комментариев
CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    post_id INT REFERENCES posts(id),
    name VARCHAR(255),
    email VARCHAR(255),
    body TEXT
);
