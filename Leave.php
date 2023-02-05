<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    $userID = $_POST["userID"];
    //본인 TBL 삭제
    $sql = "DROP TABLE $userID";
    $ret = mysqli_query($con, $sql);
    if($ret) {
        echo "DROP success";
    }
    else {
        echo "DROP fail";
        //echo "case :".mysqli_error($con);
    }
    //mysqli_close($con);

    //$response = false;

    //모든 테이블 검색
    $sql2 = "SHOW TABLES";
    $ret2 = mysqli_query($con, $sql2);

    while ($row = mysqli_fetch_array($ret2)) {
        $TBL = $row['Tables_in_dbmate'];

        $sql3 = "DELETE FROM $TBL WHERE userID = '$userID'";
        $result3 = mysqli_query($con,$sql3);

        //return
        //$response = true;
    }

    //echo json_encode($response);
?>