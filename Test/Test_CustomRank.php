<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con, 'SET NAMES utf8');

    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    $userSex = isset($_POST["userSex"]) ? $_POST["userSex"] : 0;
    $userAge = isset($_POST["userAge"]) ? (int)$_POST["userAge"] : 0;
    $age = (int)($userAge / 10) * 10;
    $age2 = $age + 10;

    // Update userAge for the given userID
    $sql6 = "UPDATE userTBL SET userAge = ? WHERE userID = ?";
    $stmt6 = mysqli_prepare($con, $sql6);
    mysqli_stmt_bind_param($stmt6, "is", $userAge, $userID);
    mysqli_stmt_execute($stmt6);

    // Fetch the userWD for the given userID
    $sql5 = "SELECT userWD FROM userTBL WHERE userID = ?";
    $stmt5 = mysqli_prepare($con, $sql5);
    mysqli_stmt_bind_param($stmt5, "s", $userID);
    mysqli_stmt_execute($stmt5);
    mysqli_stmt_bind_result($stmt5, $WD);
    mysqli_stmt_fetch($stmt5);
    mysqli_stmt_close($stmt5);

    // Update CustomRank using JOIN
    $sql3 = "UPDATE userTBL a 
        INNER JOIN (
            SELECT userID, Score, dense_rank() OVER (ORDER BY Score DESC) AS Ranking 
            FROM userTBL 
            WHERE userAge >= ? AND userAge < ? AND userWD = ? AND userSex = ?
        ) b ON b.userID = a.userID 
        SET a.CustomRank = b.Ranking";
    $stmt3 = mysqli_prepare($con, $sql3);
    mysqli_stmt_bind_param($stmt3, "iiss", $age, $age2, $WD, $userSex);
    mysqli_stmt_execute($stmt3);
    mysqli_stmt_close($stmt3);

    // Fetch the top 100 users with their rankings
    $sql = "SELECT userID, Score, dense_rank() OVER (ORDER BY Score DESC) AS CustomRank, 
            (SELECT userNickname FROM userTBL WHERE userID = t.userID) AS userNickname 
            FROM userTBL t
            WHERE userAge >= ? AND userAge < ? AND userWD = ? AND userSex = ? AND Score != 0 
            LIMIT 100";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "iiss", $age, $age2, $WD, $userSex);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userID, $score, $customRank, $userNickname);

    $test11 = array();
    $test10 = array();

    // Fetch all the top users
    while (mysqli_stmt_fetch($stmt)) {
        $response3 = array(
            "Ranking" => $customRank,
            "Nickname" => $userNickname,
            "Score" => $score
        );
        $test11[] = $response3;
    }
    mysqli_stmt_close($stmt);

    $test10["All"] = $test11;

    // Fetch user's ranking and score
    $sql4 = "SELECT Score, CustomRank FROM userTBL WHERE userID = ?";
    $stmt4 = mysqli_prepare($con, $sql4);
    mysqli_stmt_bind_param($stmt4, "s", $userID);
    mysqli_stmt_execute($stmt4);
    mysqli_stmt_bind_result($stmt4, $userScore, $userRank);
    mysqli_stmt_fetch($stmt4);
    mysqli_stmt_close($stmt4);

    $response2 = array(
        "My_Ranking" => $userRank,
        "My_Score" => $userScore
    );

    $test10["My"] = $response2;

    // Output the JSON response
    $json = json_encode($test10, JSON_UNESCAPED_UNICODE);
    echo $json;
?>