<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $userID = isset($_POST["userID"]) ? $_POST["userID"] : "";
    $userSex = isset($_POST["userSex"]) ? $_POST["userSex"] : 0;

    //$response = false;
    $test10 = array();
    $test11 = [];
    $response3 = array();
    $response2 = array();

    if($userSex == 0) {
        $sql3 = "UPDATE MaleTBL a inner join (SELECT userID, Score, dense_rank() over (order by Score desc) as Ranking FROM MaleTBL) b on b.userID = a.userID SET a.Ranking = b.Ranking";
        $result3 = mysqli_query($con,$sql3);

        $sql = "SELECT userID, Score, dense_rank() over (order by Score desc) as Ranking FROM MaleTBL LIMIT 100";
        $result = mysqli_query($con,$sql);
        //$row_ID = mysqli_fetch_array($result);

        //$sql3 = "UPDATE MaleTBL a inner join (SELECT userID, Score, dense_rank() over (order by Score desc) as Ranking FROM MaleTBL) b on b.userID = a.userID SET a.Ranking = b.Ranking";
        //$result3 = mysqli_query($con,$sql3);
        //$row3 = mysqli_fetch_array($result3);

        $sql4 = "SELECT Score, Ranking FROM MaleTBL WHERE userID = '$userID'";
        $result4 = mysqli_query($con,$sql4);
        $row3 = mysqli_fetch_array($result4);

        while ($row = mysqli_fetch_array($result)) {
            $ID = $row['userID'];
            $sql2 = "SELECT userNickname FROM userTBL WHERE userID = '$ID'";
            $result2 = mysqli_query($con,$sql2);
            
            $row2 = mysqli_fetch_array($result2);

            $response3["Ranking"] = $row['Ranking'];
            $response3["Nickname"] = $row2['userNickname'];
            $response3["Score"] = $row['Score'];
            
            //var_dump($response3["Ranking"]);
            //var_dump($response3["Nickname"]);
            //var_dump($response3["Score"]);
            
            //echo json_encode($response3);
            $test11[] = $response3;
            
            //echo $row['Ranking'];
            //echo $row['Score'];
        }
        $test10["All"] = $test11;

        $response2["My_Ranking"] = $row3['Ranking'];
        $response2["My_Score"] = $row3['Score'];

        $test10["My"] = $response2;

        $json = json_encode($test10, JSON_UNESCAPED_UNICODE);
        echo $json;
        //echo json_encode($test10);
        //$response = true;
    }
    else {
        $sql3 = "UPDATE FemaleTBL a inner join (SELECT userID, Score, dense_rank() over (order by Score desc) as Ranking FROM FemaleTBL) b on b.userID = a.userID SET a.Ranking = b.Ranking";
        $result3 = mysqli_query($con,$sql3);

        $sql = "SELECT userID, Score, dense_rank() over (order by Score desc) as Ranking FROM FemaleTBL LIMIT 100";
        $result = mysqli_query($con,$sql);
        //$row_ID = mysqli_fetch_array($result);
        
        //$test10 = [];
        //$response3 = array();

        $sql4 = "SELECT Score, Ranking FROM FemaleTBL WHERE userID = '$userID'";
        $result4 = mysqli_query($con,$sql4);
        $row3 = mysqli_fetch_array($result4);

        while ($row = mysqli_fetch_array($result)) {
            $ID = $row['userID'];
            $sql2 = "SELECT userNickname FROM userTBL WHERE userID = '$ID'";
            $result2 = mysqli_query($con,$sql2);
            
            $row2 = mysqli_fetch_array($result2);

            $response3["Ranking"] = $row['Ranking'];
            $response3["Nickname"] = $row2['userNickname'];
            $response3["Score"] = $row['Score'];
            //echo json_encode($response3);
            $test11[] = $response3;
            
            //echo $row['Ranking'];
            //echo $row['Score'];
        }
        $test10["All"] = $test11;

        $response2["My_Ranking"] = $row3['Ranking'];
        $response2["My_Score"] = $row3['Score'];

        $test10["My"] = $response2;

        $json = json_encode($test10, JSON_UNESCAPED_UNICODE);
        echo $json;
    }   
    //echo json_encode($response);
?>