<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    $userSex = isset($_POST["userSex"]) ? $_POST["userSex"] : 0;
    (int)$userAge = isset($_POST["userAge"]) ? $_POST["userAge"] : 0;
    (int)$age = (int)($userAge / 10) * 10;
    (int)$age2 = $age + 10;
    $eng = isset($_POST["eng"]) ? $_POST["eng"] : "";
    $kor = isset($_POST["kor"]) ? $_POST["kor"] : "";

    $test10 = [];
    $response3 = array();
    $response2 = array();
    (int)$count = -1;

    $start = isset($_POST["start"]) ? $_POST["start"] : 0;
    $end = isset($_POST["end"]) ? $_POST["end"] : 0;

    $sql6 = "UPDATE userTBL SET userAge = '$userAge' WHERE userID = '$userID'";
    $result6 = mysqli_query($con,$sql6);

    $sql5 = "SELECT userWD FROM $eng WHERE userID = '$userID'";
    $result5 = mysqli_query($con,$sql5);
    $row5 = mysqli_fetch_array($result5);
    $WD = $row5['userWD'];

    $sql3 = "UPDATE $eng a inner join (SELECT userID, Score, dense_rank() over (order by Score desc) as Ranking FROM $eng WHERE userAge >= '$age' AND userAge < '$age2' AND userWD = '$WD' AND userSex = '$userSex' AND userDate > '$start' AND userDate < '$end') b on b.userID = a.userID SET a.CustomRank = b.Ranking";
    $result3 = mysqli_query($con,$sql3);

    $sql4 = "SELECT CustomRank FROM $eng WHERE userID = '$userID'";
    $result4 = mysqli_query($con,$sql4);
    $row3 = mysqli_fetch_array($result4);

    $response2["My_Ranking"] = $row3['CustomRank'];
    $response2["Exercise"] = $kor;

    $test10 = $response2;

    $json = json_encode($test10, JSON_UNESCAPED_UNICODE);
    echo $json;
?>