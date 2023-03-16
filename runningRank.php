<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    // $start = isset($_POST["start"]) ? $_POST["start"] : 0;
    // $end = isset($_POST["end"]) ? $_POST["end"] : 0;

    $sql = "SELECT userID, SUM(Score) tmp FROM running GROUP BY userID ORDER BY tmp DESC LIMIT 100";
    $result = mysqli_query($con,$sql);
    
    $test10 = array();
    $test11 = [];
    $response3 = array();
    $response2 = array();
    (int)$count = 0;

    while ($row = mysqli_fetch_array($result)) {
        $count += 1;

        $ID = $row['userID'];
        if ($userID == $ID) {
            $response2["My_Ranking"] = (string)$count;
            $response2["My_Score"] = $row['tmp'];
        }
        $sql2 = "SELECT userNickname FROM userTBL WHERE userID = '$ID'";
        $result2 = mysqli_query($con,$sql2);
        $row2 = mysqli_fetch_array($result2);

        $response3["Nickname"] = $row2['userNickname'];
        $response3["Score"] = $row['tmp'];
        $response3["Ranking"] = (string)$count;

        $test11[] = $response3;
    }

    $test10["All"] = $test11;
    $test10["My"] = $response2;

    if ($test10["All"] == []) {
        $sql3 = "SELECT userNickname FROM userTBL WHERE userID = '$userID'";
        $result3 = mysqli_query($con,$sql3);
        $row3 = mysqli_fetch_array($result3);
        $response2["My_Ranking"] = "0";
        $response2["My_Score"] = "0";

        $response3["Nickname"] = $row3['userNickname'];
        $response3["Score"] = "0";
        $response3["Ranking"] = "0";

        $test11[] = $response3;

        $test10["All"] = $test11;
        $test10["My"] = $response2;
    }

    $json = json_encode($test10, JSON_UNESCAPED_UNICODE);
    echo $json;
    mysqli_close($con);
?>