<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $nickname = isset($_POST["nickname"]) ? $_POST["nickname"] : "";

    $sql2 = "SELECT userID FROM userTBL WHERE userNickname = '$nickname'";
    $result2 = mysqli_query($con,$sql2);
    $row2 = mysqli_fetch_array($result2);

    if($row2) {
        $image_name = $row2['userID'];
    // $image_name = isset($row2['userID']) ? $row2['userID'] : "";

        $sql = "SELECT image_data FROM images WHERE image_name LIKE '$image_name%'";
        $result = mysqli_query($con,$sql);
        $row = mysqli_fetch_array($result);

        if($row) {
            // $row = mysqli_fetch_array($result);
            $image_data = $row['image_data'];

            header("Content-Type: image/jpeg");
            // echo "Success";
            echo $image_data;
        }
        else {
            echo "There is no saved image!!";
        }
    }
    else {
        echo "Fail";
    }
?>