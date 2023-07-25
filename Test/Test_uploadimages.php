<?php
    require("dbset.php");

    // Ensure the script only handles POST requests
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        die("Invalid request method");
    }

    // Validate and sanitize user input
    $name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
    $data = isset($_POST["data"]) ? $_POST["data"] : "";

    if (empty($name) || empty($data)) {
        die("Please provide both name and data");
    }

    // Establish database connection
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name);
    if (!$con) {
        die("MySQL Connection Failed: " . mysqli_connect_error());
    }

    // Set utf8 character encoding
    mysqli_query($con, 'SET NAMES utf8');

    // Use prepared statement to prevent SQL injection
    $sql = "INSERT INTO images (name, data) VALUES (?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        die("Prepared statement failed: " . mysqli_error($con));
    }

    // Bind parameters and execute the query
    mysqli_stmt_bind_param($stmt, "ss", $name, $data);
    if (!mysqli_stmt_execute($stmt)) {
        die("Error executing the query: " . mysqli_stmt_error($stmt));
    }

    // Close the statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($con);

    echo "Image has been uploaded";
?>