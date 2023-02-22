<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $sql = "SELECT userExercise, COUNT(*) cnt FROM anaerobicTBL WHERE userState = '1' GROUP BY userEXercise ORDER BY cnt DESC";
    $result = mysqli_query($con,$sql);
    // $tmp = mysqli_num_rows($result);
    
    $test10 = array();
    $test11 = [];
    $response3 = array();
    $response2 = array();
    (int)$count = 0;

    while ($row = mysqli_fetch_array($result)) {
        $count += 1;
        $response3["Exercise"] = $row['userExercise'];
        $response3["Rank"] = (string)$count;

        $test11[] = $response3;
    }
    $test10["All"] = $test11;

    $json = json_encode($test10, JSON_UNESCAPED_UNICODE);
    echo $json;
    mysqli_close($con);
?>