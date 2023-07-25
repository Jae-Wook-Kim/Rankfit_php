<?php
    require("dbset.php");

    // Check if the userID is provided via POST and is not empty
    if (isset($_POST["userID"]) && !empty($_POST["userID"])) {
        $userID = $_POST["userID"];

        // Use prepared statements to prevent SQL injection
        $con = mysqli_connect($db_host, $db_user, $db_password, $db_name);
        if (!$con) {
            throw new Exception("MySQL Connection Failed: " . mysqli_connect_error());
        }

        // Prepare and execute a single DELETE query for all tables
        $sql = "SHOW TABLES";
        $result = mysqli_query($con, $sql);
        if (!$result) {
            throw new Exception("Error fetching tables: " . mysqli_error($con));
        }

        $tablesToDeleteFrom = array();
        while ($row = mysqli_fetch_array($result)) {
            $TBL = $row['Tables_in_dbmate'];
            $tablesToDeleteFrom[] = $TBL;
        }

        // Use a single DELETE query to delete data from all relevant tables
        if (!empty($tablesToDeleteFrom)) {
            $placeholders = implode(", ", array_fill(0, count($tablesToDeleteFrom), "?"));
            $sql = "DELETE FROM $placeholders WHERE userID = ?";
            $stmt = mysqli_prepare($con, $sql);

            if (!$stmt) {
                throw new Exception("Error preparing DELETE statement: " . mysqli_error($con));
            }

            // Bind the userID parameter to the prepared statement
            mysqli_stmt_bind_param($stmt, str_repeat("s", count($tablesToDeleteFrom) + 1), ...$tablesToDeleteFrom, $userID);

            // Execute the DELETE statement
            if (mysqli_stmt_execute($stmt)) {
                echo "DELETE success";
            } else {
                echo "DELETE fail";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "No tables found";
        }

        mysqli_close($con);
    } else {
        echo "UserID not provided or empty.";
    }
?>