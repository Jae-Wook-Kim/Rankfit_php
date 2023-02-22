<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $sql = "SELECT * FROM running";
    $result = mysqli_query($con,$sql);
    $tmp = mysqli_num_rows($result)
    
    echo $tmp;
    mysqli_close($con)
?>