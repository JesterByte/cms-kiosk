<?php
$hostName = "localhost";
$serverUsername = "root";
$serverPassword = "";
$databaseName = "cms_db";

$conn = mysqli_connect($hostName, $serverUsername, $serverPassword, $databaseName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}