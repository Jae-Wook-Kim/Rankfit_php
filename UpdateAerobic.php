<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');
    
    $num = NULL;
    $userDistance = isset($_POST["userDistance"])? $_POST["userDistance"] : -1;
    $Score = isset($_POST["Score"])? $_POST["Score"] : "";
    $userTime = isset($_POST["userTime"])? $_POST["userTime"] : -1;
    $userState = isset($_POST["userState"]) ? $_POST["userState"] : "";
    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    //$userExercise = isset($_POST["userExercise"]) ? $_POST["userExercise"] : "";
    $userDate = isset($_POST["userDate"]) ? $_POST["userDate"] : "";
    $userSex = isset($_POST["userSex"]) ? $_POST["userSex"] : 0;
    $eng = isset($_POST["eng"]) ? $_POST["eng"] : "";
    $ko = isset($_POST["ko"]) ? $_POST["ko"] : "";
    $uuid = isset($_POST["uuid"]) ? $_POST["uuid"] : "";

    //$userSet = isset($_POST["userSet"]) ? $_POST["userSet"] : -1;
    //$userWeight = isset($_POST["userWeight"]) ? $_POST["userWeight"] : -1;
    //$userCount = isset($_POST["userCount"]) ? $_POST["userCount"] : -1;

    // $start = isset($_POST["start"]) ? $_POST["start"] : 0;
    // $end = isset($_POST["end"]) ? $_POST["end"] : 0;

    $CustomRank = 0;

    $response = false;

    $result = mysqli_query($con,"SHOW TABLES LIKE '$eng'");
    if (!mysqli_num_rows($result)>0) {
        $sql6 = "CREATE TABLE $eng (
            -- num int NOT NULL AUTO_INCREMENT,
            userID varchar(30) NOT NULL,
            userSex int NOT NULL,
            userAge int NOT NULL,
            userWD varchar(10) NOT NULL,
            -- userDate int NOT NULL,
            Score float NOT NULL,
            CustomRank int NULL,
            PRIMARY KEY (userID)
            )";
        mysqli_query($con,$sql6);
    }

    $sql9 = "SELECT userAge, userWD FROM userTBL WHERE userID = '$userID'";
    $result9 = mysqli_query($con,$sql9);
    $row9 = mysqli_fetch_array($result9);
    $age = $row9['userAge'];
    $WD = $row9['userWD'];

    $result2 = mysqli_query($con,"SELECT userID FROM $eng WHERE userID = '$userID'");
    if (!mysqli_num_rows($result2)>0) {
        $sql7 = "INSERT INTO $eng VALUES('".$userID."','".$userSex."','".$age."','".$WD."','".$Score."','".$CustomRank."')";
        mysqli_query($con,$sql7);
    }

    if($userSex != "") {
        $sql10 = "UPDATE $userID SET userDate = '$userDate', userDistance = '$userDistance', userTime = '$userTime', userState = '$userState' WHERE uuid = '$uuid'";
        mysqli_query($con,$sql10);

        $sql = "UPDATE aerobicTBL SET userDate = '$userDate', userDistance = '$userDistance', userTime = '$userTime', Score = '$Score', userState = '$userState' WHERE uuid = '$uuid'";
        mysqli_query($con,$sql);
        $response = true;

        $sql2 = "UPDATE userTBL SET userTBL.aerobicScore = (SELECT SUM(aerobicTBL.Score) FROM aerobicTBL WHERE aerobicTBL.userID = userTBL.userID)";
        mysqli_query($con,$sql2);

        $sql3 = "UPDATE userTBL SET userTBL.Score = (SELECT SUM(userTBL.anaerobicScore + userTBL.aerobicScore))";
        mysqli_query($con,$sql3);

        if($userSex == 0) {
            $sql4 = "UPDATE MaleTBL SET MaleTBL.Score = (SELECT userTBL.Score FROM userTBL WHERE userTBL.userID = MaleTBL.userID)";
            mysqli_query($con,$sql4);
        }
        else {
            $sql5 = "UPDATE FemaleTBL SET FemaleTBL.Score = (SELECT userTBL.Score FROM userTBL WHERE userTBL.userID = FemaleTBL.userID)";
            mysqli_query($con,$sql5);
        }
    }

    $sql11 = "UPDATE $eng a LEFT OUTER JOIN (SELECT userID, SUM(Score) as tmp FROM aerobicTBL WHERE userExercise = '$ko' GROUP BY userID) b on b.userID = a.userID SET a.Score = b.tmp";
    mysqli_query($con,$sql11);
    
    echo json_encode($response);
    mysqli_close($con)
?>