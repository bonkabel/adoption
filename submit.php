<!DOCTYPE html>
<?php
require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

// AWS settings
$bucket = 'your-bucket-name';
$region = 'your-region'; // e.g., us-east-1

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $name  = $_POST['name'];
    $age   = $_POST['age'];
    $breed = $_POST['breed'];
    $file  = $_FILES['image'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $filename = uniqid() . "_" . basename($file['name']);
        $filePath = $file['tmp_name'];
        $type     = mime_content_type($filePath);

        try {
            $s3 = new S3Client([
                'region'  => $region,
                'version' => 'latest',
            ]);

            $s3->putObject([
                'Bucket'      => $bucket,
                'Key'         => $filename,
                'SourceFile'  => $filePath,
                'ACL'         => 'public-read',
                'ContentType' => $type
            ]);

            // Save pet data
            $meta = [
                'name'     => $name,
                'age'      => $age,
                'breed'    => $breed,
                'filename' => $filename
            ];

            $jsonFile = 'petdata.json';
            $allMeta = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];
            $allMeta[] = $meta;
            file_put_contents($jsonFile, json_encode($allMeta, JSON_PRETTY_PRINT));

            echo "<p>Upload successful.</p><a href='view.php'>View images</a>";

        } catch (S3Exception $e) {
            echo "<p>Upload failed: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Error uploading file.</p>";
    }
}
?>
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
                <li><a href="\submit.php">Submit</a></li>
                <li><a href="\pets">Pets</a></li>
            </ul>
        </div>
        <div class="content">
        <form method="POST" enctype="multipart/form-data" action="">
            <input type="file" name="image" accept="image/*" required><br>
            <input type="text" name="name" placeholder="Name" required><br>
            <input type="text" name="age" placeholder="Age" required><br>
            <input type="text" name="breed" placeholder="Breed" required><br>
            <input type="submit" value="Upload">
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