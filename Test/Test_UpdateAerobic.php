<?php
    require("dbset.php");

    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con, 'SET NAMES utf8');

    // Set default values using an array
    $defaults = [
        "userDistance" => -1,
        "Score" => "",
        "userTime" => -1,
        "userState" => "",
        "userID" => "",
        "userDate" => "",
        "userSex" => 0,
        "eng" => "",
        "ko" => "",
        "uuid" => "",
    ];

    // Fetch values from $_POST using short-circuiting with the ternary operator
    extract(array_map(fn($key) => isset($_POST[$key]) ? $_POST[$key] : $defaults[$key], array_keys($defaults)));

    $CustomRank = 0;
    $response = false;

    // Use prepared statement to avoid SQL injection
    $createTableQuery = "CREATE TABLE IF NOT EXISTS $eng (
        userID varchar(30) NOT NULL,
        userSex int NOT NULL,
        userAge int NOT NULL,
        userWD varchar(10) NOT NULL,
        Score float NOT NULL,
        CustomRank int NULL,
        PRIMARY KEY (userID)
    )";
    mysqli_query($con, $createTableQuery);

    // Use prepared statement to avoid SQL injection and fetch userAge and userWD
    $selectUserInfoQuery = "SELECT userAge, userWD FROM userTBL WHERE userID = ?";
    $stmt = mysqli_prepare($con, $selectUserInfoQuery);
    mysqli_stmt_bind_param($stmt, "s", $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $age, $WD);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Use prepared statement to avoid SQL injection and insert data
    $insertDataQuery = "INSERT INTO $eng (userID, userSex, userAge, userWD, Score, CustomRank) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $insertDataQuery);
    mysqli_stmt_bind_param($stmt, "siisdi", $userID, $userSex, $age, $WD, $Score, $CustomRank);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($userSex != "") {
        // Use prepared statement to avoid SQL injection and update data
        $updateDataQuery = "UPDATE $userID SET userDate = ?, userDistance = ?, userTime = ?, userState = ? WHERE uuid = ?";
        $stmt = mysqli_prepare($con, $updateDataQuery);
        mysqli_stmt_bind_param($stmt, "siiss", $userDate, $userDistance, $userTime, $userState, $uuid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $updateAerobicTBLQuery = "UPDATE aerobicTBL SET userDate = ?, userDistance = ?, userTime = ?, Score = ?, userState = ? WHERE uuid = ?";
        $stmt = mysqli_prepare($con, $updateAerobicTBLQuery);
        mysqli_stmt_bind_param($stmt, "siisss", $userDate, $userDistance, $userTime, $Score, $userState, $uuid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $response = true;

        $sql2 = "UPDATE userTBL SET userTBL.aerobicScore = (SELECT SUM(aerobicTBL.Score) FROM aerobicTBL WHERE aerobicTBL.userID = userTBL.userID)";
        mysqli_query($con, $sql2);

        $sql3 = "UPDATE userTBL SET userTBL.Score = (SELECT SUM(userTBL.anaerobicScore + userTBL.aerobicScore))";
        mysqli_query($con, $sql3);

        $updateScoreQuery = ($userSex == 0) ? "UPDATE MaleTBL SET MaleTBL.Score = (SELECT userTBL.Score FROM userTBL WHERE userTBL.userID = MaleTBL.userID)"
                                        : "UPDATE FemaleTBL SET FemaleTBL.Score = (SELECT userTBL.Score FROM userTBL WHERE userTBL.userID = FemaleTBL.userID)";
        mysqli_query($con, $updateScoreQuery);
    }

    $sql11 = "UPDATE $eng a LEFT OUTER JOIN (SELECT userID, SUM(Score) as tmp FROM aerobicTBL WHERE userExercise = ? GROUP BY userID) b on b.userID = a.userID SET a.Score = b.tmp";
    $stmt = mysqli_prepare($con, $sql11);
    mysqli_stmt_bind_param($stmt, "s", $ko);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo json_encode($response);

    mysqli_close($con);
?>