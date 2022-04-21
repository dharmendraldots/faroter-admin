<?php
require_once 'Firestore.php';

$fs = new Firestore(collection:'Wallet');
$fuser = new Firestore(collection:'User');

$message = "Invalid Request";

if(isset($_POST['user_detail'])){
	$message = "true";
	$document_id = $_POST['document_id'];
	$receiver_id = $_POST['receiver_id'];
	$sender_id = $_POST['sender_id'];
	$amount = $_POST['amount'];
	$transaction_id = $_POST['transaction_id'];
		
	$userData = $fuser->getDocument(name: $receiver_id);
	// print_r($userData);
}
else{
	$message = "Invalid Request";
}

?>
<!DOCTYPE html>
<!-- saved from url=(0053)https://getbootstrap.com/docs/4.0/examples/dashboard/ -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="img/favicon.png">

	<title>Faroter: Firebase</title>

	<link rel="stylesheet" href="css/bootstrap.css">
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">
	<!------ Include the above in your HEAD tag ---------->
</head>

<body>
	<div class="container">
		<nav class="navbar navbar-light bg-light">
		  <div class="container-fluid">
			<div class="row">
				<div class="col-sm-6">
					<a href="#" class="navbar-brand">
						<img src="img/faroter.png" width="100px" />
					</a>
				</div>
				<div class="col-sm-6 text-right">
					<a href="update_password.php">
						<button class="btn btn-primary btn-sm" style="margin-top: 45px;"><span class="glyphicon glyphicon-cog"></span> Manage Password</button>
					</a>
					
					<a href="logout.php">
						<button class="btn btn-primary btn-sm" style="margin-top: 45px;"><span class="glyphicon glyphicon-log-out"></span> Logout</button>
					</a>
				</div>
			</div>
		  </div>
		</nav>
		<br />
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
			<a href="list.php">Transactions</a> / Receiver's Detail
			</div>
		</div>
		<br />
		<div class="row">
			<?php if($message == "true"){ ?>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="well well-sm">
					<div class="row">
						<div class="col-sm-3 col-md-3">
							<img src="<?php if(isset($userData['image'])){
								echo $userData['image'];
							};?>" alt="" class="img-rounded img-responsive" width="200px">
						</div>
						<div class="col-sm-8 col-md-8">
							<h5><b>Transaction ID:</b> <?php if(isset($transaction_id)){
								echo $transaction_id;
							};?></h5>
							<br />
							<h4><?php if(isset($userData['name'])){
								echo $userData['name'];
							};?>(<?php if(isset($userData['username'])){
								echo $userData['username'];
							};?>)</h4>
							<small><cite title="address"><i class="glyphicon glyphicon-map-marker">
							</i> <?php if(isset($userData['address'])){
								echo $userData['address'];
							};?> <?php if(isset($userData['city'])){
								echo $userData['city'];
							};?>, <?php if(isset($userData['country'])){
								echo $userData['country'];
							}
							else{ echo "--"; };?></cite></small>
							<p>
								<i class="glyphicon glyphicon-phone"></i> 
								<?php if(isset($userData['country_code'])){
								echo $userData['country_code'];
							}
							else{ echo "--"; };?><?php if(isset($userData['mobile'])){
								echo $userData['mobile'];
							}
							else{ echo "--"; };?>
								<br>
								<i class="glyphicon glyphicon-envelope"></i> <?php if(isset($userData['email'])){
								echo $userData['email'];
							}
							else{ echo "--"; };?>
							</p>
							<br />
							<h4>Bank Detail</h4>
							<p>
								<b>Bank Name:</b> <?php if(isset($userData['bank_name'])){
								echo $userData['bank_name'];
							}
							else{ echo "--"; };?>
								<br>
								<b>Routing Number:</b> <?php if(isset($userData['routing_number'])){
								echo $userData['routing_number'];
							}
							else{ echo "--"; };?>
								<br>
								<b>Account Number:</b> <?php if(isset($userData['account_number'])){
								echo $userData['account_number'];
							}
							else{ echo "--"; };?>
								</p>
								
							<br />
							<h4>Mobile Money</h4>								
								<p>
								<?php if(isset($userData['mobile_money'])){
								echo $userData['mobile_money']['country_code'].$userData['mobile_money']['mobile_number'];
							}
							else{ echo "--"; };?>
								</p>							
								<form action="update_payment.php" method="post" onsubmit="return confirm('Do you really want to update?');">
								<input type="hidden" name="document_id" value="<?=$_POST['document_id'];?>" />
								<input type="hidden" name="receiver_id" value="<?=$_POST['receiver_id'];?>" />
								<input type="hidden" name="sender_id" value="<?=$_POST['sender_id'];?>" />
								<input type="hidden" name="amount" value="<?=$_POST['amount'];?>" />
								<input type="submit" class="btn btn-primary" name="update_payment" value="Update Payment" />
								</form>
						</div>
					</div>
				</div>
			</div>
			<?php }
			else{
				echo $message;
			}
			?>
		</div>
	</div>
</body>
</html>