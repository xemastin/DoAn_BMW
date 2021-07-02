<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "doan_bmw";
$conn = mysqli_connect($servername, $username, $password, $dbname);
$user_name = "";

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function delete_files($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK );
        foreach( $files as $file ){
            delete_files( $file );      
        }

        rmdir( $target );
    } elseif(is_file($target)) {
        unlink( $target );  
    }
}