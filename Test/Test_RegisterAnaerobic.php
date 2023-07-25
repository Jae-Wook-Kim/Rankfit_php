<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");

    // Use prepared statements to prevent SQL injection
    $Index = NULL;
    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    $category = isset($_POST["category"]) ? $_POST["category"] : "";
    $userExercise = isset($_POST["userExercise"]) ? $_POST["userExercise"] : "";
    $userDate = isset($_POST["userDate"]) ? $_POST["userDate"] : "";
    $userSet = isset($_POST["userSet"]) ? $_POST["userSet"] : "";
    $userWeight = isset($_POST["userWeight"]) ? $_POST["userWeight"] : "";
    $userCount = isset($_POST["userCount"]) ? $_POST["userCount"] : "";
    $exTime = isset($_POST["exTime"]) ? $_POST["exTime"] : "";
    $Score = isset($_POST["Score"]) ? $_POST["Score"] : 0;
    $userTime = isset($_POST["userTime"]) ? $_POST["userTime"] : -1;
    $userState = isset($_POST["userState"]) ? $_POST["userState"] : 0;
    $uuid = isset($_POST["uuid"]) ? $_POST["uuid"] : "";
    $userDistance = isset($_POST["userDistance"]) ? $_POST["userDistance"] : -1;

    // Prepare the SQL statement using prepared statements
    $sql = "INSERT INTO anaerobicTBL VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param(
            $stmt,
            "isssssssssis",
            $Index,
            $userID,
            $category,
            $userExercise,
            $userDate,
            $userSet,
            $userWeight,
            $userCount,
            $exTime,
            $Score,
            $userTime,
            $userState,
            $uuid
        );

        // Execute the prepared statement
        $ret = mysqli_stmt_execute($stmt);

        if ($ret) {
            echo "Data Insert Success.";
        } else {
            echo "Data Insert Failed!!!";
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    }

    // Insert into a table named after userID may not be a good practice.
    // Instead, consider normalizing your database structure.

    // Close the database connection
    mysqli_close($con);
?>