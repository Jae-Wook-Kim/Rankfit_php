<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    if(isset($_FILES['image'])) {
        $image_data = file_get_contents($_FILES['image']['tmp_name']);
        $image_name = $_FILES['image']['name'];

        $sql = "DELETE FROM images WHERE image_name = '$image_name'";
        $result = mysqli_query($con,$sql);

        $statement = mysqli_prepare($con, "INSERT INTO images (image_name, image_data) VALUES (?, ?)");
        mysqli_stmt_bind_param($statement, "ss", $image_name, $image_data);
        mysqli_stmt_execute($statement);

        echo "Image has been uploaded";
        // echo(json_encode($_FILES['image']));
    }
    else {
        echo "It has not been uploaded to the server!!";
    }
?>