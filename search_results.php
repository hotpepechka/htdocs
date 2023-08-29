<?php
$host = "localhost";
$port = "5432";
$dbname = "PhpTest";
$user = "postgres";
$password = "root";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

$search = pg_escape_string($_GET["search"]);

$query = "SELECT posts.title, comments.body
          FROM posts
          INNER JOIN comments ON posts.id = comments.post_id
          WHERE comments.body ILIKE '%$search%'
          ORDER BY posts.title";

$result = pg_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результат поиска</title>
</head>
<body>
    <div>
    <h1>Результаты поиска</h1>
    <?php if (pg_num_rows($result) > 0): ?>
        <ul>
            <?php while ($row = pg_fetch_assoc($result)): ?>
                <li>
                    <strong>Заголовок записи:</strong> <?php echo htmlspecialchars($row["title"]); ?><br>
                    <strong>Комментарий:</strong> <?php echo htmlspecialchars($row["body"]); ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Ничего не найдено.</p>
    <?php endif; ?>
    </div>
</body>
</html>

