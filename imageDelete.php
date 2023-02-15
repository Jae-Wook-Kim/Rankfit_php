<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $image_name = isset($_POST["image_name"]) ? $_POST["image_name"] : "";

    if($image_name != "") {

        $sql = "DELETE FROM images WHERE image_name = '$image_name'";
        $result = mysqli_query($con,$sql);

        echo "Success";
        // echo(json_encode($_FILES['image']));
    }
    else {
        echo "Fail";
    }
?>