<?php
session_start();

$sucResponce = "false";
$sucMsg = "Invaid action";

if(!isset($_SESSION["email"])){
	$dataArray = array(
		"success" => $sucResponce,
		"sucMsg" => $sucMsg
	);
	$dataJSON = json_encode($dataArray);
	echo ($dataJSON);
	die;
}
require_once 'Firestore.php';

// $fs = new Firestore(collection:'Wallet');
$fuser = new Firestore(collection:'User');

// print_r($_REQUEST); die;
$document_id = $_SESSION["document_id"];

// $password = "12345678";
// $newpassword = "123456789";
// echo $fuser->updatePassword($document_id, $password, $newpassword);
// die;

if(isset($_POST['action'])){
	$action = $_POST['action'];
	if($action == "update_password"){
		$password = $_REQUEST['password'];
		$newpassword = $_REQUEST['newpassword'];
		$password = str_replace("%26","&",$password);
		$newpassword = str_replace("%26","&",$newpassword);
		$documents = $fuser->updatePassword($document_id, $password, $newpassword);
		if($documents){
			$sucResponce = "true";
			$sucMsg = "Password Updated Successfully";
			// header("Location: list.php");
		}
		else{
			$sucMsg = "Unauthorised Access";
		}
	}
}
$dataArray = array(
		"success" => $sucResponce,
		"sucMsg" => $sucMsg
	);
$dataJSON = json_encode($dataArray);
echo ($dataJSON);

die;