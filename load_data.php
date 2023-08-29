<?php
$host = "localhost";
$port = "5432";
$dbname = "PhpTest";
$user = "postgres";
$password = "root";

// Создание подключения к БД
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Загрузка данных о записях
$posts_json = file_get_contents("https://jsonplaceholder.typicode.com/posts");
$posts = json_decode($posts_json, true);

foreach ($posts as $post) {
    $id = $post["id"];
    $title = pg_escape_string($post["title"]);
    $body = pg_escape_string($post["body"]);

    $query = "INSERT INTO posts (id, title, body) VALUES ($id, '$title', '$body')";
    pg_query($conn, $query);

    // Загрузка комментариев для каждой записи
    $comments_json = file_get_contents("https://jsonplaceholder.typicode.com/posts/$id/comments");
    $comments = json_decode($comments_json, true);

    foreach ($comments as $comment) {
        $comment_id = $comment["id"];
        $name = pg_escape_string($comment["name"]);
        $email = pg_escape_string($comment["email"]);
        $comment_body = pg_escape_string($comment["body"]);

        $query = "INSERT INTO comments (id, post_id, name, email, body) VALUES ($comment_id, $id, '$name', '$email', '$comment_body')";
        pg_query($conn, $query);
    }
}

// Закрытие подключения
pg_close($conn);

echo "Загружено " . count($posts) . " записей и " . count($comments) . " комментариев";
?>
