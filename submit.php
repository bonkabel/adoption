<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>adoption</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="menu">
            <ul>
                <li><a href="\index.html">Home</a></li>
                <li><a href="\submit.php"></a></li>
                <li><a href="\pets"></a></li>
            </ul>
        </div>
        <div class="content">
            <form action="submit.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="image" id="image" accept="image/*" required>
                <input type="submit" value ="Upload">
            </form>
            
        </div>
    </body>
</html>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image'])) {
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $fileName = basename($_FILES['image']['name']);
            $uploadPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                echo "File uploaded successfully.";
            } else {
                echo "Error: Failed to move uploaded file.";
            }
        } else {
            echo "Error: " . $_FILES['image']['error'];
        }
    } else {
        echo "No file uploaded.";
    }
}
?>