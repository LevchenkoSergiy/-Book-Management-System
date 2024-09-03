<?php

namespace Mylibrary\App\Classes;

use Exception;


class BookHandler
{
	protected $book;
	private $filepath;
	private $filetype;
	protected $allowedTypes;
	protected $newFilepath;
	protected $imageIsSaved = false;
	protected $id;

	// Конструктор, який приймає об'єкт Book і зберігає його у властивості
	public function __construct(Book $book, $id = null)
	{
		$this->book = $book;
		$this->id = $id;
	}

	public function validateInput()
	{
		$title = $this->book->getTitle();
		$author = $this->book->getAuthor();
		$year = $this->book->getYear();
		$coverImage = $this->book->getCoverImage();

		$this->validateTitle($title);
		$this->validateAuthor($author);
		$this->validateYear($year);
		$this->validateCoverImage($coverImage);
	}
	public function getId()
	{
		return $this->id;
	}

	// Перевірка назви
	private function validateTitle($title)
	{
		if (empty($title)) {
			throw new Exception("Назва не може бути порожньою");
		}
	}

	// Перевірка автора
	private function validateAuthor($author)
	{
		if (empty($author)) {
			throw new Exception("Автор не може бути порожнім");
		}
	}

	// Перевірка року
	private function validateYear($year)
	{
		if (empty($year)) {
			throw new Exception("Рік не може бути порожнім");
		}
		if (!preg_match('/^\d{4}$/', $year)) {
			throw new Exception("Рік має бути 4-значним числом");
		}
	}

	// Перевірка обкладинки
	private function validateCoverImage($coverImage)
	{
		if ($coverImage ['error'] !== UPLOAD_ERR_OK) {
			throw new Exception("Завантажте обкладинку");
		}
	}

	// Перевірка розміру файлу (обмеження 3 MB)
	public function sizeImageCheck($coverImage)
	{
		$this->filepath = $coverImage['tmp_name'];
		$fileSize = filesize($this->filepath);

		if ($fileSize === 0) {
			throw new Exception("Файл порожній.");
		}
		if ($fileSize > 3145728) { // 3 MB
			throw new Exception("Файл занадто великий.");
		}
	}

	// Перевірка типу файлу
	public function typeImageCheck()
	{
		$fileinfo = finfo_open(FILEINFO_MIME_TYPE);
		$this->filetype = finfo_file($fileinfo, $this->filepath);

		$this->allowedTypes = [
			'image/png' => 'png',
			'image/jpeg' => 'jpg',
			'image/gif' => 'gif'
		];

		if (!array_key_exists($this->filetype, $this->allowedTypes)) {
			throw new Exception("На жаль, дозволені лише файли JPG, JPEG, PNG та GIF.");
		}
	}

	public function saveFileImage($coverImage)

	{
		$uploads_dir = 'uploads';

		if (!file_exists($uploads_dir)) {
			mkdir($uploads_dir, 0777, true);
		}

		$filename = basename($coverImage["name"]);
		$extension = $this->allowedTypes[$this->filetype];
		$this->newFilepath = $uploads_dir . "/" . $filename . "." . $extension;
		$this->imageIsSaved = true;
		if (!move_uploaded_file($this->filepath, $this->newFilepath)) {
			throw new \Exception("Не вдалося зберегти завантажену обкладинку");
		}

	}
	public function saveDb()
	{
		if ($this->id === null) {
			$this->insertDb();
		} else {
			$this->updateDb();
		}
	}
	public static function deleteById($id)
	{
		$ad = self::fetchById($id);
		$adConnection = Database::getInstance()->getConnection();
		$stmt = $adConnection->prepare("DELETE FROM ads WHERE id = ?");

		if ($stmt === false) {
			throw new \Exception("Помилка підготовки запиту на видалення оголошення з бази даних");
		}
		$stmt->bind_param("i", $id);
		if (!$stmt->execute()) {
			throw new \Exception("Помилка видалення оголошення з бази даних");
		}

		if (file_exists($ad['coverImage'])) {
			unlink($ad['coverImage']);
		}
	}
	public static function fetchAll(
	)
	{
		$dbConnection = Database::getInstance()->getConnection();

		$result = $dbConnection->query("SELECT * FROM ads");

		if ($result === false) {
			throw new \Exception("Помилка отримання оголошення з бази даних");
		}

		return $result->fetch_all(MYSQLI_ASSOC);
	}
	public static function fetchById($id)
	{
		$dbConnection = Database::getInstance()->getConnection();

		$stmt = $dbConnection->prepare("SELECT * FROM ads WHERE id = ?");
		if ($stmt === false) {
			throw new \Exception("Помилка підготовки оголошення з бази даних");
		}
		$stmt->bind_param("i", $id);

		if (!$stmt->execute()) {
			throw new \Exception("Помилка отримання оголошення з бази даних");
		}
		$result = $stmt->get_result();
		$ad = $result->fetch_assoc();

		if ($ad === null) {
			throw new \Exception("Оголошення з id = {$id} не знайдено");
		}

		return $ad;
	}
	public static function search($term)
	{
		$dbConnection = Database::getInstance()->getConnection();
		$term = "%$term%";
		$stmt = $dbConnection->prepare("SELECT * FROM ads WHERE title LIKE ?");
		if ($stmt === false) {
			throw new \Exception("Помилка підготовки запиту на пошук оголошень");
		}
		$stmt->bind_param("s", $term);
		if (!$stmt->execute()) {
			throw new \Exception("Помилка пошук оголошень");
		}
		$result = $stmt->get_result();
		$ads = $result->fetch_all(MYSQLI_ASSOC);
		return $ads;
	}

	private function insertDb()
	{
		$dbConnection = Database::getInstance()->getConnection();

		$stmt = $dbConnection->prepare("INSERT INTO ads (title, author, year, coverImage) VALUES (?, ?, ?, ?)");
		if ($stmt === false) {
			throw new Exception("Помилка підготовки запиту на вставку в базу даних: " . $dbConnection->error);
		}

		$title = $this->book->getTitle();
		$author = $this->book->getAuthor();
		$year = $this->book->getYear();
		$coverImage = $this->newFilepath;

		$stmt->bind_param("ssss", $title, $author, $year, $coverImage);

		if ($stmt->execute() === false) {
			throw new Exception("Помилка виконання запиту на вставку в базу даних: " . $stmt->error);
		}

		$this->id = $stmt->insert_id;
	}

	private function updateDb()
	{
		$dbConnection = Database::getInstance()->getConnection();
		$stmt = $dbConnection->prepare("UPDATE ads SET title=?, author=?, year=?, coverImage=? WHERE id=?");
		if ($stmt === false) {
			throw new \Exception("Помилка підготовки запиту до бази даних");
		}

		$title = $this->book->getTitle();
		$author = $this->book->getAuthor();
		$year = $this->book->getYear();
		$coverImage = $this->newFilepath;

		$stmt->bind_param("ssssi", $title, $author, $year, $coverImage, $this->id);
		if (!$stmt->execute()) {
			throw new \Exception("Помилка виконання запиту до бази даних");
		}
	}
	private function verifyReCaptcha($recaptchaResponse) {
		$secretKey = '6LcctyEqAAAAAPDI75kiIYu8sT1y-cAkUbtR_jpj';
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = [
			'secret' => $secretKey,
			'response' => $recaptchaResponse
		];

		$options = [
			'http' => [
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			]
		];

		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$response = json_decode($result);

		return $response->success;
	}
	public function validateReCaptcha($recaptchaResponse)
	{
		if (!$this->verifyReCaptcha($recaptchaResponse)) {
			throw new Exception("reCAPTCHA перевірка не пройдена. Спробуйте ще раз.");
		}
	}
}