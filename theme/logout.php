<?php
session_start();
unset($_SESSION["name"]);
unset($_SESSION["position"]);
header("Location: http://".$_SERVER['HTTP_HOST']."/index.php");
die();
?>