<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Додати книгу</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<a href='books_list.php'>Список книг</a><br/>
    <h1>Додати книгу</h1>
    <form action="book_handler.php" method="post" enctype="multipart/form-data"
        <label for="title">Назва книги</label>
            <input type="text" id="title" name="title"><br/><br/>
        <label for="title">Автор</label>
            <input type="text" id="author" name="author"><br/><br/>
        <label for="title">Рік видання</label>
            <input type="text" id="year" name="year"><br/><br/>
        <label for="title">Обкладинка</label>
            <input type="file" id="coverImage" name="coverImage"><br/><br/>

        <div class="g-recaptcha" data-sitekey="6LcctyEqAAAAAKCHmlLdulCjJgB0wYB-8QpfBtmm"></div>

        <button type="submit">Надіслати</button>
    <form/>
</body>
</html>



