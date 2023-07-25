<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con, 'SET NAMES utf8');

    $Score = $_POST["Score"] ?? "";
    $userTime = $_POST["userTime"] ?? "";
    $userState = $_POST["userState"] ?? "";
    $userID = $_POST["userID"] ?? "";
    $userDate = $_POST["userDate"] ?? "";
    $userSex = isset($_POST["userSex"]) ? (int)$_POST["userSex"] : 0;
    $eng = $_POST["eng"] ?? "";
    $ko = $_POST["ko"] ?? "";
    $uuid = $_POST["uuid"] ?? "";

    $response = false;

    // Ensure input data is sanitized to prevent SQL injection

    // ... Add necessary input data sanitization if required ...

    // Escape input data before using it in SQL queries
    $Score = mysqli_real_escape_string($con, $Score);
    $userTime = mysqli_real_escape_string($con, $userTime);
    $userState = mysqli_real_escape_string($con, $userState);
    $userID = mysqli_real_escape_string($con, $userID);
    $userDate = mysqli_real_escape_string($con, $userDate);
    $eng = mysqli_real_escape_string($con, $eng);
    $ko = mysqli_real_escape_string($con, $ko);
    $uuid = mysqli_real_escape_string($con, $uuid);

    $CustomRank = 0;

    $result = mysqli_query($con, "SHOW TABLES LIKE '$eng'");
    if (!mysqli_num_rows($result) > 0) {
        $sql6 = "CREATE TABLE $eng (
            userID varchar(30) NOT NULL,
            userSex int NOT NULL,
            userAge int NOT NULL,
            userWD varchar(10) NOT NULL,
            Score float NOT NULL,
            CustomRank int NULL,
            PRIMARY KEY (userID)
        )";
        mysqli_query($con, $sql6);
    }

    $sql9 = "SELECT userAge, userWD FROM userTBL WHERE userID = '$userID'";
    $result9 = mysqli_query($con, $sql9);
    $row9 = mysqli_fetch_array($result9);
    $age = (int)$row9['userAge'];
    $WD = mysqli_real_escape_string($con, $row9['userWD']);

    $result2 = mysqli_query($con, "SELECT userID FROM $eng WHERE userID = '$userID'");
    if (!mysqli_num_rows($result2) > 0) {
        $sql7 = "INSERT INTO $eng (userID, userSex, userAge, userWD, Score, CustomRank) VALUES ('$userID', '$userSex', '$age', '$WD', '$Score', '$CustomRank')";
        mysqli_query($con, $sql7);
    }

    if ($userSex != "") {
        $sql10 = "UPDATE $eng SET userDate = '$userDate', userTime = '$userTime', userState = '$userState' WHERE uuid = '$uuid' AND userID = '$userID'";
        mysqli_query($con, $sql10);

        $sql = "UPDATE anaerobicTBL SET userDate = '$userDate', Score = '$Score', userTime = '$userTime', userState = '$userState' WHERE uuid = '$uuid'";
        mysqli_query($con, $sql);
        $response = true;

        $sql2 = "UPDATE userTBL SET anaerobicScore = (SELECT SUM(Score) FROM anaerobicTBL WHERE userID = userTBL.userID)";
        mysqli_query($con, $sql2);

        $sql3 = "UPDATE userTBL SET Score = (anaerobicScore + aerobicScore)";
        mysqli_query($con, $sql3);

        if ($userSex == 0) {
            $sql4 = "UPDATE MaleTBL SET Score = (SELECT Score FROM userTBL WHERE userID = MaleTBL.userID)";
            mysqli_query($con, $sql4);
        } else {
            $sql5 = "UPDATE FemaleTBL SET Score = (SELECT Score FROM userTBL WHERE userID = FemaleTBL.userID)";
            mysqli_query($con, $sql5);
        }
    }

    $sql11 = "UPDATE $eng a LEFT OUTER JOIN (SELECT userID, SUM(Score) as tmp FROM anaerobicTBL WHERE userExercise = '$ko' GROUP BY userID) b on b.userID = a.userID SET a.Score = b.tmp";
    mysqli_query($con, $sql11);

    echo json_encode($response);
    mysqli_close($con);
?>