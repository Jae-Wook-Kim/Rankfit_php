<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con, 'SET NAMES utf8');

    $nickname = isset($_POST["nickname"]) ? $_POST["nickname"] : "";

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT userID FROM userTBL WHERE userNickname = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $nickname);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    mysqli_stmt_close($stmt);

    if ($row) {
        $image_name = $row['userID'];

        // Use prepared statement to prevent SQL injection
        $sql = "SELECT image_data FROM images WHERE image_name LIKE ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", "$image_name%");
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $images = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);

        if (!empty($images)) {
            // Return the first image as JPEG
            header("Content-Type: image/jpeg");
            echo $images[0]['image_data'];
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "There is no saved image!!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Fail"]);
    }

    // Close the database connection
    mysqli_close($con);
?>