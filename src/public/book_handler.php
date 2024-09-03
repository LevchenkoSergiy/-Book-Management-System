<?php

require __DIR__ . '/../vendor/autoload.php';
use Mylibrary\App\Classes\BookHandler;
use Mylibrary\App\Classes\Book;
use Mylibrary\App\Classes\InvalidInputException;

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$recaptchaResponse = $_POST['g-recaptcha-response'];

		$id = $_POST['id'] ?? null;
		$title = $_POST['title'];
		$author = $_POST['author'];
		$year = $_POST['year'];
		$coverImage = $_FILES['coverImage'];

		$book = new Book($title, $author, $year, $coverImage);
		$bookhandler = new BookHandler($book, $id);

		$bookhandler->validateReCaptcha($recaptchaResponse);
		$bookhandler->validateInput();
		$bookhandler->sizeImageCheck($coverImage);
		$bookhandler->typeImageCheck();
		$bookhandler->saveFileImage($coverImage);
		$bookhandler->saveDb();

		header("Location: single_ad.php?id=" . $bookhandler->getId());
		exit;
	}
} catch (InvalidInputException $e) {
	echo "Виникла помилка: " . $e->getMessage();
	echo "<br><a href='index.php'>Повернутися на початок</a>";
	exit;
} catch (Exception $e) {
	echo "Помилка: " . $e->getMessage();
	echo "<br><a href='index.php'>Повернутися на початок</a>";
	exit;
}

