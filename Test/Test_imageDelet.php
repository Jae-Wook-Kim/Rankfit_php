<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con, 'SET NAMES utf8');

    if (isset($_POST["image_name"]) && !empty($_POST["image_name"])) {
        $image_name = $_POST["image_name"];

        $sql = "DELETE FROM images WHERE image_name = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $image_name);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo "Success";
        } else {
            echo "Fail";
        }
    } else {
        echo "Fail";
    }
?>