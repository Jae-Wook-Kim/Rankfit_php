<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con, 'SET NAMES utf8');

    $test10 = array();

    $sql = "SELECT * FROM noticeboard";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $test11[] = array(
                "title" => $row['title'],
                "content" => $row['content'],
                "register_day" => $row['register_day']
            );
        }
        $test10["Notice"] = $test11;
    } else {
        $test10["Notice"] = array(
            array(
                "title" => "랭크핏에 오신 것을 환영합니다.",
                "content" => "랭크핏에 오신 것을 환영합니다.",
                "register_day" => "23/03/16"
            )
        );
    }

    mysqli_free_result($result);
    mysqli_close($con);

    header('Content-Type: application/json; charset=utf-8');
    $json = json_encode($test10, JSON_UNESCAPED_UNICODE);
    echo $json;
?>