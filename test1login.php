<?php include ( "inc/connect.inc.php" ); ?>

<?php
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
}
else {
	header("location: index.php");
}
$emails = "";
$passs = "";
if (isset($_POST['login'])) {
	if (isset($_POST['email']) && isset($_POST['password'])) {
		$user_login = mysqli_real_escape_string($con, $_POST['email']);
		$user_login = mb_convert_case($user_login, MB_CASE_LOWER, "UTF-8");	
		$password_login = mysqli_real_escape_string($con, $_POST['password']);		
		$num = 0;
		$password_login_md5 = md5($password_login);
		$result = mysqli_query($con, "SELECT * FROM user WHERE (email='$user_login') AND password='$password_login_md5' AND activation='yes'");
		$num = mysqli_num_rows($result);
		$get_user_email = mysqli_fetch_assoc($result);
			$get_user_uname_db = $get_user_email['id'];
		if ($num>0) {
			$_SESSION['user_login'] = $get_user_uname_db;
			setcookie('user_login', $user_login, time() + (365 * 24 * 60 * 60), "/");
			
			if (isset($_REQUEST['ono'])) {
				$ono = mysqli_real_escape_string($con, $_REQUEST['ono']);
				header("location: orderform.php?poid=".$ono."");
			}else {
				header('location: index.php');
			}
			exit();
		}
		else {
			$result1 = mysqli_query($con, "SELECT * FROM user WHERE (email='$user_login') AND password='$password_login_md5' AND activation='no'");
		$num1 = mysqli_num_rows($result1);
		$get_user_email1 = mysqli_fetch_assoc($result1);
			$get_user_uname_db1 = $get_user_email1['id'];
		if ($num1>0) {
			$emails = $user_login;
			$activacc ='';
		}else {
			$emails = $user_login;
			$passs = $password_login;
			$error_message = '<br><br>
				<div class="maincontent_text" style="text-align: center; font-size: 18px;">
				<font face="bookman">Email or Password incorrect.<br>
				</font></div>';
		}
			
		}
	}

}
$acemails = "";
$acccode = "";
if(isset($_POST['activate'])){
	if(isset($_POST['actcode'])){
		$user_login = mysqli_real_escape_string($con, $_POST['acemail']);
		$user_login = mb_convert_case($user_login, MB_CASE_LOWER, "UTF-8");	
		$user_acccode = mysqli_real_escape_string($con, $_POST['actcode']);
		$result2 = mysqli_query($con, "SELECT * FROM user WHERE (email='$user_login') AND confirmCode='$user_acccode'");
		$num3 = mysqli_num_rows($result2);
		echo $user_login;
		if ($num3>0) {
			$get_user_email = mysqli_fetch_assoc($result2);
			$get_user_uname_db = $get_user_email['id'];
			$_SESSION['user_login'] = $get_user_uname_db;
			setcookie('user_login', $user_login, time() + (365 * 24 * 60 * 60), "/");
			mysqli_query($con, "UPDATE user SET confirmCode='0', activation='yes' WHERE email='$user_login'");
			if (isset($_REQUEST['ono'])) {
				$ono = mysqli_real_escape_string($con, $_REQUEST['ono']);
				header("location: orderform.php?poid=".$ono."");
			}else {
				header('location: index.php');
			}
			exit();
		}else {
			$emails = $user_login;
			$error_message = '<br><br>
				<div class="maincontent_text" style="text-align: center; font-size: 18px;">
				<font face="bookman">Code not matched!<br>
				</font></div>';
		}
	}else {
		$error_message = '<br><br>
				<div class="maincontent_text" style="text-align: center; font-size: 18px;">
				<font face="bookman">Activation code not matched!<br>
				</font></div>';
	}

}

?>

<!doctype html>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Sign Up/In to SITENAME</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
  <style>
    body, html {
      height: 100%;
      background-color: #f9f9f9;
      background-size:cover;
      background-image: url('LINK TO MY BACKGROUND IMAGE');
    }

    .login-container {
      position: relative;
      height: 100%;
    }

    .login-box {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      padding: 15px;
      background-color: #fff;
      box-shadow: 0px 5px 5px #ccc;
      border-radius: 5px;
      border-top: 1px solid #e9e9e9;
      border: 1px solid grey;
    }

    .login-header {
      text-align: center;
    }

    .login-header img {
      width: 200px;
    }
    
    .forgot-password {
      text-align: right;
      float: right;
      font-size: 10px
    }

    #error-message {
      display: none;
    }
  </style>
<body>
  <div class="login-container">
    <div class="col-xs-12 col-sm-4 col-sm-offset-4 login-box">
      <div class="login-header">
      	<img src="LINK TO MY LOGO"/>
      </div>
      <div id="error-message" class="alert alert-danger"></div>
      <form onsubmit="return false;" method="post">
        <div class="form-group">
         <label for="name">Email</label>
          <input
            type="email"
            class="form-control"
            id="email"
            placeholder="Enter your email">
        </div>
        <div class="form-group">
          <label for="name">Password</label>
          <input
            type="password"
            class="form-control"
            id="password"
            placeholder="Enter your password">
        </div>
        <button
          type="submit"
          id="btn-login"
          class="btn btn-primary btn-block">
            Sign In
        </button>
        <span class="forgot-password">
          <a href="signin.php" title="Forgot Password" id="link-reset">Forgot Password?</a>
        </span>
        <button
          type="button"
          id="btn-signup"
          class="btn btn-default btn-block">
            No account yet?  Sign Up
        </button>
        <hr>
        <button
          type="button"
          id="btn-google"
          class="btn btn-default btn-danger btn-block">
            Sign In with Google
        </button>
      </form>
    </div>
  </div>
			<div class="">
				<div id="srcheader">
					<!--form id="newsearch" method="get" action="search.php">
					        <input type="text" class="srctextinput" name="keywords" size="21" maxlength="120"  placeholder="Search Here..."><input type="submit" value="search" class="srcbutton" >
					</form-->
				<div class="srcclear"></div>
				</div>
			</div>
		</div>
		<div class="holecontainer" style="float: right; margin-right: 36%; padding-top: 110px;">
			<div class="container">
				<div>
					<div>
						<div class="signupform_content">
							<?php
							 	if (isset($activacc)){
							 		echo '<h2>Activation Form</h2>';
							 	}else {
							 		echo '<h2>  श्री गजानन मसाले</h2>';
					
							 	}
							?>
							<div class="signupform_text"></div>
							<div>
								<form action="" method="POST" class="registration">
									<div class="signup_form">
										<?php
											if (isset($activacc)) {

												echo '
													<div class="signup_error_msg">
														<div class="maincontent_text" style="text-align: center; font-size: 18px;">
													<font face="bookman">Check your email!<br>
													</font></div>
													</div>
													<div>
														<td>
															<input name="acemail" placeholder="Enter Your Email" required="required" class="email signupbox" type="email" size="30" value="'.$emails.'"style="color:white;">
														</td>
													</div>
													<div>
														<td>
															<input name="actcode" placeholder="Activation Code" required="required" class="email signupbox" type="text" size="30" value="'.$acccode.'" style="color: white;">
														</td>
													</div>
													<div>
														<input name="activate" class="uisignupbutton signupbutton" type="submit" value="Active Account">
													</div>
													';
											}else{
												echo '
										<div>
											<td>
												<input name="email" placeholder="Enter Your Email" required="required" class="email signupbox" type="email" size="30" value="'.$emails.'"style="color:whitw;">
											</td>
										</div>
										<div>
											<td>
												<input name="password" id="password-1" required="required"  placeholder="Enter Password" class="password signupbox " type="password" size="30" value="'.$passs.'"style="color:white;">
											</td>
										</div>
										<div>
											<input name="login" class="uisignupbutton signupbutton" type="submit" value="Log In">
										</div>
										';
											}
										  ?>
										<div style="float: right;">
											<a class="forgetpass" href="forgetpass.php">
												<span>forget your password???</span>
											</a>
										</div>
										<div class="signup_error_msg">
											<?php 
												if (isset($error_message)) {echo $error_message;}
												
											?>
										</div>
									</div>
								</form>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		  <style>
    input.signupbox {
      color: white;
    }
  </style>
	</body>
</html>