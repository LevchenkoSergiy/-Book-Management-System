<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . '/../vendor/autoload.php';
use Mylibrary\App\Classes\BookHandler;

$searchTerm = $_GET['q'] ?? '';

$ads = [];
if ($searchTerm) {
	$ads = BookHandler::search($searchTerm);
}
?>

<html>
<head>
	<title>Пошук книг</title>
</head>
<body>
	<h1>Пошук книг</h1>
	<form method="get" action="">
		<label for="q">Введіть назву книги:</label>
		<input type="text" name="q" id="q" value="<?php echo htmlspecialchars($searchTerm); ?>" required>
		<button type="submit">Пошук</button>
	</form>
	<br/>
    <a href='books_list.php'>Повернутися до списку книг</a><br/>
	<br/>
	<?php if ($ads) { ?>
		<table style="width: 60%;" border="1">
			<thead>
				<tr>
					<th style="width: 10%;">ID</th>
					<th style="width: 30%;">Назва книги</th>
					<th style="width: 30%;">Автор</th>
					<th style="width: 10%;">Рік видання</th>
					<th style="width: 10%;">Дата створення оголошення</th>
					<th style="width: 10%;">Дії</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($ads as $ad) { ?>
				<tr>
					<td><?php echo $ad['id']; ?></td>
					<td><?php echo $ad['title']; ?></td>
					<td><?php echo $ad['author']; ?></td>
					<td><?php echo $ad['year']; ?></td>
					<td><?php echo $ad['created_at']; ?></td>
					<td><a href="single_ad.php?id=<?php echo $ad['id'];?>">Переглянути</a> | <a href="delete_ad.php?id=<?php echo $ad['id'];?>">Видалити</a></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	<?php } else { ?>
		<p>Немає результатів за вашим запитом.</p>
	<?php } ?>
</body>
</html>
