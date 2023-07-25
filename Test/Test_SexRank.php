<?php
    require("dbset.php");

    function getNickname($con, $userID) {
        $sql = "SELECT userNickname FROM userTBL WHERE userID = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $userNickname);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        return $userNickname;
    }

    function fetchAndEncodeData($con, $sql) {
        $result = mysqli_query($con, $sql);

        $test11 = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response3 = array(
                "Ranking" => $row['Ranking'],
                "Nickname" => getNickname($con, $row['userID']),
                "Score" => $row['Score']
            );
            $test11[] = $response3;
        }
        return $test11;
    }

    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con, 'SET NAMES utf8');

    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    $userSex = isset($_POST["userSex"]) ? $_POST["userSex"] : 0;

    if ($userSex == 0) {
        // Male users
        $sql3 = "UPDATE MaleTBL a INNER JOIN (SELECT userID, Score, dense_rank() OVER (ORDER BY Score DESC) AS Ranking FROM MaleTBL) b ON b.userID = a.userID SET a.Ranking = b.Ranking";
        $result3 = mysqli_query($con, $sql3);

        $sql = "SELECT userID, Score, Ranking FROM MaleTBL WHERE Score != 0 ORDER BY Score DESC LIMIT 100";
    } else {
        // Female users
        $sql3 = "UPDATE FemaleTBL a INNER JOIN (SELECT userID, Score, dense_rank() OVER (ORDER BY Score DESC) AS Ranking FROM FemaleTBL) b ON b.userID = a.userID SET a.Ranking = b.Ranking";
        $result3 = mysqli_query($con, $sql3);

        $sql = "SELECT userID, Score, Ranking FROM FemaleTBL ORDER BY Score DESC LIMIT 100";
    }

    $response2 = array();
    $sql4 = "SELECT Score, Ranking FROM " . ($userSex == 0 ? "MaleTBL" : "FemaleTBL") . " WHERE userID = ?";
    $stmt = mysqli_prepare($con, $sql4);
    mysqli_stmt_bind_param($stmt, "s", $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $myScore, $myRanking);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $test11 = fetchAndEncodeData($con, $sql);
    $test10 = array("All" => $test11, "My" => array("My_Ranking" => $myRanking, "My_Score" => $myScore));

    $json = json_encode($test10, JSON_UNESCAPED_UNICODE);
    echo $json;
?>