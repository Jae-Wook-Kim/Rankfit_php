<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $image_name = isset($_POST["image_name"]) ? $_POST["image_name"] : "";

    if($image_name != "") {

        $sql = "SELECT image_name FROM images WHERE image_name = '$image_name'";
        $result = mysqli_query($con,$sql);

        $row = mysqli_fetch_array($result);
        $image_data = $row['image_name'];

        header("Content-Type: image/jpeg");
        // echo "Success";
        echo $image_data;
    }
    else {
        echo "Fail";
    }
?>