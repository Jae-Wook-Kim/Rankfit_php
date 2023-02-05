<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $userNickname = isset($_POST["userNickname"]) ? $_POST["userNickname"] : "";

    $response = true;

    if($userNickname != "") {
        $statement = mysqli_prepare($con, "SELECT * FROM userTBL WHERE userNickname = ?");
        mysqli_stmt_bind_param($statement, "s", $userNickname);
        mysqli_stmt_execute($statement);
 
        while(mysqli_stmt_fetch($statement)) {
            $response = false;       
        }
    }

    echo json_encode($response);
?>