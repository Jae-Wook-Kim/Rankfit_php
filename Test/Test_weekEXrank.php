<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con, 'SET NAMES utf8');

    $sql = "SELECT userExercise, COUNT(*) as cnt FROM (
        SELECT userExercise FROM anaerobicTBL WHERE userState = '1'
        UNION ALL
        SELECT userExercise FROM aerobicTBL WHERE userState = '1'
    ) subquery GROUP BY userExercise ORDER BY cnt DESC LIMIT 10";

    $result = mysqli_query($con, $sql);

    $response = array();
    $count = 0;

    while ($row = mysqli_fetch_array($result)) {
        $count++;
        $response[] = array(
            "Exercise" => $row['userExercise'],
            "Rank" => (string)$count
        );
    }

    $json = json_encode(array("All" => $response), JSON_UNESCAPED_UNICODE);
    echo $json;

    mysqli_close($con);
?>