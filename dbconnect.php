<?php


$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "ecom";

$conn = null;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; 
    }
catch(PDOException $e)
    {
    // echo "Connection failed: " . $e->getMessage();
	die();
}

include_once 'class.user.php';
include_once 'class.session.php';


$user = new USER();
// $handler = new MySessionHandler();
// session_set_save_handler($handler, true);

session_start();

?>