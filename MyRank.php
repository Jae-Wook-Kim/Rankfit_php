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

    //(int)$start = (int)isset($_POST["start"]);
    //(int)$end = (int)isset($_POST["end"]);

    $start = isset($_POST["start"]) ? $_POST["start"] : 0;
    $end = isset($_POST["end"]) ? $_POST["end"] : 0;

    //var_dump($start);
    //echo($start);
    //echo($end);

    $sql5 = "SELECT userWD FROM $eng WHERE userID = '$userID'";
    $result5 = mysqli_query($con,$sql5);
    $row5 = mysqli_fetch_array($result5);
    $WD = $row5['userWD'];

    $sql3 = "UPDATE $eng a inner join (SELECT userID, Score, dense_rank() over (order by Score desc) as Ranking FROM $eng WHERE userAge >= '$age' AND userAge < '$age2' AND userWD = '$WD' AND userSex = '$userSex' AND userDate > '$start' AND userDate < '$end') b on b.userID = a.userID SET a.CustomRank = b.Ranking";
    $result3 = mysqli_query($con,$sql3);

    //$sql = "SELECT userID, Score, CustomRank FROM $eng WHERE userAge >= '$age' AND userAge < '$age2' AND userWD = '$WD' AND userSex = '$userSex' LIMIT 100";
    
    //$sql = "SELECT userID, Score, dense_rank() over (order by Score desc) CustomRank FROM $eng WHERE userAge >= '$age' AND userAge < '$age2' AND userWD = '$WD' AND userSex = '$userSex' AND userDate > '$start' AND userDate < '$end' LIMIT 100";
    //$result = mysqli_query($con,$sql);

    $sql4 = "SELECT CustomRank FROM $eng WHERE userID = '$userID'";
    $result4 = mysqli_query($con,$sql4);
    $row3 = mysqli_fetch_array($result4);

    $response2["My_Ranking"] = $row3['CustomRank'];
    $response2["Exercise"] = $kor;
    //$response2["My_Score"] = $row3['Score'];

    $test10 = $response2;

    /*while ($row = mysqli_fetch_array($result)) {
        $count += 1;
        $ID = $row['userID'];
        $sql2 = "SELECT userNickname FROM userTBL WHERE userID = '$ID'";
        $result2 = mysqli_query($con,$sql2);
            
        $row2 = mysqli_fetch_array($result2);

        $response3["Ranking"] = $row['CustomRank'];
        $response3["Nickname"] = $row2['userNickname'];
        $response3["Score"] = $row['Score'];

        $test10[] = $response3;
    }
    $response4 = array();
    $response4["count"] = $count;
    $test10 += $response4;*/

    $json = json_encode($test10, JSON_UNESCAPED_UNICODE);
    echo $json;
?>