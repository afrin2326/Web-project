<?php
$pdo = new PDO("mysql:host=localhost;dbname=library;port=3307", "root", "");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['create'])) {
        $stmt = $pdo->prepare("INSERT INTO books (title, author) VALUES (?, ?)");
        $stmt->execute([$_POST['title'], $_POST['author']]);
    } elseif (isset($_POST['update'])) {
        $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ? WHERE id = ?");
        $stmt->execute([$_POST['title'], $_POST['author'], $_POST['id']]);
    }
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
}

header("Location: books_ui.php");
exit();
