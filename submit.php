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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        console.log("Image uploaded: " . htmlspecialchars($_FILES["image"]["name"]));
    } else {
        console.log("Upload failed.");
    }
}
?>