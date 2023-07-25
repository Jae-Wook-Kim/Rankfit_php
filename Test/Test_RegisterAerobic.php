<?php
    require("dbset.php");

    // Make sure to validate and sanitize user inputs before using them in the query.
    $userID = isset($_POST["userID"]) ? mysqli_real_escape_string($con, $_POST["userID"]) : "";
    $category = "유산소"; // Assuming this is a fixed value.
    $userExercise = isset($_POST["userExercise"]) ? mysqli_real_escape_string($con, $_POST["userExercise"]) : "";
    $userDate = isset($_POST["userDate"]) ? mysqli_real_escape_string($con, $_POST["userDate"]) : "";
    $userDistance = isset($_POST["userDistance"]) ? mysqli_real_escape_string($con, $_POST["userDistance"]) : "";
    $userTime = isset($_POST["userTime"]) ? intval($_POST["userTime"]) : -1; // Sanitize as integer.
    $Score = isset($_POST["Score"]) ? intval($_POST["Score"]) : 0; // Sanitize as integer.
    $userState = isset($_POST["userState"]) ? intval($_POST["userState"]) : 0; // Sanitize as integer.
    $uuid = isset($_POST["uuid"]) ? mysqli_real_escape_string($con, $_POST["uuid"]) : "";

    $userSet = isset($_POST["userSet"]) ? intval($_POST["userSet"]) : -1; // Sanitize as integer.
    $userWeight = isset($_POST["userWeight"]) ? floatval($_POST["userWeight"]) : -1; // Sanitize as float.
    $userCount = isset($_POST["userCount"]) ? intval($_POST["userCount"]) : -1; // Sanitize as integer.

    // Avoid using NULL for auto-incrementing primary key fields.
    $Index = ''; // Assuming it's an auto-incrementing primary key.

    // Insert data into aerobicTBL.
    $sql = "INSERT INTO aerobicTBL (userID, userExercise, userDate, userDistance, userTime, Score, userState, uuid) 
            VALUES ('$userID', '$userExercise', '$userDate', '$userDistance', $userTime, $Score, $userState, '$uuid')";

    $ret = mysqli_query($con, $sql);

    if ($ret) {
        echo "Data Insert Success.";
    } else {
        echo "Data Insert Failed!!!";
    }

    // Create a separate table for each user is not recommended, consider using one table with appropriate columns.
    // However, if you still want to proceed with creating separate tables, make sure to validate and sanitize the $userID before using it in the query.
    $tableName = mysqli_real_escape_string($con, $userID);

    $result3 = mysqli_query($con, "SHOW TABLES LIKE '$tableName'");

    if (mysqli_num_rows($result3) > 0) {
        $sql10 = "INSERT INTO $tableName (Index, userExercise, category, userDate, userSet, userWeight, userCount, userDistance, userTime, userState, uuid) 
                VALUES ('$Index', '$userExercise', '$category', '$userDate', $userSet, $userWeight, $userCount, '$userDistance', $userTime, $userState, '$uuid')";

        mysqli_query($con, $sql10);
    }

    mysqli_close($con);
?>