<?php 
	require '../dbconnect.php';
	require '../constants.php';	
	require '../inc.header.php'; 
	require '../inc.menu.php';
	require '../inc.verifyuser.php';	
	
	$email = '';
	$errmessage = '';
	if(isset($_POST['email']) && !empty($_POST['email'])) {
		$email = $_POST['email'];
		$authCode = password_hash(time(), PASSWORD_BCRYPT);
		// echo $email.' '.$authCode.' '.$_SESSION['user_session'];
		try {
			$stmt = $conn->prepare("UPDATE users
								   SET UserEmail2 = :userEmail,
								   UserVerificationCode=:authCode
								   WHERE UserID=:userID");
			$stmt->execute(array(':userEmail'=>$email, ':authCode'=>$authCode,':userID'=>$_SESSION['user_session']));
			
			if($stmt->rowCount() > 0) {
				$to      = $email; // Send email to our user
				$subject = 'Change Email Verification'; // Give the email a subject 
				$message = '
				 
				You are requesting to change your email.
				
				------------------------
				New Email / Username: '.$email.'
				------------------------
				 
				Please click this link to activate your account:
				<a href="'.BASE_URL.'/customer/verifychangeemail.php?key='.$authCode.'">This is a link</a>
				 
				'; // Our message above including the link
									 
				$headers = 'From:'.BASE_URL . "\r\n"; // Set from headers
				mail($to, $subject, $message, $headers); // Send our email
				
				$errmessage = 'A verification email was sent to your previous email.<br>You will still be using this email until you have verified.';

			
			} else {
				echo '<h3>Error in verification. Please try again.</h3>';
			}
		} catch(PDOException $e) {
			echo '<h3>Error in verification. Please try again.</h3>';
		} finally {
			$stmt = null;
		}
		
	}
	
	try {
		$stmt = $conn->prepare("SELECT UserEmail
								FROM users
								WHERE `UserID` = :user_id
								LIMIT 1");
		
		$stmt->bindParam(':user_id', $_SESSION['user_session']);
			
		
		if ($stmt->execute() && $stmt->rowCount() > 0) {
			$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
			$email = $userRow['UserEmail'];
		} else {
			$errmessage = 'An error has occurred. Pleasse try again.';
		}
	} catch(PDOException $e) {
		$errmessage = 'An error has occurred. Please try again.';
	}finally {
		$stmt=null;
	}
	
?>
</div>
 <div class="main">
    <div class="content">
   	<div class="section group customersection">
		<div class="col_1_of_4 span_1_of_4 sidebarborder">
			<?php include 'inc.sidebar.php'; ?>
		</div>
		
		<div class="col_3_of_4 span_3_of_4">
			<div class="customer_header">
				<h2>Change Email</h2>
			</div>
			<form action="<?php echo BASE_URL; ?>/customer/changeemail.php" name="changeemail" onsubmit="return validate.changemailForm()" method="post">
				<table class="accountinfo">
					<tbody>
						<tr>
							<td>Email:</td>
							<td><input id="email" name="email" type="text" value="<?php echo $email; ?>"></td>						
							<td class="link">
								<div class="buttons"><button type="submit" class="grey">Save Email</button></div>
							</td>
							<td><pre id="errmessage"><?php echo $errmessage; ?></pre></td>
					</tbody>
				</table>
			</form>

		</div>
	</div>
			
			
 </div>
</div>
<?php include '../inc.footer.php'; ?>
