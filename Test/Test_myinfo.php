<?php
    require("dbset.php");

    // Database connection
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name);

    // Check for database connection errors
    if (mysqli_connect_errno()) {
        die("MySQL Connection Failed: " . mysqli_connect_error());
    }

    mysqli_query($con, 'SET NAMES utf8');

    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    $uuid = isset($_POST["uuid"]) ? $_POST["uuid"] : "";
    $response3 = array();

    if (!empty($uuid) && !empty($userID)) {

        $stmt = mysqli_prepare($con, "SELECT * FROM $userID WHERE uuid = ?");
        mysqli_stmt_bind_param($stmt, "s", $uuid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $response3["Exercise"] = $row['userExercise'];
            $response3["Date"] = $row['userDate'];
            if ($row['userSet'] != "-1") {
                $response3["Category"] = $row['category'];
                $response3["Set"] = $row['userSet'];
                $response3["Weight"] = $row['userWeight'];
                $response3["Count"] = $row['userCount'];
            } else {
                $response3["Distance"] = $row['userDistance'];
            }
            $response3["Time"] = $row['userTime'];
            $test10[] = $response3;
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);

        if (!empty($test10)) {
            echo json_encode($test10, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(array("message" => "No data found"), JSON_UNESCAPED_UNICODE);
        }

    } else {
        echo json_encode(array("message" => "Invalid parameters"), JSON_UNESCAPED_UNICODE);
    }

    // Close the database connection
    mysqli_close($con);
?>