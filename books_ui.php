<?php
$pdo = new PDO("mysql:host=localhost;dbname=library;port=3307", "root", "");
$books = $pdo->query("SELECT * FROM books")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Books CRUD</title>
    <style>
        form, table { margin: 20px auto; }
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 8px; }
        input[type="text"] { padding: 5px; }
    </style>
</head>
<body>
    <h2 align="center">Books Management</h2>

    <form method="post" action="books_crud.php">
        <input type="text" name="title" placeholder="Book Title" required>
        <input type="text" name="author" placeholder="Author" required>
        <input type="submit" name="create" value="Add Book">
    </form>

    <table>
        <tr><th>ID</th><th>Title</th><th>Author</th><th>Actions</th></tr>
        <?php foreach ($books as $book): ?>
        <tr>
            <form method="post" action="books_crud.php">
                <td><?= $book['id'] ?></td>
                <td><input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>"></td>
                <td><input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>"></td>
                <td>
                    <input type="hidden" name="id" value="<?= $book['id'] ?>">
                    <input type="submit" name="update" value="Update">
                    <a href="books_crud.php?delete=<?= $book['id'] ?>" onclick="return confirm('Delete this book?')">Delete</a>
                </td>
            </form>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
