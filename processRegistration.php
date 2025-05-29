<?php
$errors = [];
$name = $email = "";
$uploadDir = "uploads/";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate inputs
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");

    if ($name === "") $errors[] = "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email address.";

    // Validate file upload
    if (!isset($_FILES["fileToUpload"]) || $_FILES["fileToUpload"]["error"] !== 0) {
        $errors[] = "File upload failed or no file selected.";
    } else {
        $fileName = basename($_FILES["fileToUpload"]["name"]);
        $targetFile = $uploadDir . $fileName;

        // Optional: validate file type/size
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (!in_array($fileType, ['jpg', 'jpeg', 'png', 'pdf'])) {
            $errors[] = "Only JPG, PNG, or PDF files are allowed.";
        } elseif ($_FILES["fileToUpload"]["size"] > 2 * 1024 * 1024) {
            $errors[] = "File size must be under 2MB.";
        }
    }

    if (empty($errors)) {
        // Save file
        if (!is_dir($uploadDir)) mkdir($uploadDir);
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile);

        // Save to database
        $conn = new mysqli("localhost", "root", "", "library", 3307); // No password
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

        $stmt = $conn->prepare("INSERT INTO admins (name, email, file_path) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $targetFile);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        header("Location: welcome.php?name=" . urlencode($name));
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration</title>
    <link rel="stylesheet" href="registrationStyle.css">
</head>
<body>
    <h2>Register Admin</h2>

    <?php if (!empty($errors)): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $err) echo "<li>$err</li>"; ?>
        </ul>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        Name: <input type="text" name="name" value="<?= htmlspecialchars($name) ?>"><br><br>
        Email: <input type="text" name="email" value="<?= htmlspecialchars($email) ?>"><br><br>
        Upload File: <input type="file" name="fileToUpload"><br><br>
