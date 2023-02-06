<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    $userExercise = isset($_POST["userExercise"]) ? $_POST["userExercise"] : "";
    $userDate = isset($_POST["userDate"]) ? $_POST["userDate"] : "";
    $eng = isset($_POST["eng"]) ? $_POST["eng"] : "";
    
    $start = isset($_POST["start"]) ? $_POST["start"] : "";
    $end = isset($_POST["end"]) ? $_POST["end"] : "";

    $userSex = isset($_POST["userSex"]) ? $_POST["userSex"] : "";

    $response = false;

    if($userID != "") {
        $statement = mysqli_prepare($con, "DELETE FROM aerobicTBL WHERE userID = ? AND userExercise = ? AND userDate = ?");
        mysqli_stmt_bind_param($statement, "sss", $userID, $userExercise, $userDate);
        mysqli_stmt_execute($statement);

        mysqli_stmt_store_result($statement);

        $sql = "DELETE FROM $userID WHERE userExercise = '$userExercise' AND userDate = '$userDate'";
        $result = mysqli_query($con,$sql);

        $sql2 = "DELETE FROM $eng WHERE userID = '$userID' AND userDate = '$userDate'";
        $result2 = mysqli_query($con,$sql2);

        $response = true;
    }

    if($userID != "") {
        $sql20 = "UPDATE userTBL SET userTBL.aerobicScore = (SELECT SUM(aerobicTBL.Score) FROM aerobicTBL WHERE aerobicTBL.userID = userTBL.userID AND userDate > '$start' AND userDate < '$end')";
        mysqli_query($con,$sql20);

        $sql30 = "UPDATE userTBL SET userTBL.Score = (SELECT SUM(userTBL.anaerobicScore + userTBL.aerobicScore))";
        mysqli_query($con,$sql30);

        if($userSex == 0) {
            $sql40 = "UPDATE MaleTBL SET MaleTBL.Score = (SELECT userTBL.Score FROM userTBL WHERE userTBL.userID = MaleTBL.userID)";
            mysqli_query($con,$sql40);
        }
        else {
            $sql50 = "UPDATE FemaleTBL SET FemaleTBL.Score = (SELECT userTBL.Score FROM userTBL WHERE userTBL.userID = FemaleTBL.userID)";
            mysqli_query($con,$sql50);
        }
    }
    
    echo json_encode($response);
?>