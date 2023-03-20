<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $userNickname = isset($_POST["userNickname"]) ? $_POST["userNickname"] : "";

    $start = isset($_POST["start"]) ? $_POST["start"] : 0;
    $end = isset($_POST["end"]) ? $_POST["end"] : 0;

    $sql2 = "SELECT userID FROM userTBL WHERE userNickname = '$userNickname'";
    $result2 = mysqli_query($con,$sql2);
    $row2 = mysqli_fetch_array($result2);
    $ID = $row2['userID'];

    $test10 = [];
    $test = [];
    $test11 = array();
    $response3 = array();
    $response2 = array();

    if($userNickname != "") {

        $sql = "SELECT * FROM $ID WHERE userDate > '$start' AND userDate < '$end'";
        $result = mysqli_query($con,$sql);

        while ($row = mysqli_fetch_array($result)) {
            if ($row['userState'] == "1") {
                if($row['userSet'] != "-1") {
                    $response3["Exercise"] = $row['userExercise'];
                    $response3["category"] = $row['category'];
                    $response3["Date"] = $row['userDate'];
                    $response3["Set"] = $row['userSet'];
                    $response3["Weight"] = $row['userWeight'];
                    $response3["Count"] = $row['userCount'];
                    $response3["Time"] = $row['userTime'];

                    $test10[] = $response3;

                    //$test11["Anaerobic"] = $test10;
                }
                else {
                    $response2["Exercise"] = $row['userExercise'];
                    $response2["Date"] = $row['userDate'];
                    $response2["Distance"] = $row['userDistance'];
                    $response2["Time"] = $row['userTime'];

                    $test[] = $response2;

                    //$test11["Aerobic"] = $test;
                }
            }
        }
        $test11["Anaerobics"] = $test10;
        $test11["Aerobics"] = $test;
        //$test11[] = $test10;

        $json = json_encode($test11, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        echo $json;
    }
?>