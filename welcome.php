<?php
$name = $_GET['name'] ?? "Admin";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Welcome</title>
  <link rel="stylesheet" href="registrationStyle.css" />
</head>
<body>
  <div class="container">
    <h1>Welcome, <?= htmlspecialchars($name) ?>!</h1>
    <p>You have successfully registered.</p>
    <a href="books_ui.php" class="next-btn">Go to Book Management</a>
  </div>
</body>
</html>
