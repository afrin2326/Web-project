<?php
$errors = [];
$name = $email = "";
$targetFile = "";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    $name = trim($_POST["name"] ?? "");
    if ($name === "") {
        $errors[] = "Full Name is required.";
    }

    // Validate email
    $email = trim($_POST["email"] ?? "");
    if ($email === "") {
        $errors[] = "Email Address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate and handle file upload
    if (isset($_FILES["profile"]) && $_FILES["profile"]["error"] === 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $originalFilename = basename($_FILES["profile"]["name"]);
        $uniqueName = uniqid() . "_" . $originalFilename;
        $targetFile = $targetDir . $uniqueName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        } else {
            if (!move_uploaded_file($_FILES["profile"]["tmp_name"], $targetFile)) {
                $errors[] = "Failed to upload the profile image.";
            }
        }
    } else {
        $errors[] = "Please upload a profile image.";
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $conn = new mysqli("localhost", "root", "", "library", 3307);
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO admins (name, email, profile_image) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $name, $email, $targetFile);
            $stmt->execute();
            $stmt->close();
            $conn->close();

            // Redirect to welcome page
            header("Location: welcome.php?name=" . urlencode($name) . "&image=" . urlencode($targetFile));
            exit();
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}
?>

<!-- Error Display -->
<!DOCTYPE html>
<html>
<head>
    <title>Registration Error</title>
    <link rel="stylesheet" href="registrationStyle.css">
</head>
<body>
<div class="container">
    <h2>Registration Failed</h2>
    <?php if (!empty($errors)): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <a href="registrationAdmin.html" class="back-btn">Back to Registration</a>
</div>
</body>
</html>
