<?php
    function sanitizeInput($input)
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    require_once("dbset.php");

    $con = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($con->connect_error) {
        die("MySQL Connection Failed: " . $con->connect_error);
    }

    $con->set_charset("utf8");

    if (isset($_FILES['image'])) {
        $image_data = file_get_contents($_FILES['image']['tmp_name']);
        $image_name = sanitizeInput($_FILES['image']['name']);

        $delete_statement = $con->prepare("DELETE FROM images WHERE image_name = ?");
        $delete_statement->bind_param("s", $image_name);
        $delete_statement->execute();
        $delete_statement->close();

        $insert_statement = $con->prepare("INSERT INTO images (image_name, image_data) VALUES (?, ?)");
        $insert_statement->bind_param("ss", $image_name, $image_data);
        $insert_statement->execute();
        $insert_statement->close();

        echo "Image has been uploaded";
    } else {
        echo "Image has not been uploaded to the server!";
    }

    $con->close();
?>