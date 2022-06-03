<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pemrograman_web_2";
    $connection = mysqli_connect($host, $username, $password, $dbname);
    
    if(!$connection){
        die('<script>alert("Gagal tersambung dengan database.");</script>');
    }
?>