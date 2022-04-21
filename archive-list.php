<?php
session_start();
if(!isset($_SESSION["email"])){
	
	header("Location: ./");
}
require_once 'Firestore.php';

$fs = new Firestore(collection:'Wallet');
$fuser = new Firestore(collection:'User');

$todayDate = date('Y-m-d');
$fromDate= "";
$toDate= "";
$sender_name = "";
$receiver_name = "";
if(!isset($_REQUEST['clear_filter'])){
	if(isset($_REQUEST['fromdate']) && isset($_REQUEST['todate'])){
		$fromDate = $_REQUEST['fromdate'];
		$toDate = $_REQUEST['todate'];
	}
	// else{
		// $fromDate= date('Y-m-d', strtotime($todayDate. ' -7 days'));
		// $toDate= $todayDate;
	// }

	if(isset($_REQUEST['sender_name'])){
		$sender_name = $_REQUEST['sender_name'];
	}
	if(isset($_REQUEST['receiver_name'])){
		$receiver_name = $_REQUEST['receiver_name'];
	}
}

$documents = [];
$documents = $fs->getAllDocument($fromDate,$toDate,$sender_name);
?>
<!DOCTYPE html>
<!-- saved from url=(0053)https://getbootstrap.com/docs/4.0/examples/dashboard/ -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="img/favicon.png">

	<title>Faroter | Firebase</title>

	<link rel="stylesheet" href="css/bootstrap.css">
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
		<div class="row">
			<div class="panel panel-primary filterable">
				<div class="panel-heading">
					<div class="pull-right">
						<a href="list.php" class="btn btn-default btn-xs exportCsv"><span class="glyphicon glyphicon-list"></span> Transactions</a>
						<button class="btn btn-default btn-xs exportCsv"><span class="glyphicon glyphicon-download"></span> Export to CSV</button>
					</div>
					<!-- <div class="pull-right">
						<button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filter</button>
					</div> -->
					<h3 class="panel-title">Archive</h3>
				</div>
				<div class="table-responsive" style="padding: 15px;">
					<table class="dataTable no-print">
					<tbody>
						<tr>
							<form action="" method="get">
							<th>
							  <input type="hidden" class="form-control" name="clear_filter" id="clear_filter" value="true" required="">
							  <br />
							  <input class="form-control btn btn-primary" type="submit" value="Clear Filter">
							</th>
							</form>
							<form action="" method="get">
							<th>
							  From:
							  <input type="date" class="form-control" name="fromdate" id="fromdate" value="<?= $fromDate; ?>" required="">
							</th>
							<th>
							  To: 
							  <input type="date" class="form-control" name="todate" id="todate" min="" value="<?= $toDate; ?>" required="">
							</th>
							<th>
								<br />
							  <input class="form-control btn btn-primary" type="submit" value="Filter Date">
							</th>
							</form>
							<form action="" method="get">
							<th>
							  Sender: 
							  <input type="text" class="form-control" name="sender_name" value="<?= $sender_name; ?>" required="">
							</th>
							<!-- <th>
							  Receiver: 
							  <input type="text" class="form-control" name="receiver_name" value="<?= $receiver_name; ?>">
							</th> -->
							<th>
								<br />
							  <input class="form-control btn btn-primary" type="submit" value="Filter Sender">
							</th>
							</form>
						</tr>
					</tbody>
					</table>
					<br />
					
					
					<table id="exampleTable" class="table table-striped table-sm">
						<thead>
							<tr class="filters">
								<th>#</th>
								<th>Sender</th>
								<th>Receiver</th>
								<th>Amount Sent ($)</th>
								<th>Stripe Charge ($)</th>
								<th>Processing Fee ($)</th>
								<th>Receiver Amount ($)</th>
								<th>Payment Status</th>
								<th>Transaction Date</th>
								<th style="display: none;">Bank Name</th>
								<th style="display: none;">Routing Number</th>
								<th style="display: none;">Account Number</th>
								<th style="display: none;">Mobile Money</th>
								<th>Receiver Details</th>
								<!--
								<th>Transaction Id</th>
								<th><input type="text" class="form-control" placeholder="id" disabled></th>
								<th><input type="text" class="form-control" placeholder="note_type" disabled></th>
								<th><input type="text" class="form-control" placeholder="timestamp" disabled></th>
								-->
							</tr>
						</thead>
						<tbody>
						<?php
						$sno = 0;
						foreach ($documents as $document) {
							if ($document->exists()) {
								if(isset($document->data()['payment_status']) &&  $document->data()['payment_status'] == 1){
									$sno++;
									//$showallData[] = $document->data();
									// print_r($document->data());
									// echo "<br>";
									// print($document->id());
									$payment_status = "--";
									if(isset($document->data()['payment_status'])){
										$payment_status = $document->data()['payment_status'];
										
										if($payment_status == 1){
											$payment_status = '<span class="label label-success">Completed</span>';
										}
										else{
											$payment_status = '<span class="label label-danger">Pending</span>';
										}
									}
									
									$stripe_charge = "--";
									$platformCharge = 0;
									$platformCharge = ($document->data()['amount']*0.02);
									$platformCharge = number_format((float)$platformCharge, 2, '.', '');
									$payable_amount = $document->data()['amount'];
									if(isset($document->data()['stripe_charge'])){
										$stripe_charge = $document->data()['stripe_charge'];
										
										$payable_amount = $document->data()['amount'] - $stripe_charge;
										$payable_amount = $payable_amount - $platformCharge;
										$payable_amount = number_format((float)$payable_amount, 2, '.', '');
									}
									$receiver_id = $document->data()['receiver_id'];
									$userData = [];
									$userData = $fuser->getDocument(name: $receiver_id);
									
									$created_at= date('m-d-Y h:ia', strtotime($document->data()['created_at']));
									?>
									<tr>
										<td><?=$sno;?></td>
										<td><?=$document->data()['sender_name'];?></td>
										<td><?=$document->data()['receiver_name'];?></td>
										<td><?=$document->data()['amount'];?></td>
										<td><?=$stripe_charge;?></td>
										<td><?=$platformCharge;?></td>
										<td><?=$payable_amount;?></td>
										<td><?=$payment_status;?></td>
										<td><?=$created_at;?></td>
										<td style="display: none;"><?php if(isset($userData['bank_name'])){echo $userData['bank_name'];}else{ echo "--"; };?></td>
										<td style="display: none;"><?php if(isset($userData['routing_number'])){echo $userData['routing_number']; } else{ echo "--"; };?></td>
										<td style="display: none;"><?php if(isset($userData['account_number'])){echo $userData['account_number']; } else{ echo "--"; };?></td>
										<td style="display: none;"><?php if(isset($userData['mobile_money'])){echo $userData['mobile_money']['country_code'].$userData['mobile_money']['mobile_number'];}else{ echo "--"; };?></td>
										<td>
										<form action="detail.php" method="post">
										<input type="hidden" name="document_id" value="<?=$document->id();?>" />
										<input type="hidden" name="receiver_id" value="<?=$receiver_id;?>" />
										<input type="hidden" name="sender_id" value="<?=$document->data()['sender_id'];?>" />
										<input type="hidden" name="amount" value="<?=$payable_amount;?>" />
										<input type="hidden" name="transaction_id" value="<?=$document->data()['transaction_id'];?>" />
										<?php // if($payment_status != "True"){ ?>
										<input type="submit" class="btn btn-primary" name="user_detail" value="Details" style="background-color: #000;" />
										<?php // } ?>
										</form>
										</td>
										<!--
										<td><?php //echo $document->data()['transaction_id'];?></td>
										<td><?php //echo $document->id();?></td>
										<td><?php //echo $document->data()['note_type'];?></td>
										<td><?php //echo $document->data()['timestamp'];?></td>
										-->
									</tr>
									<?php
								}
							}
						}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<script>	
	function download_csv(csv, filename) {
		var csvFile;
		var downloadLink;

		// CSV FILE
		csvFile = new Blob([csv], {type: "text/csv"});

		// Download link
		downloadLink = document.createElement("a");

		// File name
		downloadLink.download = filename;

		// We have to create a link to the file
		downloadLink.href = window.URL.createObjectURL(csvFile);

		// Make sure that the link is not displayed
		downloadLink.style.display = "none";

		// Add the link to your DOM
		document.body.appendChild(downloadLink);

		// Lanzamos
		downloadLink.click();
	}

	function export_table_to_csv(html, filename) {
		var csv = [];
		var rows = document.querySelectorAll("table tr");
		
		for (var i = 0; i < rows.length; i++) {
			var row = [], cols = rows[i].querySelectorAll("td, th");
			
			for (var j = 0; j < cols.length; j++) 
				row.push(cols[j].innerText);
			
			csv.push(row.join(","));		
		}

		// Download CSV
		download_csv(csv.join("\n"), filename);
	}

	document.querySelector("button.exportCsv").addEventListener("click", function () {
		var html = document.querySelector("table").outerHTML;
		export_table_to_csv(html, "transactions.csv");
	});
	
	
	$(document).ready(function() {
		$('#exampleTable').DataTable();
	} );
	
	
	</script>  
	<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
</body>
</html>