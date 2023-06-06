<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    (int)$userAge = isset($_POST["userAge"]) ? $_POST["userAge"] : 0;
    (int)$age = (int)($userAge / 10) * 10;
    (int)$age2 = $age + 10;

    $test10 = array();
    $test11 = [];
    $response3 = array();
    $response2 = array();
    (int)$count = -1;

    $sql5 = "UPDATE userTBL SET userAge = '$userAge' WHERE userID = '$userID'";
    $result5 = mysqli_query($con,$sql5);

    $sql3 = "UPDATE userTBL a inner join (SELECT userID, Score, dense_rank() over (order by Score desc) as Ranking FROM userTBL WHERE userAge >= '$age' AND userAge < '$age2') b on b.userID = a.userID SET a.AgeRank = b.Ranking";
    $result3 = mysqli_query($con,$sql3);

    $sql = "SELECT userID, Score, dense_rank() over (order by Score desc) AgeRank FROM userTBL WHERE userAge >= '$age' AND userAge < '$age2' AND Score != 0 LIMIT 100";
    $result = mysqli_query($con,$sql);

    $sql4 = "SELECT Score, AgeRank FROM userTBL WHERE userID = '$userID'";
    $result4 = mysqli_query($con,$sql4);
    $row3 = mysqli_fetch_array($result4);

    while ($row = mysqli_fetch_array($result)) {
        $count += 1;
        $ID = $row['userID'];
        $sql2 = "SELECT userNickname FROM userTBL WHERE userID = '$ID'";
        $result2 = mysqli_query($con,$sql2);
            
        $row2 = mysqli_fetch_array($result2);

        $response3["Ranking"] = $row['AgeRank'];
        $response3["Nickname"] = $row2['userNickname'];
        $response3["Score"] = $row['Score'];

        $test11[] = $response3;
    }
    $test10["All"] = $test11;

    $response2["My_Ranking"] = (string)$row3['AgeRank'];
    $response2["My_Score"] = (string)$row3['Score'];

    $test10["My"] = $response2;

    $json = json_encode($test10, JSON_UNESCAPED_UNICODE);
    echo $json;

    // require("dbset.php");
    // $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    // mysqli_query($con, 'SET NAMES utf8');

    // $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    // $userAge = isset($_POST["userAge"]) ? intval($_POST["userAge"]) : 0;
    // $age = intval($userAge / 10) * 10;
    // $age2 = $age + 10;

    // $test10 = [];
    // $response2 = [];
    // $response3 = [];
    // $count = -1;

    // $sql5 = "UPDATE userTBL SET userAge = '$userAge' WHERE userID = '$userID'";
    // $result5 = mysqli_query($con, $sql5);

    // $sql3 = "UPDATE userTBL a INNER JOIN (SELECT userID, Score, DENSE_RANK() OVER (ORDER BY Score DESC) AS Ranking FROM userTBL WHERE userAge >= '$age' AND userAge < '$age2') b ON b.userID = a.userID SET a.AgeRank = b.Ranking";
    // $result3 = mysqli_query($con, $sql3);

    // $sql = "SELECT userID, Score, DENSE_RANK() OVER (ORDER BY Score DESC) AS AgeRank FROM userTBL WHERE userAge >= '$age' AND userAge < '$age2' AND Score != 0 LIMIT 100";
    // $result = mysqli_query($con, $sql);

    // $sql4 = "SELECT Score, AgeRank FROM userTBL WHERE userID = '$userID'";
    // $result4 = mysqli_query($con, $sql4);
    // $row3 = mysqli_fetch_array($result4);

    // while ($row = mysqli_fetch_array($result)) {
    //     $count++;
    //     $ID = $row['userID'];
    //     $sql2 = "SELECT userNickname FROM userTBL WHERE userID = '$ID'";
    //     $result2 = mysqli_query($con, $sql2);

    //     $row2 = mysqli_fetch_array($result2);

    //     $response3["Ranking"] = $row['AgeRank'];
    //     $response3["Nickname"] = $row2['userNickname'];
    //     $response3["Score"] = $row['Score'];

    //     $test10[] = $response3;
    // }

    // $test10["All"] = $test11;

    // $response2["My_Ranking"] = (string)$row3['AgeRank'];
    // $response2["My_Score"] = (string)$row3['Score'];

    // $test10["My"] = $response2;

    // $json = json_encode($test10, JSON_UNESCAPED_UNICODE);
    // echo $json;
?>