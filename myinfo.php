<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";

    $uuid = isset($_POST["uuid"]) ? $_POST["uuid"] : "";

    //$test10 = array();
    $test10 = [];
    $response3 = array();
    //$response2 = array();

    if($uuid != "") {

        $sql = "SELECT * FROM $userID WHERE uuid = '$uuid'";
        $result = mysqli_query($con,$sql);

        while ($row = mysqli_fetch_array($result)) {
            if($row['userSet'] != "-1") {
                $response3["Exercise"] = $row['userExercise'];
                $response3["Category"] = $row['category'];
                $response3["Date"] = $row['userDate'];
                $response3["Set"] = $row['userSet'];
                $response3["Weight"] = $row['userWeight'];
                $response3["Count"] = $row['userCount'];
                $response3["Time"] = $row['userTime'];
            }
            else {
                $response3["Exercise"] = $row['userExercise'];
                $response3["Date"] = $row['userDate'];
                $response3["Distance"] = $row['userDistance'];
                $response3["Time"] = $row['userTime'];
            }

            $test10[] = $response3;
        }
        //$test10["All"] = $test11;

        //$response2["My_Ranking"] = $row3['Ranking'];
        //$response2["My_Score"] = $row3['Score'];

        //$test10["My"] = $response2;

        $json = json_encode($test10, JSON_UNESCAPED_UNICODE);
        echo $json;
    }
?>