<?php 
	require 'dbconnect.php';
	require 'constants.php';
	
	if($user->is_loggedin()) {
		 header("Location: ".BASE_URL);
		 die();		
	}
	
	$errmessage = '';
	$errlmessage = '';

	if( isset($_POST['action']) && !empty($_POST['action'])) {
		switch($_POST['action']) {
			case 'login':
				if(isset($_POST['user']) && !empty($_POST['user']) && isset($_POST['pass']) && !empty($_POST['pass'])){
					$username = $_POST['user'];
					$pass = $_POST['pass'];
					
					try {
					  $stmt = $conn->prepare("SELECT u.UserID
												   , u.UserEmail
												   , u.UserPhone
												   , u.UserAddress1
												   , u.UserPassword
												   , u.UserFirstName
												   , u.UserLastName
												   , u.type
												   , sessions.sessionData
												   , sessions.HashID
												   FROM users u
												   INNER JOIN sessions
												   ON sessions.UserID = u.UserID
												   WHERE u.UserUsername=:username 
												   LIMIT 1");
												   
					  $stmt->execute(array(':username'=>$username));
					  $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
					  if($stmt->rowCount() > 0)
					  {
						
						 if(password_verify($pass, $userRow['UserPassword']))
						 {
							 
							$anonymousCart = false;
							
							if(isset($_SESSION['cart'])) {
								$anonymousCart = $_SESSION['cart'];
							}

							session_destroy();
							session_id($userRow['HashID']);
							session_start();
							
							$_SESSION['user_session'] = $userRow['UserID'];
							$_SESSION['email'] = $userRow['UserEmail'];
							$_SESSION['userPhone'] = $userRow['UserPhone'];
							$_SESSION['address'] = $userRow['UserAddress1'];
							$_SESSION['firstName'] = $userRow['UserFirstName'];
							$_SESSION['lastName'] = $userRow['UserLastName'];
							$_SESSION['type'] = $userRow['type'];
							
							$sessionData = unserialize($userRow['sessionData']);
							
							$_SESSION['cart'] = is_array($sessionData) ? $sessionData : array();
							
							if($anonymousCart !== false) {
								foreach($anonymousCart as $key => $value) {
									if(isset($_SESSION['cart'][$key])) {
										$_SESSION['cart'][$key]['quantity'] += $value['quantity'];
									} else {
										$_SESSION['cart'][$key] = $value; 
									}
								}
							}
							
							// echo '<pre>';
							// print_r($_SESSION);
							// echo '</pre>';

							
							 $errlmessage = 'Login successful.';
							 header("Location: ".BASE_URL);
							 die();
						 } else {
							 $errlmessage = 'Username or password does not match.';
						 }	
					  } else {
						$errlmessage = 'Username or password does not match.';
					  }
				   }
				   catch(PDOException $e)
				   {
						$errlmessage = 'An error has occurred';
					   // echo '<pre>';
					   // echo print_r($e);
					   // echo '</pre>';
				   } finally {
							$stmt=null;
					}
				}
				break;
			case 'register':
				if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['rpassword']) && !empty($_POST['rpassword']) && isset($_POST['fname']) && !empty($_POST['fname']) && isset($_POST['lname']) && !empty($_POST['lname'])){
					
					$username = $_POST['username'];
					$email = $_POST['email'];
					$pass = $_POST['password'];
					$rpass = $_POST['rpassword'];
					$fName = $_POST['fname'];
					$lName = $_POST['lname'];
					$authCode = password_hash(time(), PASSWORD_BCRYPT);
					$ip = $_SERVER['REMOTE_ADDR'];
					
					//compares if passwords are the same
					if($pass === $rpass) {
						// $query = 'select UserEmail from users where UserEmail="'.$email.'"';
							
						try {
							$conn->beginTransaction();
							
							$stmt = $conn->prepare("INSERT INTO users (UserUsername
																	 , UserEmail
																	 , UserPassword
																	 , UserFirstName
																	 , UserLastName
																	 , UserVerificationCode
																	 , UserIP) VALUES (:username
																					 , :email
																					 , :pass
																					 , :fName
																					 , :lName
																					 , :authCode
																					 , :ip)");
							$stmt->bindParam(':username', $username);
							$stmt->bindParam(':email', $email);
							$stmt->bindParam(':pass', password_hash($pass, PASSWORD_BCRYPT));
							$stmt->bindParam(':fName', $fName);
							$stmt->bindParam(':lName', $lName);
							$stmt->bindParam(':authCode', $authCode);
							$stmt->bindParam(':ip', $ip);
							
							$stmt2 = $conn->prepare("INSERT INTO sessions (`HashID`,`UserID`)
																					SELECT :hash, UserID 
																					FROM users
																					WHERE UserUsername=:UserUsername
																					LIMIT 1	");
							$hash = session_id();
							$stmt2->bindParam(':hash',$hash);
							$stmt2->bindParam(':UserUsername',$username);
							
							
							if ($stmt->execute()
							 && $stmt2->execute()
							 && $conn->commit()) {
								$to      = $email; // Send email to our user
								$subject = 'Signup | Verification'; // Give the email a subject 
								$message = '
								 
								Thanks for signing up!
								Your account has been created, you can login with the credentials you gave after you have activated your account by pressing the url below.
								 
								 
								Please click this link to activate your account:
								'.BASE_URL.'/verify.php?email='.$email.'&hash='.$authCode.'
								 
								'; // Our message above including the link
													 
								$headers = 'From:'.BASE_URL . "\r\n"; // Set from headers
								mail($to, $subject, $message, $headers); // Send our email
								
								$errmessage = 'A verification email was sent to '.$email;
								
							} else {
								session_regenerate_id();
								$conn->rollBack();
								$errmessage = "Error: " . $query . "<br>" . mysqli_error($conn);
							}
						} catch(PDOException $e) {
							
							if($e->errorInfo[1] == 1062) {
								$errmessage = 'Email already exists.';
							} else {
								$errmessage = 'An error has occurred. Please try again.';								
							}
							session_regenerate_id();
							$conn->rollBack();
						} finally {
							$stmt=null;
							$stmt2=null;
						}
					} else {
						$errmesage = 'Passwords do not match';
					}
				}
				break;
		}
	}
	
	require 'inc.header.php'; 
	require 'inc.menu.php';
?>

 </div>
 <div class="main">
    <div class="content">
		
    	 <div class="login_panel">
        	<h3>Existing Customers</h3>
        	<p>Sign in with the form below.</p>
        	<form action="<?php echo BASE_URL;?>/login.php" name="login" onsubmit="return validate.loginForm()" method="post" id="member">
                	<input id="luser" name="user" type="text" value="Username" class="field" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Username';}">
                    <input id="lpass" name="pass" type="password" value="Password" class="field" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Password';}">
					<input name="action" type="hidden" value="login">
					<p class="note">If you forgot your passoword just enter your email and click <a href="#">here</a></p>
                    <div class="buttons"><div><button type="submit" class="grey">Sign In</button></div></div>
					<div><br><pre id="errMessage"><?php echo $errlmessage; ?></pre></div>
			</form>
	
					
		</div>
    	<div class="register_account">
    		<h3>Register New Account</h3>
				<form action="<?php echo BASE_URL;?>/login.php" name="register" onsubmit="return validate.registerForm()" method="post">
		   			 <table>
		   				<tbody>
						<tr>
							<td>
								<div><input id="username" name="username" type="text" placeholder="Username" onfocus="if (this.value == 'Username') this.value = '';" onblur="if (this.value == '') {this.value = 'Username';}"></div>
								<div><input id="password" name="password" type="password" placeholder="Password" onfocus="if (this.value == 'Password') this.value = '';" onblur="if (this.value == '') {this.value = 'Password';}"></div>
								<div><input id="rpassword" name="rpassword" type="password" placeholder="Repeat Password" onfocus="if (this.value == 'Password') this.value = '';" onblur="if (this.value == '') {this.value = 'Password';}"></div>
							</td>
							<td>
								<div><input id="email" name="email" type="text" value="E-Mail" onfocus="if (this.value == 'E-Mail') this.value = '';" onblur="if (this.value == '') {this.value = 'E-Mail';}"></div>
								<div><input id="fName" name="fname" type="text" value="First Name" onfocus="if (this.value == 'First Name') this.value = '';" onblur="if (this.value == '') {this.value = 'First Name';}" ></div>
								<div><input id="lName" name="lname" type="text" value="Last Name" onfocus="if (this.value == 'Last Name') this.value = '';" onblur="if (this.value == '') {this.value = 'Last Name';}" ></div>
								<div><br><pre id="errRMessage"><?php echo $errmessage; ?></pre></div>
								<div><input name="action" type="hidden" value="register"></div>
							</td>
						</tr> 
		    </tbody></table> 
		    <div class="search"><div><button type="submit" class="grey">Create Account</button></div></div>
		    <p class="terms">By clicking 'Create Account' you agree to the <a href="#">Terms &amp; Conditions</a>.</p>
		    <div class="clear"></div>
		    </form>
    	</div>  	
       <div class="clear"></div>
    </div>
 </div>
</div>

<?php include 'inc.footer.php'; ?>
