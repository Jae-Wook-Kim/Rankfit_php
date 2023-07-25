<?php
    require("dbset.php");

    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con, 'SET NAMES utf8');

    $userID = $_POST["userID"] ?? "";
    $userExercise = $_POST["userExercise"] ?? "";
    $userDate = $_POST["userDate"] ?? "";
    $eng = $_POST["eng"] ?? "";
    $ko = $_POST["ko"] ?? "";

    $userSex = $_POST["userSex"] ?? "";

    $response = false;

    if (!empty($userID)) {
        $statement = mysqli_prepare($con, "DELETE FROM anaerobicTBL WHERE userID = ? AND userExercise = ? AND userDate = ?");
        mysqli_stmt_bind_param($statement, "sss", $userID, $userExercise, $userDate);
        mysqli_stmt_execute($statement);
        mysqli_stmt_close($statement);

        $sql = "DELETE FROM $userID WHERE userExercise = ? AND userDate = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $userExercise, $userDate);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $response = true;
    }

    if (!empty($userID)) {
        $sql20 = "UPDATE userTBL 
                SET userTBL.anaerobicScore = (SELECT SUM(anaerobicTBL.Score) 
                                            FROM anaerobicTBL 
                                            WHERE anaerobicTBL.userID = userTBL.userID)";

        $sql30 = "UPDATE userTBL 
                SET userTBL.Score = (userTBL.anaerobicScore + userTBL.aerobicScore)";

        mysqli_query($con, $sql20);
        mysqli_query($con, $sql30);

        if ($userSex == 0) {
            $sql40 = "UPDATE MaleTBL 
                    SET MaleTBL.Score = (SELECT userTBL.Score 
                                        FROM userTBL 
                                        WHERE userTBL.userID = MaleTBL.userID)";
            mysqli_query($con, $sql40);
        } else {
            $sql50 = "UPDATE FemaleTBL 
                    SET FemaleTBL.Score = (SELECT userTBL.Score 
                                            FROM userTBL 
                                            WHERE userTBL.userID = FemaleTBL.userID)";
            mysqli_query($con, $sql50);
        }
    }

    $sql11 = "UPDATE $eng a 
            LEFT OUTER JOIN (
                SELECT userID, SUM(Score) as tmp 
                FROM anaerobicTBL 
                WHERE userExercise = ? 
                GROUP BY userID
            ) b ON b.userID = a.userID 
            SET a.Score = b.tmp";

    $stmt11 = mysqli_prepare($con, $sql11);
    mysqli_stmt_bind_param($stmt11, "s", $ko);
    mysqli_stmt_execute($stmt11);
    mysqli_stmt_close($stmt11);

    echo json_encode($response);
?>