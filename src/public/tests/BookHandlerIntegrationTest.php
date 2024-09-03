<?php

namespace Mylibrary\App\Tests;

use PHPUnit\Framework\TestCase;
use Mylibrary\App\Classes\Book;
use Mylibrary\App\Classes\BookHandler;
use Mylibrary\App\Classes\Database;

class BookHandlerIntegrationTest extends TestCase
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

	public function testInsertBookToDatabase()
	{
		$book = new Book('Тестова книга', 'Автор', '2024', ['tmp_name' => '/tmp/test.jpg', 'error' => UPLOAD_ERR_OK]);
		$handler = new BookHandler($book);

		$handler->validateInput();
		$handler->saveDb();

		$savedBook = $this->dbConnection->query("SELECT * FROM ads WHERE title = 'Тестова книга'")->fetch_assoc();

		$this->assertNotEmpty($savedBook, 'Книга повинна бути додана до бази даних.');
		$this->assertEquals('Тестова книга', $savedBook['title']);
	}
}
