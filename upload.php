<?php
$uploadDir = "uploads/";
$uploadOk = 1;
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["uploadedFile"]) && $_FILES["uploadedFile"]["error"] == 0) {
        $fileName = basename($_FILES["uploadedFile"]["name"]);
        $targetFile = $uploadDir . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Create uploads directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Allow certain file types
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($fileType, $allowedTypes)) {
            $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check file size (max 5MB)
        if ($_FILES["uploadedFile"]["size"] > 5 * 1024 * 1024) {
            $message = "Sorry, your file is too large (Max 5MB).";
            $uploadOk = 0;
        }

        if ($uploadOk) {
            if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $targetFile)) {
                $message = "File uploaded successfully!";
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $message = "No file selected or upload error.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Result</title>
  <link rel="stylesheet" href="adminNext.css">
</head>
<body>
  <div class="container">
    <h1>Upload Result</h1>
    <p><?= htmlspecialchars($message) ?></p>

    <?php if ($uploadOk && in_array($fileType, ["jpg", "jpeg", "png", "gif"])): ?>
        <div style="margin-top: 20px;">
            <img src="<?= htmlspecialchars($targetFile) ?>" alt="Uploaded Image" style="max-width: 100%; border-radius: 10px;" />
        </div>
    <?php endif; ?>

    <a href="registrationAdmin.html" class="back-btn">Go Back</a>
  </div>
</body>
</html>
