<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $sql = "SELECT userID, SUM(Score) tmp FROM running WHERE userDate > '$start' AND userDate < '$end' GROUP BY userID ORDER BY tmp DESC LIMIT 100";
    $result = mysqli_query($con,$sql);
    
    $test10 = array();
    $test11 = [];
    $response3 = array();
    $response2 = array();
    (int)$count = 0;

    while ($row = mysqli_fetch_array($result)) {
        $count += 1;

        $ID = $row['userID'];
        $sql2 = "SELECT userNickname FROM userTBL WHERE userID = '$ID'";
        $result2 = mysqli_query($con,$sql2);
        $row2 = mysqli_fetch_array($result2);

        $response3["Nickname"] = $row2['userNickname'];
        $response3["Score"] = $row['tmp'];
        $response3["Rank"] = (string)$count;

        $test11[] = $response3;
    }
    $test10["All"] = $test11;

    $json = json_encode($test10, JSON_UNESCAPED_UNICODE);
    echo $json;
    mysqli_close($con);
?>