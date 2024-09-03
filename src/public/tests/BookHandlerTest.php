<?php

use PHPUnit\Framework\TestCase;
use Mylibrary\App\Classes\Book;
use Mylibrary\App\Classes\BookHandler;

class BookHandlerTest extends TestCase
{
	public function testValidateTitle()
	{
		$book = new Book("", "Автор", "2024", ["error" => UPLOAD_ERR_OK]);
		$bookHandler = new BookHandler($book);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage("Назва не може бути порожньою");

		$bookHandler->validateInput();
	}

	public function testValidateAuthor()
	{
		$book = new Book("Назва", "", "2024", ["error" => UPLOAD_ERR_OK]);
		$bookHandler = new BookHandler($book);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage("Автор не може бути порожнім");

		$bookHandler->validateInput();
	}

	public function testValidateYear()
	{
		$book = new Book("Назва", "Автор", "Тест", ["error" => UPLOAD_ERR_OK]);
		$bookHandler = new BookHandler($book);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage("Рік має бути 4-значним числом");

		$bookHandler->validateInput();
	}

	public function testValidateCoverImage()
	{
		$book = new Book("Назва", "Автор", "2024", ["error" => UPLOAD_ERR_NO_FILE]);
		$bookHandler = new BookHandler($book);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage("Завантажте обкладинку");

		$bookHandler->validateInput();
	}
}
