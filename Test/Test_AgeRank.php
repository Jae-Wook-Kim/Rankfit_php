<?php
    require("dbset.php");

    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name);
    if (!$con) {
        die("MySQL Connection Failed: " . mysqli_connect_error());
    }

    mysqli_query($con, 'SET NAMES utf8');

    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    $userAge = isset($_POST["userAge"]) ? (int)$_POST["userAge"] : 0;
    $age = (int)($userAge / 10) * 10;
    $age2 = $age + 10;

    // Update user's age
    $sql5 = "UPDATE userTBL SET userAge = ? WHERE userID = ?";
    $stmt5 = mysqli_prepare($con, $sql5);
    mysqli_stmt_bind_param($stmt5, "is", $userAge, $userID);
    mysqli_stmt_execute($stmt5);

    // Update user's AgeRank
    $sql3 = "UPDATE userTBL a INNER JOIN (SELECT userID, Score, dense_rank() OVER (ORDER BY Score DESC) AS Ranking FROM userTBL WHERE userAge >= ? AND userAge < ?) b ON b.userID = a.userID SET a.AgeRank = b.Ranking";
    $stmt3 = mysqli_prepare($con, $sql3);
    mysqli_stmt_bind_param($stmt3, "ii", $age, $age2);
    mysqli_stmt_execute($stmt3);

    // Fetch top 100 rankings
    $sql = "SELECT userID, Score, dense_rank() OVER (ORDER BY Score DESC) AS AgeRank FROM userTBL WHERE userAge >= ? AND userAge < ? AND Score != 0 LIMIT 100";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $age, $age2);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userID, $Score, $AgeRank);

    $result = [];
    while (mysqli_stmt_fetch($stmt)) {
        $result[] = [
            "userID" => $userID,
            "Score" => $Score,
            "AgeRank" => $AgeRank
        ];
    }

    // Fetch user's Score and AgeRank
    $sql4 = "SELECT Score, AgeRank FROM userTBL WHERE userID = ?";
    $stmt4 = mysqli_prepare($con, $sql4);
    mysqli_stmt_bind_param($stmt4, "s", $userID);
    mysqli_stmt_execute($stmt4);
    mysqli_stmt_bind_result($stmt4, $Score, $AgeRank);
    mysqli_stmt_fetch($stmt4);

    $test11 = [];
    foreach ($result as $row) {
        $ID = $row['userID'];
        $sql2 = "SELECT userNickname FROM userTBL WHERE userID = ?";
        $stmt2 = mysqli_prepare($con, $sql2);
        mysqli_stmt_bind_param($stmt2, "s", $ID);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_bind_result($stmt2, $userNickname);
        mysqli_stmt_fetch($stmt2);

        $response3 = [
            "Ranking" => $row['AgeRank'],
            "Nickname" => $userNickname,
            "Score" => $row['Score']
        ];

        $test11[] = $response3;
    }

    $test10 = [
        "All" => $test11,
        "My" => [
            "My_Ranking" => (string)$AgeRank,
            "My_Score" => (string)$Score
        ]
    ];

    $json = json_encode($test10, JSON_UNESCAPED_UNICODE);
    echo $json;
?>