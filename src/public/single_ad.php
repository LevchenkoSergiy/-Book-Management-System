<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

use Mylibrary\App\Classes\BookHandler;



if (!isset($_GET['id'])) {
	header("Location: books_list.php");
	exit;
}

$ad = BookHandler::fetchById($_GET['id']);

echo "<h1>Ваша книга</h1>";
echo "<br><a href='books_list.php'>Повернутися до списку книг</a><br>";
echo "<br><a href='delete_ed.php?id=" . $ad['id'] . "'>Видалити книгу</a><br>";
echo "<br><a href='index.php'>Повернутися на початок</a><br><br>";
echo "<br><a href='edit_form.php?id=" . $ad['id'] . "'>Редагування книги</a><br><br>";
echo "Назва книги: " . $ad['title'] . "<br>";
echo "Автор: " . $ad['author'] . "<br>";
echo "Рік видання: " . $ad['year'] . "<br>";
echo "Обкладинка: <img src='" . $ad['coverImage'] . "'/><br>";

