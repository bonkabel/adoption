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
        <li><a href="\submit.php">Submit</a></li>
        <li><a href="\pets.php">Pets</a></li>
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

<?php
require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

// Show PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$bucket = 'mypetimages';
$region = 'us-east-2';

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
                'ContentType' => $type
            ]);

            // Save pet metadata
            $meta = [
                'name'     => $name,
                'age'      => $age,
                'breed'    => $breed,
                'url'      => "https://{$bucket}.s3.{$region}.amazonaws.com{$filename}"
            ];

            $jsonFile = __DIR__ . '/petdata.json';

            // Load and append
            if (file_exists($jsonFile)) {
                $existing = json_decode(file_get_contents($jsonFile), true);
                if (!is_array($existing)) $existing = [];
            } else {
                $existing = [];
            }

            $existing[] = $meta;
            $saved = file_put_contents($jsonFile, json_encode($existing, JSON_PRETTY_PRINT));

            if ($saved !== false) {
                echo "<p>Upload successful.</p><a href='pets.php'>View images</a>";
            } else {
                echo "<p>Upload succeeded, but failed to save metadata.</p>";
            }

        } catch (S3Exception $e) {
            echo "<p>Upload failed: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Error uploading file.</p>";
    }
}
?>

</div>
</body>
</html>