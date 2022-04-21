<?php
session_start();
if(!isset($_SESSION["email"])){
	
	header("Location: ./");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="icon" href="img/favicon.png">

    <title>Faroter | Update Password</title>
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
        <form class="form-signin" accept="#">
            <h1 class="h3 mb-3 font-weight-normal" style="text-align: center"> Update Password</h1>
			
            <input type="password" name="password" id="password" class="form-control" placeholder="Current password" required="" autofocus="">
			
            <input type="password" name="newpassword" id="newpassword" class="form-control" placeholder="New Password" required="">
			
            <input type="password" name="confirm_new_password" id="confirm_new_password" class="form-control" placeholder="Confirm New Password" required="">

            <button class="btn btn-success btn-block" id="loginbtn" type="button" onClick="update_password();"><i class="fas fa-sign-in-alt"></i>
                Update</button>
				
            <button class="btn btn-default btn-block" id="loginbtn" type="button" onclick="javascript:history.back()"><i class="fas fa-angle-left"></i>
                Back</button>
			<br />
			<div class="alert alert-success hidden" id="success-alert">
			  <button type="button" class="close" data-dismiss="alert">x</button>
			  <strong>Success! </strong> Updated Successfully
			</div>
			<div class="alert alert-success hidden" id="redirecting-alert">
			  <strong>Redirecting... </strong>
			</div>
			<div class="alert alert-danger hidden" id="danger-alert">
			  <button type="button" class="close" data-dismiss="alert">x</button>
			  <strong>Invalid Request! </strong> Current password is wrong
			</div>
			<div class="alert alert-danger hidden" id="missing-alert">
			  <button type="button" class="close" data-dismiss="alert">x</button>
			  <strong>Missing Parameter! </strong> Please fill all fields
			</div>
			<div class="alert alert-danger hidden" id="password-alert">
			  <button type="button" class="close" data-dismiss="alert">x</button>
			  <strong>Should be same! </strong> New Password and Confirm Password
			</div>
        </form>
    </div>
	
	
    <script src="js/jquery.min.js"></script>
    <script type="text/javascript">
    function update_password() {
      var action = "update_password";
      var password = $("#password").val();
      var newpassword = $("#newpassword").val();
      var confirm_new_password = $("#confirm_new_password").val();
	  
      if(password.length > 0 && newpassword.length > 0 && confirm_new_password.length > 0){
		if(newpassword == confirm_new_password){
			// password = password.replace("&", "%26");
			password = password.replace(/\&/g, '%26');			
			// newpassword = newpassword.replace("&", "%26");	
			newpassword = newpassword.replace(/\&/g, '%26');			
			var dataString = 'action='+ action + "&password=" + password + "&newpassword=" + newpassword;
			// alert(dataString);
			// return false;
			$.ajax({
			  type: "POST",
			  dataType: "json",
			  url: "update_password_action.php",
			  data : dataString,
			  success:function(result){
				if(result.success == "true"){
					$("#success-alert").removeClass("hidden");
					$("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
						$("#success-alert").slideUp(500);
					});
					$("#redirecting-alert").removeClass("hidden");
					window.location = "list.php";
				}
				else{
					$("#danger-alert").removeClass("hidden");
					$("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
						$("#danger-alert").slideUp(500);
					});
				}
			  }
			});
		}
		else{
			$("#password-alert").removeClass("hidden");
			$("#password-alert").fadeTo(2000, 500).slideUp(500, function(){
				$("#password-alert").slideUp(500);
			});
		}
      }
      else{
		$("#missing-alert").removeClass("hidden");
		$("#missing-alert").fadeTo(2000, 500).slideUp(500, function(){
			$("#missing-alert").slideUp(500);
		});
      }
    }
    </script>
	
</body>
</html>