<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    $userExercise = isset($_POST["userExercise"]) ? $_POST["userExercise"] : "";
    $userDate = isset($_POST["userDate"]) ? $_POST["userDate"] : "";
    $eng = isset($_POST["eng"]) ? $_POST["eng"] : "";

    $response = false;

    if($userID != "") {
        $statement = mysqli_prepare($con, "DELETE FROM anaerobicTBL WHERE userID = ? AND userExercise = ? AND userDate = ?");
        mysqli_stmt_bind_param($statement, "sss", $userID, $userExercise, $userDate);
        mysqli_stmt_execute($statement);

        mysqli_stmt_store_result($statement);

        $sql = "DELETE FROM $userID WHERE userExercise = '$userExercise' AND userDate = '$userDate'";
        $result = mysqli_query($con,$sql);

        $sql2 = "DELETE FROM $eng WHERE userID = '$userID' AND userDate = '$userDate'";
        $result2 = mysqli_query($con,$sql2);

        $response = true;
    }
    
    echo json_encode($response);
?>