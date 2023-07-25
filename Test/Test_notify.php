<?php
    require("dbset.php");

    try {
        $con = new mysqli($db_host, $db_user, $db_password, $db_name);
        if ($con->connect_error) {
            throw new Exception("MySQL Connection Failed: " . $con->connect_error);
        }
        $con->set_charset("utf8");

        $nickname = isset($_POST["nickname"]) ? $_POST["nickname"] : "";
        $reason = isset($_POST["reason"]) ? $_POST["reason"] : "";
        $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
        $date = isset($_POST["date"]) ? $_POST["date"] : "";

        $response = false;

        $sql = "SELECT u.userID FROM userTBL u LEFT JOIN notify n ON u.userID = n.ID 
                WHERE u.userNickname = ? AND n.reason = ? AND n.userID = ?";
        $statement = $con->prepare($sql);
        $statement->bind_param("sss", $nickname, $reason, $userID);
        $statement->execute();
        $statement->bind_result($ID);
        $statement->fetch();
        $statement->close();

        if ($ID) {
            echo "Already reported it!!";
        } else {
            $sql = "INSERT INTO notify (ID, reason, userID, date) VALUES (?,?,?,?)";
            $statement = $con->prepare($sql);
            $statement->bind_param("ssss", $Index, $reason, $userID, $date);
            $response = $statement->execute();
            $statement->close();
        }

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(false); // Return false in case of any error.
    }
?>