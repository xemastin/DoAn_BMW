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