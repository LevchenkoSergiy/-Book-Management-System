<?php

require __DIR__ . '/../vendor/autoload.php';

use Mylibrary\App\Classes\BookHandler;

if (!isset($_GET['id'])) {
	header("Location: books_list.php");
	exit;
}

$ad = BookHandler::fetchById($_GET['id']);

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Редагування книг</title>
</head>
<body>
<a href='books_list.php'>Список книг</a><br/>
<h1>Редагування книг</h1>
<form action="book_handler.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $ad['id']; ?>">
    <label for="title">Назва книги</label>
    <input type="text" id="title" name="title" value="<?php echo $ad['title']; ?>"><br/><br/>
    <label for="author">Автор</label>
    <input type="text" id="author" name="author" value="<?php echo $ad['author']; ?>"><br/><br/>
    <label for="year">Рік видання</label>
    <input type="text" id="year" name="year" value="<?php echo $ad['year']; ?>"><br/><br/>
    <label for="coverImage">Обкладинка</label>
    <input type="file" id="coverImage" name="coverImage"><br/><br/>
    <button type="submit">Надіслати</button>
</form>
</body>
</html>
