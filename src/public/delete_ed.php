<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

use Mylibrary\App\Classes\BookHandler;

if (!isset($_GET['id'])) {
	header('Location: books_list.php');
	exit;
}
try {
	BookHandler::deleteById($_GET['id']);
} catch (Exception $ex) {
	echo "Помилка: " . $ex->getMessage();
	echo "<br><a href='books_list.php'>Повернутися до форми</a>";
	exit;
}

header("Location: books_list.php");
exit;
