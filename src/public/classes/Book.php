<?php
namespace Mylibrary\App\Classes;

class Book
{
	protected $title;
	protected $author;
	protected $year;
	protected $coverImage;

	public function __construct($title, $author, $year, $coverImage)
	{
		$title = htmlspecialchars(strip_tags($title));
		$author = htmlspecialchars(strip_tags($author));
		$year = htmlspecialchars(strip_tags($year));

		$this->title = $title;
		$this->author = $author;
		$this->year = $year;
		$this->coverImage = $coverImage;
	}
	public function getTitle()
	{
		return $this->title;
	}
	public function getAuthor()
	{
		return $this->author;
	}
	public function getYear()
	{
		return $this->year;
	}
	public function getCoverImage()
	{
		return $this->coverImage;
	}
}