<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $Index = NULL;
    $nickname = isset($_POST["nickname"]) ? $_POST["nickname"] : "";
    $reason = isset($_POST["reason"]) ? $_POST["reason"] : "";
    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    $date = isset($_POST["date"]) ? $_POST["date"] : "";

    $response = false;

    $sql5 = "SELECT userID FROM userTBL WHERE userNickname = '$nickname'";
    $result5 = mysqli_query($con,$sql5);
    $row5 = mysqli_fetch_array($result5);
    if ($row5) {
        $ID = $row5['userID'];
        $sql6 = "SELECT * FROM notify WHERE ID = '$ID' AND reason = '$reason' AND userID = '$userID'";
        $result6 = mysqli_query($con,$sql6);
        $row6 = mysqli_fetch_array($result6);
        if ($row6) {
            echo "Already reported it!!";
            return;
        }
        else {
            $statement = mysqli_prepare($con, "INSERT INTO notify VALUES (?,?,?,?,?)");
            mysqli_stmt_bind_param($statement, "sssss", $Index, $ID, $reason, $userID, $date);
            mysqli_stmt_execute($statement);
        }

        $response = true;
    }

    echo json_encode($response);
?>