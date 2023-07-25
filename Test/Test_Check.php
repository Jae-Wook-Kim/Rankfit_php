<?php
    require("dbset.php");
    $response = true;

    if (isset($_POST["userNickname"])) {
        $userNickname = $_POST["userNickname"];
        
        $con = mysqli_connect($db_host, $db_user, $db_password, $db_name);
        if (!$con) {
            die("MySQL Connection Failed !!");
        }
        mysqli_query($con, 'SET NAMES utf8');

        $statement = mysqli_prepare($con, "SELECT COUNT(*) FROM userTBL WHERE userNickname = ?");
        mysqli_stmt_bind_param($statement, "s", $userNickname);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $count);
        mysqli_stmt_fetch($statement);
        mysqli_stmt_close($statement);
        
        if ($count > 0) {
            $response = false;
        }

        mysqli_close($con);
    }

    echo json_encode($response);
?>