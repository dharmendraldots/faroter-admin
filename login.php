<?php
session_start();
require_once 'Firestore.php';

// $fs = new Firestore(collection:'Wallet');
$fuser = new Firestore(collection:'User');

// print_r($_REQUEST); die;

$sucResponce = "false";
$sucMsg = "Invaid action";
// $country_code = "+1";
// $mobile = "4692228854";
// $password = "12345678";
// $userData = $fuser->getAllDocumentUser($country_code,$mobile,$password);
		// echo "<pre>"; print_r($userData);

if(isset($_POST['action'])){
	$action = $_POST['action'];
	if($action == "userlogin"){
		if($_REQUEST['country_code'] > 0){
			$country_code = "+".$_REQUEST['country_code'];
		}
		else{
			$country_code = $_REQUEST['country_code'];
		}
		
		$mobile = $_REQUEST['mobile'];
		$password = $_REQUEST['password'];
		$password = str_replace("%26","&",$password);
		$documents = $fuser->getAllDocumentUser($country_code,$mobile,$password);
		foreach ($documents as $document) {
			$userData = $document->data();
			// print($document->id());
			// echo "<pre>"; print_r($document->data());
			// die;
			if(!empty($userData)){
				if($userData['is_admin']){
					$_SESSION["email"] = $userData['mobile'];
					$_SESSION["name"] = $userData['name'];
					$_SESSION["user_id"] = $userData['user_id'];
					$_SESSION["document_id"] = $document->id();
					
					$sucResponce = "true";
					$sucMsg = "Login Successfully";
				}
				else{
					$sucMsg = "Unauthorised Access";
				}
			}
			else{
				$sucMsg = "Invalid username and password";
			}
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