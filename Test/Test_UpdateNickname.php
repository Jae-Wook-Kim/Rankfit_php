<?php
    require("dbset.php");

    $response = false;

    try {
        $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
        $userNickname = isset($_POST["userNickname"]) ? $_POST["userNickname"] : "";

        if (empty($userID) || empty($userNickname)) {
            throw new Exception("User ID and User Nickname are required.");
        }

        $con = mysqli_connect($db_host, $db_user, $db_password, $db_name);
        if (!$con) {
            throw new Exception("MySQL Connection Failed: " . mysqli_connect_error());
        }

        mysqli_query($con, 'SET NAMES utf8');

        $sql = "UPDATE userTBL SET userNickname = ? WHERE userID = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $userNickname, $userID);
        mysqli_stmt_execute($stmt);

        $response = true;

        mysqli_stmt_close($stmt);
        mysqli_close($con);
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        $response = false;
    }

    echo json_encode($response);
?>