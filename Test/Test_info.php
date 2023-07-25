<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $userNickname = isset($_POST["userNickname"]) ? $_POST["userNickname"] : "";
    $start = isset($_POST["start"]) ? $_POST["start"] : 0;
    $end = isset($_POST["end"]) ? $_POST["end"] : 0;

    if (!empty($userNickname)) {
        $userNickname = mysqli_real_escape_string($con, $userNickname); // Sanitize user input

        $sql = "SELECT userID FROM userTBL WHERE userNickname = '$userNickname'";
        $result = mysqli_query($con, $sql);

        if ($row = mysqli_fetch_array($result)) {
            $ID = $row['userID'];

            $sql = "SELECT * FROM $ID WHERE userDate > '$start' AND userDate < '$end'";
            $result = mysqli_query($con, $sql);

            $response = array();
            $response['Anaerobics'] = array();
            $response['Aerobics'] = array();

            while ($row = mysqli_fetch_array($result)) {
                if ($row['userState'] == "1") {
                    $data = array(
                        'Exercise' => $row['userExercise'],
                        'Date' => $row['userDate'],
                    );

                    if ($row['userSet'] != "-1") {
                        $data['Category'] = $row['category'];
                        $data['Set'] = $row['userSet'];
                        $data['Weight'] = $row['userWeight'];
                        $data['Count'] = $row['userCount'];
                        $data['Time'] = $row['userTime'];
                        $response['Anaerobics'][] = $data;
                    } else {
                        $data['Distance'] = $row['userDistance'];
                        $data['Time'] = $row['userTime'];
                        $response['Aerobics'][] = $data;
                    }
                }
            }

            $json = json_encode($response, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            echo $json;
        }
    }
?>