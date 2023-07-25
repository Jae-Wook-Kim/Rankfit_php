<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con, 'SET NAMES utf8');

    $response = false;

    // Assuming these fields are required for the operation.
    $userID = $_POST["userID"] ?? "";
    $userExercise = $_POST["userExercise"] ?? "";
    $userDate = $_POST["userDate"] ?? "";

    // Use prepared statement for DELETE query
    if (!empty($userID) && !empty($userExercise) && !empty($userDate)) {
        $deleteStatement = mysqli_prepare($con, "DELETE FROM aerobicTBL WHERE userID = ? AND userExercise = ? AND userDate = ?");
        mysqli_stmt_bind_param($deleteStatement, "sss", $userID, $userExercise, $userDate);
        mysqli_stmt_execute($deleteStatement);

        // You might want to handle errors here if needed.
        mysqli_stmt_close($deleteStatement);

        $deleteSql = "DELETE FROM $userID WHERE userExercise = '$userExercise' AND userDate = '$userDate'";
        mysqli_query($con, $deleteSql);

        $response = true;
    }

    if (!empty($userID)) {
        // Combine SQL updates for efficiency
        $updateScoreSql = "UPDATE userTBL SET userTBL.aerobicScore = (SELECT SUM(aerobicTBL.Score) FROM aerobicTBL WHERE aerobicTBL.userID = userTBL.userID)";
        $updateScoreSql .= ", userTBL.Score = (SELECT SUM(userTBL.anaerobicScore + userTBL.aerobicScore))";
        mysqli_query($con, $updateScoreSql);

        if (isset($_POST["userSex"]) && $_POST["userSex"] == 0) {
            $updateScoreSql = "UPDATE MaleTBL SET MaleTBL.Score = (SELECT userTBL.Score FROM userTBL WHERE userTBL.userID = MaleTBL.userID)";
            mysqli_query($con, $updateScoreSql);
        } elseif ($_POST["userSex"] == 1) {
            $updateScoreSql = "UPDATE FemaleTBL SET FemaleTBL.Score = (SELECT userTBL.Score FROM userTBL WHERE userTBL.userID = FemaleTBL.userID)";
            mysqli_query($con, $updateScoreSql);
        }
    }

    // Use prepared statement for UPDATE query
    if (!empty($_POST["eng"]) && !empty($_POST["ko"])) {
        $updateEngScoreStatement = mysqli_prepare($con, "UPDATE ? a LEFT OUTER JOIN (SELECT userID, SUM(Score) as tmp FROM aerobicTBL WHERE userExercise = ? GROUP BY userID) b ON b.userID = a.userID SET a.Score = b.tmp");
        mysqli_stmt_bind_param($updateEngScoreStatement, "ss", $_POST["eng"], $_POST["ko"]);
        mysqli_stmt_execute($updateEngScoreStatement);

        // You might want to handle errors here if needed.
        mysqli_stmt_close($updateEngScoreStatement);
    }

    echo json_encode($response);
?>