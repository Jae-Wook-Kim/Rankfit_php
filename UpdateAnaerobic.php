<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $num = NULL;
    $Score = isset($_POST["Score"])? $_POST["Score"] : "";
    $userTime = isset($_POST["userTime"])? $_POST["userTime"] : "";
    $userState = isset($_POST["userState"]) ? $_POST["userState"] : "";
    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
   // $userExercise = isset($_POST["userExercise"]) ? $_POST["userExercise"] : "";
    $userDate = isset($_POST["userDate"]) ? $_POST["userDate"] : "";
    $userSex = isset($_POST["userSex"]) ? $_POST["userSex"] : 0;
    $eng = isset($_POST["eng"]) ? $_POST["eng"] : "";
    $uuid = isset($_POST["uuid"]) ? $_POST["uuid"] : "";

    //$userSet = isset($_POST["userSet"]) ? $_POST["userSet"] : -1;
    //$userWeight = isset($_POST["userWeight"]) ? $_POST["userWeight"] : -1;
    //$userCount = isset($_POST["userCount"]) ? $_POST["userCount"] : -1;
    //$userDistance = isset($_POST["userDistance"])? $_POST["userDistance"] : -1;
    //$userTime = isset($_POST["userTime"])? $_POST["userTime"] : -1;

    $start = isset($_POST["start"]) ? $_POST["start"] : 0;
    $end = isset($_POST["end"]) ? $_POST["end"] : 0;

    $CustomRank = 0;

    $response = false;

    $result = mysqli_query($con,"SHOW TABLES LIKE '$eng'");
    if (!mysqli_num_rows($result)>0) {
        $sql6 = "CREATE TABLE $eng (
            num int NOT NULL AUTO_INCREMENT,
            userID varchar(30) NOT NULL,
            userSex int NOT NULL,
            userAge int NOT NULL,
            userWD varchar(10) NOT NULL,
            userDate int NOT NULL,
            Score float NOT NULL,
            CustomRank int NULL,
            PRIMARY KEY (num)
            )";
        mysqli_query($con,$sql6);
    }
    //사용자 테이블
    /*$result3 = mysqli_query($con,"SHOW TABLES LIKE '$userID'");
    if (!mysqli_num_rows($result)>0) {
        $sql6 = "CREATE TABLE $userID (
            num int NOT NULL AUTO_INCREMENT,
            userExercise varchar(20) NOT NULL,
            userDate int NOT NULL,
            userSet int NULL,
            userWeight float NULL,
            userCount int NULL,
            userDistance double NULL,
            userTime int NULL,
            PRIMARY KEY (num)
            )";
        mysqli_query($con,$sql6);
    } else {
        $sql10 = "INSERT INTO $userID VALUES('".$num."','".$userExercise."','".$userDate."','".$userSet."','".$userWeight."','".$userCount."','".$userDistance."','".$userTime."')";
        mysqli_query($con,$sql10);
    }*/
//운동별 테이블
    $sql9 = "SELECT userAge, userWD FROM userTBL WHERE userID = '$userID'";
    $result9 = mysqli_query($con,$sql9);
    $row9 = mysqli_fetch_array($result9);
    $age = $row9['userAge'];
    $WD = $row9['userWD'];

    $sql7 = "INSERT INTO $eng VALUES('".$num."','".$userID."','".$userSex."','".$age."','".$WD."','".$userDate."','".$Score."','".$CustomRank."')";
    // $sql7 = "INSERT INTO $eng VALUES('".$userID."','".$userSex."','".$age."','".$WD."','".$userDate."','".$Score."','".$CustomRank."')";
    mysqli_query($con,$sql7);
//무산소 테이블
    if($userSex != "") {
        $sql10 = "UPDATE $userID SET userDate = '$userDate', userTime = '$userTime', userState = '$userState' WHERE uuid = '$uuid'";
        mysqli_query($con,$sql10);
        
        $sql = "UPDATE anaerobicTBL SET userDate = '$userDate', Score = '$Score', userTime = '$userTime', userState = '$userState' WHERE uuid = '$uuid'";
        mysqli_query($con,$sql);
        $response = true;

        $sql2 = "UPDATE userTBL SET userTBL.anaerobicScore = (SELECT SUM(anaerobicTBL.Score) FROM anaerobicTBL WHERE anaerobicTBL.userID = userTBL.userID AND userDate > '$start' AND userDate < '$end')";
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
    
    echo json_encode($response);
    mysqli_close($con)
?>