<?php
session_start();
if(!isset($_SESSION["email"])){
	
	// header("Location: ./");
}

// print_r($_REQUEST); die;

require_once 'Firestore.php';
$fuser = new Firestore(collection:'User');

$sucResponce = "false";
$sucMsg = '';
if(isset($_REQUEST['action'])){
	$action = $_REQUEST['action'];
	if($action == "reset_password"){
		
		if(isset($_REQUEST['email']) && $_REQUEST['email'] != "" && $_REQUEST['newpassword'] != "" && $_REQUEST['confirm_new_password'] != ""&& $_REQUEST['reset_token'] != ""){
			$email = $_REQUEST['email'];
			$reset_token = $_REQUEST['reset_token'];
			$newpassword = $_REQUEST['newpassword'];
			$confirm_new_password = $_REQUEST['confirm_new_password'];
			
			if($newpassword == $confirm_new_password){
				// echo $email = "dharmendra@yopmail.com111";
				// die;
				$response = $fuser->resetPassword($email, $reset_token, $newpassword);
				
				if($response){
					$sucResponce = "true";
					// $sucMsg = "We have emailed your password reset link!";
					$sucMsg = '
					<div class="alert alert-success" id="success-alert">
					  <button type="button" class="close" data-dismiss="alert">x</button>
					  <strong>Success! </strong> password updated.
					  <a href="./" title="Click to login">Click to login</a>
					</div>';
				}
				else{
					$sucMsg = '
					<div class="alert alert-danger" id="danger-alert">
					  <button type="button" class="close" data-dismiss="alert">x</button>
					  <strong>Invalid Request! </strong> No Such User Found
					</div>';
					// $sucMsg = "We can't find a user with that email address.";
				}
			}
			else{
				$sucMsg = '
				<div class="alert alert-danger" id="danger-alert">
				  <button type="button" class="close" data-dismiss="alert">x</button>
				  <strong>Should be same! </strong> New Password and Confirm Password
				</div>';
				// $sucMsg = "We can't find a user with that email address.";
			}
		}
		else{			
			$sucMsg = '
				<div class="alert alert-danger" id="missing-alert">
				  <button type="button" class="close" data-dismiss="alert">x</button>
				  <strong>Missing Parameter! </strong> Please fill all fields
				</div>';
		}
	}	
}
if((!isset($_REQUEST['email']) && @$_REQUEST['email'] == "") || (!isset($_REQUEST['reset_token']) && @$_REQUEST['reset_token'] == "")){
	$sucMsg = '
	<div class="alert alert-danger" id="danger-alert">
	  <button type="button" class="close" data-dismiss="alert">x</button>
	  <strong>Invalid Request! </strong> No Such Request found
	</div>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="icon" href="img/favicon.png">

    <title>Faroter | Forget Password</title>
    <link rel="stylesheet" href="css/bootstrap.css">
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
        integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

    <style>
        /* sign in FORM */
        #logreg-forms {
            width: 412px;
            margin: 5vh auto;
            background-color: #f3f3f3;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
        }

        #logreg-forms form {
            width: 100%;
            max-width: 410px;
            padding: 15px;
            margin: auto;
        }

        #logreg-forms .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }

        #logreg-forms .form-control:focus {
            z-index: 2;
        }

        #logreg-forms .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        #logreg-forms .form-signin input[type="password"] {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        #logreg-forms .social-login {
            width: 390px;
            margin: 0 auto;
            margin-bottom: 14px;
        }

        #logreg-forms .social-btn {
            font-weight: 100;
            color: white;
            width: 190px;
            font-size: 0.9rem;
        }

        #logreg-forms a {
            display: block;
            padding-top: 10px;
            color: lightseagreen;
        }

        #logreg-form .lines {
            width: 200px;
            border: 1px solid red;
        }


        #logreg-forms button[type="submit"] {
            margin-top: 10px;
        }

        #logreg-forms .facebook-btn {
            background-color: #3C589C;
        }

        #logreg-forms .google-btn {
            background-color: #DF4B3B;
        }

        #logreg-forms .form-reset,
        #logreg-forms .form-signup {
            display: none;
        }

        #logreg-forms .form-signup .social-btn {
            width: 210px;
        }

        #logreg-forms .form-signup input {
            margin-bottom: 2px;
        }

        .form-signup .social-login {
            width: 210px !important;
            margin: 0 auto;
        }

        /* Mobile */
		.hidden{
			display: none;
		}
		.form-control, .btn{
			margin-top: 10px;
		}
		#recaptcha-container{
			text-align: center;
			margin-top: 10px;
		}
    </style>
</head>

<body>
    <div id="logreg-forms">
		<div class="text-center"><img src="img/faroter.png" width="100px" /></div>
        <form class="form-signin" accept="#" method="post">
            <h1 class="h3 mb-3 font-weight-normal" style="text-align: center"> Reset Password</h1>			
			
            <input type="hidden" name="reset_token" id="reset_token" class="form-control" placeholder="reset_token" required="" value="<?php echo @$_REQUEST['reset_token'];?>">
			
            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required="" value="<?php echo @$_REQUEST['email'];?>" readonly>
			
            <input type="password" name="newpassword" id="newpassword" class="form-control" placeholder="New Password" required="">
			
            <input type="password" name="confirm_new_password" id="confirm_new_password" class="form-control" placeholder="Confirm New Password" required="">

            <button class="btn btn-success btn-block" type="submit" name="action" value="reset_password"><i class="fas fa-sign-in-alt"></i>
                Reset</button>
				
            <button class="btn btn-default btn-block" id="loginbtn" type="button" onclick="window.location='./'"><i class="fas fa-angle-left"></i>
                Back</button>
			<br />
			<?=$sucMsg;?>
        </form>
    </div>	
	
    <script src="js/jquery.min.js"></script>
	
</body>
</html>