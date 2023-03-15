<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    $userNickname = isset($_POST["userNickname"]) ? $_POST["userNickname"] : "";

    $response = false;

    $sql = "UPDATE userTBL SET userNickname = '$userNickname' WHERE userID = '$userID'";
    mysqli_query($con,$sql);
    $response = true;
    
    echo json_encode($response);
    mysqli_close($con)
?>