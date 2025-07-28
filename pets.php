<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>adoption</title>
        <link rel="stylesheet" href="styles.css">
        <style>
            img {
                width : 200px;
                height: auto;
                margin: 10px;
                border: 1px solid;

            }
            .images {
                display: flex;
                flex-wrap: wrap;
            }
        </style>
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
            <h1>Pets</h1>
            <?php
                $bucket = 'mypetimages';
                $region = 'us-east-2';
                $jsonFile = 'petdata.json';

                if (!file_exists($jsonFile)) {
                    echo "uploads not found.";
                    exit;
                }

                $entries = json_decode(file_get_contents($jsonFile), true);
                echo "<div style='display:flex; flex-wrap:wrap;'>";

                foreach ($entries as $entry) {
                    echo "<p>URL: $url</p>";
                    echo "<div style='margin:15px; text-align:center;'>";
                    echo "<img src='$url' style='width:200px; height:auto;'><br>";
                    echo "<strong>Name:</strong> " . htmlspecialchars($entry['name']) . "<br>";
                    echo "<strong>Age:</strong> " . htmlspecialchars($entry['age']) . "<br>";
                    echo "<strong>Breed:</strong> " . htmlspecialchars($entry['breed']);
                    echo "</div>";
                }
                echo "</div>";
            ?>
        </div>
    </body>
</html>