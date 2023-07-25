<?php
    require("dbset.php");

    // Establish database connection
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con, 'SET NAMES utf8');

    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";

    // Create 'running' table if it doesn't exist
    $result = mysqli_query($con, "SHOW TABLES LIKE 'running'");
    if (!mysqli_num_rows($result) > 0) {
        $sql6 = "CREATE TABLE running (
            userID varchar(30) NOT NULL,
            userSex int NOT NULL,
            userAge int NOT NULL,
            userWD varchar(10) NOT NULL,
            Score float NOT NULL,
            CustomRank int NULL,
            PRIMARY KEY (userID)
        )";
        mysqli_query($con, $sql6);
    }

    // Fetch the ranking data
    $sql = "SELECT r.userID, SUM(r.Score) AS tmp, u.userNickname 
            FROM running AS r
            LEFT JOIN userTBL AS u ON r.userID = u.userID
            WHERE r.Score != 0
            GROUP BY r.userID
            ORDER BY tmp DESC
            LIMIT 100";
    $result = mysqli_query($con, $sql);

    $test10 = array();
    $response2 = array();
    $count = 0;

    while ($row = mysqli_fetch_array($result)) {
        $count++;

        $ID = $row['userID'];
        if ($userID == $ID) {
            $response2["My_Ranking"] = (string)$count;
            $response2["My_Score"] = $row['tmp'];
        }

        $response3 = array();
        $response3["Nickname"] = $row['userNickname'];
        $response3["Score"] = $row['tmp'];
        $response3["Ranking"] = (string)$count;

        $test10["All"][] = $response3;
    }

    // Add default values for "My" if the user has no data in the ranking
    if (!isset($response2["My_Ranking"])) {
        $response2["My_Ranking"] = "0";
        $response2["My_Score"] = "0";
    }

    $test10["My"] = $response2;

    // Add default values for "All" if there is no ranking data
    if (empty($test10["All"])) {
        $sql3 = "SELECT userNickname FROM userTBL WHERE userID = '$userID'";
        $result3 = mysqli_query($con, $sql3);
        $row3 = mysqli_fetch_array($result3);

        $response3 = array();
        $response3["Nickname"] = $row3['userNickname'];
        $response3["Score"] = "0";
        $response3["Ranking"] = "0";

        $test10["All"][] = $response3;
    }

    // Encode the result as JSON and send the response
    $json = json_encode($test10, JSON_UNESCAPED_UNICODE);
    echo $json;

    mysqli_close($con);
?>