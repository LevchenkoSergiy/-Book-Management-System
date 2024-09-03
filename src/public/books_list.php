<?php
require __DIR__ . '/../vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
use Mylibrary\App\Classes\BookHandler;

$ads = BookHandler::fetchAll();
?>

<html>
<head>
	<title>Books List</title>
</head>
<body>
	<h1>Список книг</h1>
	<a href="index.php">Додати книгу</a>
	<br/>
	<br/>
    <a href="search.php">Знайти книгу</a>
    <br/>
    <br/>
	<table style="width: 60%;" border="1">
		<thead>
			<tr>
				<th style="width: 30%;">ID</th>
				<th style="width: 30%;">Назва книги</th>
				<th style="width: 30%;">Автор</th>
				<th style="width: 30%;">Рік видання</th>
				<th style="width: 30%;">Дата створення оголошення</th>
				<th style="width: 10%;">Дії</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($ads as $ad) { ?>
			<tr>
				<th><?php echo $ad['id']; ?></th>
				<th><?php echo $ad['title']; ?></th>
				<th><?php echo $ad['author']; ?></th>
				<th><?php echo $ad['year']; ?></th>
				<th><?php echo $ad['created_at']; ?></th>
                <td><a href="single_ad.php?id=<?php echo $ad['id'];?>">Переглянути</a> |
                    <a href="delete_ed.php?id=<?php echo $ad['id'];?>">Видалити</a></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</body>