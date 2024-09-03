<?php

namespace Mylibrary\App\Tests;

use PHPUnit\Framework\TestCase;
use Mylibrary\App\Classes\Book;
use Mylibrary\App\Classes\BookHandler;
use Mylibrary\App\Classes\Database;

class BookHandlerFunctionalTest extends TestCase
{
	protected $dbConnection;

	protected function setUp(): void
	{
		$this->dbConnection = Database::getInstance()->getConnection();
		$this->dbConnection->query("CREATE TABLE IF NOT EXISTS ads (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255),
            author VARCHAR(255),
            year VARCHAR(4),
            coverImage VARCHAR(255)
        )");
	}

	protected function tearDown(): void
	{
		$this->dbConnection->query("DROP TABLE ads");
	}

	public function testDeleteBookFromDatabase()
	{
		// Додаємо книгу
		$book = new Book('Книга для видалення', 'Автор', '2024', ['tmp_name' => '/tmp/test.jpg', 'error' => UPLOAD_ERR_OK]);
		$handler = new BookHandler($book);
		$handler->validateInput();
		$handler->saveDb();

		// Видаляємо книгу
		BookHandler::deleteById($handler->getId());

		$deletedBook = $this->dbConnection->query("SELECT * FROM ads WHERE id = " . $handler->getId())->fetch_assoc();

		$this->assertEmpty($deletedBook, 'Книга повинна бути видалена з бази даних.');
	}
}