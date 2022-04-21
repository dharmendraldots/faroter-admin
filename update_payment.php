<?php
require_once 'Firestore.php';

$fs = new Firestore(collection:'Wallet');
$fuser = new Firestore(collection:'User');

if(isset($_POST['update_payment'])){
	$document_id = $_POST['document_id'];
	$receiver_id = $_POST['receiver_id'];
	$sender_id = $_POST['sender_id'];
	$amount = $_POST['amount'];
	if($fs->updateDocument($document_id)){
		echo "Document Updated";
		
		$userData = $fuser->getDocument(name: $receiver_id);
		// print_r($userData);
		$device_id = $userData['fcm_token'];
		
		$message = "CONGRATULATIONS";
		
		$callpush = push_notification_android($device_id,$message,$sender_id,$amount);
		// print_r($callpush);
		
		header("Location: list.php");
	}else{
		echo "Not Updated";
	}
}
else{
	echo "Invalid Request";
}


function push_notification_android($device_id,$message,$sender_id,$amount){

    //API URL of FCM
    $url = $_ENV['GOOGLE_FCM_HOST'];

    /*api_key available in:
    Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
    $api_key = $_ENV['GOOGLE_FCM_KEY'];
                
    $fields = array (
        'registration_ids' => array (
                $device_id
        ),
        'data' => array (
                "title" => $message,
                "body" => "You received $".$amount." from Faroter",
                "type" => "payment_release",
                "sender_id" => $sender_id,
                "amount" => $amount
        ),
        'notification' => array (
            "title" => $message,
            "body" => "You received $".$amount." from Faroter",
            "type" => "payment_release",
            "sender_id" => $sender_id,
            "amount" => $amount                
         )
    );   
 
    //header includes Content type and api key
    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$api_key
    );
                
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
}
?>