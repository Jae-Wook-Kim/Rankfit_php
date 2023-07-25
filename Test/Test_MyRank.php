<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con, 'SET NAMES utf8');

    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    $userSex = isset($_POST["userSex"]) ? $_POST["userSex"] : 0;
    $userAge = isset($_POST["userAge"]) ? (int)$_POST["userAge"] : 0;
    $age = (int)($userAge / 10) * 10;
    $age2 = $age + 10;
    $eng = isset($_POST["eng"]) ? $_POST["eng"] : "";
    $kor = isset($_POST["kor"]) ? $_POST["kor"] : "";

    // Sanitize input data to prevent SQL injection
    $userID = mysqli_real_escape_string($con, $userID);
    $eng = mysqli_real_escape_string($con, $eng);
    $kor = mysqli_real_escape_string($con, $kor);

    // Update userTBL with userAge
    $sql6 = "UPDATE userTBL SET userAge = '$userAge' WHERE userID = '$userID'";
    mysqli_query($con, $sql6);

    // Fetch userWD from $eng table
    $sql5 = "SELECT userWD FROM $eng WHERE userID = '$userID'";
    $result5 = mysqli_query($con, $sql5);
    $row5 = mysqli_fetch_array($result5);
    $WD = $row5['userWD'];

    // Update CustomRank using a JOIN
    $sql3 = "UPDATE $eng a
            INNER JOIN (SELECT userID, Score, dense_rank() OVER (ORDER BY Score DESC) AS Ranking
                        FROM $eng
                        WHERE userAge >= '$age' AND userAge < '$age2' AND userWD = '$WD' AND userSex = '$userSex' AND Score != 0) b
            ON b.userID = a.userID
            SET a.CustomRank = b.Ranking";
    mysqli_query($con, $sql3);

    // Fetch CustomRank for the current user
    $sql4 = "SELECT CustomRank FROM $eng WHERE userID = '$userID'";
    $result4 = mysqli_query($con, $sql4);
    $row3 = mysqli_fetch_array($result4);

    $response2 = array();
    $response2["My_Ranking"] = $row3['CustomRank'];
    $response2["Exercise"] = $kor;

    $json = json_encode($response2, JSON_UNESCAPED_UNICODE);
    echo $json;

    // Close the database connection
    mysqli_close($con);
?>