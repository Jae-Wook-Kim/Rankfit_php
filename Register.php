<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    $userEmail = isset($_POST["userEmail"]) ? $_POST["userEmail"] : "";
    $userNickname = isset($_POST["userNickname"]) ? $_POST["userNickname"] : "";
    $userAge = isset($_POST["userAge"]) ? $_POST["userAge"] : "";
    $userSex = isset($_POST["userSex"]) ? $_POST["userSex"] : "";
    $userWeight = isset($_POST["userWeight"]) ? $_POST["userWeight"] : "";

    $anaerobicScore = 0;
    $aerobicScore = 0;
    $Score = 0;
    $AgeRank = 0;
    $CustomRank = 0;
    $Ranking = 0;

    $response = false;

    $result3 = mysqli_query($con,"SHOW TABLES LIKE '$userID'");
    if (!mysqli_num_rows($result3)>0) {
        $sql6 = "CREATE TABLE $userID (
            num int NOT NULL AUTO_INCREMENT,
            userExercise varchar(20) NOT NULL,
            category varchar(5) NULL,
            userDate int NOT NULL,
            userSet int NULL,
            userWeight float NULL,
            userCount int NULL,
            userDistance double NULL,
            -- exTime double NULL,
            userTime int NULL,
            userState tinyint(1) NULL,
            uuid varchar(40) NULL,
            PRIMARY KEY (num)
            )";
        mysqli_query($con,$sql6);
    }

    if($userID != "") {
        $statement2 = mysqli_prepare($con, "DELETE FROM `userTBL` WHERE `userTBL`.`userID` = ?");
        mysqli_stmt_bind_param($statement2, "s", $userID);
        mysqli_stmt_execute($statement2);

        if ($userWeight < 52) {
            $userWD = "Straw";
        }
        elseif ($userWeight < 57) {
            $userWD = "Fly";
        }
        elseif ($userWeight < 66) {
            $userWD = "Light";
        }
        elseif ($userWeight < 77) {
            $userWD = "Middle";
        }
        elseif ($userWeight < 93) {
            $userWD = "LightHeavy";
        }
        else {
            $userWD = "Heavy";
        }

        $statement = mysqli_prepare($con, "INSERT INTO userTBL VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($statement, "ssssssssssss", $userID, $userEmail, $userNickname, $userAge, $userSex, $userWeight, $userWD, $anaerobicScore, $aerobicScore, $Score, $AgeRank, $CustomRank);
        mysqli_stmt_execute($statement);

        if($userSex == 0) {
            $statement2 = mysqli_prepare($con, "INSERT INTO MaleTBL VALUES (?,?,?)");
            mysqli_stmt_bind_param($statement2, "sss", $userID, $Score, $Ranking);
            mysqli_stmt_execute($statement2);
        }
        else {
            $statement3 = mysqli_prepare($con, "INSERT INTO FemaleTBL VALUES (?,?,?)");
            mysqli_stmt_bind_param($statement3, "sss", $userID, $Score, $Ranking);
            mysqli_stmt_execute($statement3);
        }

        $response = true;
    }   
    echo json_encode($response);
?>