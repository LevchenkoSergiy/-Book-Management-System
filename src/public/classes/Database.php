<?php

namespace Mylibrary\App\Classes;

class Database
{
	private $mysqli;
	private static $instance = null;

	public function __construct()
	{
		$this->mysqli = new \mysqli('mysql', 'default', 'secret', 'default');

		if ($this->mysqli->connect_errno) {
			die("Connection failed: " . $this->mysqli->connect_error);
		}
	}

	public static function getInstance()
	{
		if (self::$instance == null) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	public function getConnection()
	{
		return $this->mysqli;
	}
}
