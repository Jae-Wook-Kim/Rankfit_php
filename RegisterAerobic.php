<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    
    $Index = NULL;
    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    $category = "유산소";
    $userExercise = isset($_POST["userExercise"]) ? $_POST["userExercise"] : "";
    $userDate = isset($_POST["userDate"]) ? $_POST["userDate"] : "";
    $userDistance = isset($_POST["userDistance"]) ? $_POST["userDistance"] : "";
    $userTime = isset($_POST["userTime"]) ? $_POST["userTime"] : -1;
    $Score = isset($_POST["Score"]) ? $_POST["Score"] : 0;
    $userState = isset($_POST["userState"]) ? $_POST["userState"] : 0;
    $uuid = isset($_POST["uuid"]) ? $_POST["uuid"] : "";

    $userSet = isset($_POST["userSet"]) ? $_POST["userSet"] : -1;
    $userWeight = isset($_POST["userWeight"]) ? $_POST["userWeight"] : -1;
    $userCount = isset($_POST["userCount"]) ? $_POST["userCount"] : -1;
    
    $sql = "INSERT INTO aerobicTBL VALUES('".$Index."','".$userID."','".$userExercise."','".$userDate."','".$userDistance."','".$userTime."','".$Score."','".$userState."','".$uuid."')";
    $ret = mysqli_query($con, $sql);

    if ($ret) {
        echo "Data Insert Success.";
    }
    else {
        echo "Data Insert Failed!!!";
        //echo "Cause :".mysqli_error($con);
    }

    $result3 = mysqli_query($con,"SHOW TABLES LIKE '$userID'");
    if (mysqli_num_rows($result3)>0) {
        $sql10 = "INSERT INTO $userID VALUES('".$Index."','".$userExercise."','".$userDate."','".$userSet."','".$userWeight."','".$userCount."','".$userDistance."','".$userTime."','".$userState."','".$uuid."')";
        mysqli_query($con,$sql10);
    }

    mysqli_close($con);
?>