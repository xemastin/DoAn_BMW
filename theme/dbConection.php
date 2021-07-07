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

function csrf_token() {
    return bin2hex(random_bytes(35));
}

function create_csrf_token() {
    $token = csrf_token();
    $_SESSION['csrf_token'] = $token;
    return $token;
}

function csrf_token_tag() {
    $token = create_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}