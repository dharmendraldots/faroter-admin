<?php
session_start();
echo "<h1>Welcome ";
echo $_SESSION["email"];
echo "Login Successfull </h1>";

if(isset($_SESSION["email"])){
	header("Location: list.php");
}
