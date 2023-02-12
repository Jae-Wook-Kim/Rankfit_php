<?php
    require("dbset.php");
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("MySQL Connection Failed !!");
    mysqli_query($con,'SET NAMES utf8');

    $name = $_POST["name"];
    $data = file_get_contents($name);
    // $floder_path = "images/" . time() . ".png";
    // $data = str_replace('data:image/png;base64,', '', $picture);
    // $data = str_replace(' ', '+', $data);
    // $data = base64_decode($data);
    // file_put_contents($floder_path, $data);
    $sql = "INSERT INTO images (name, data) VALUES ('$name', '$data')";
    mysqli_query($con, $sql);
    echo "Image has been uploaded"
?>