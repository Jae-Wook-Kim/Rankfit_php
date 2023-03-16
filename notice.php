<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $test10 = array();
    $test11 = [];
    $response3 = array();

    $sql = "SELECT * FROM noticeboard";
    $result = mysqli_query($con,$sql);

    while ($row = mysqli_fetch_array($result)) {
        $response3["title"] = $row['title'];
        $response3["content"] = $row['content'];
        $response3["register_day"] = $row['register_day'];

        $test11[] = $response3;
    }
    $test10["Notice"] = $test11;

    if ($test10["Notice"] == []) {
        $response3["title"] = "랭크핏에 오신 것을 환영합니다.";
        $response3["content"] = "랭크핏에 오신 것을 환영합니다.";
        $response3["register_day"] = "23/03/16";

        $test11[] = $response3;

        $test10["Notice"] = $test11;
    }

    header('Content-Type: application/json; charset=utf-8');
    $json = json_encode($test10, JSON_UNESCAPED_UNICODE);
    echo $json;
    mysqli_close($con);
?>