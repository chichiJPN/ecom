<?php 
	require 'dbconnect.php';
	require 'constants.php';	
	require 'inc.header.php'; 
	require 'inc.menu.php';
?>
</div>
 <div class="main">
    <div class="content">
    	<div class="section group">
				<div class="col_1_of_3 span_1_of_3">
				</div>
				<div class="col_1_of_3 span_1_of_3">
<?php 
	$email = $_GET['email'];
	$authCode = $_GET['hash'];
	
	
	try {
		$stmt = $conn->prepare("UPDATE users
								   SET UserEmailVerified=1
								 WHERE UserEmail=:email 
								   AND UserVerificationCode=:authCode");
		$stmt->execute(array(':email'=>$email, ':authCode'=>$authCode));
		// $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
		if($stmt->rowCount() > 0) {
			echo '<h3>Account has been verified.</h3>';
			echo '<h3>You can now login in</h3>';
			echo '<h3> the login page.</h3>';
		} else {
			echo '<h3>Error in verification. Please try again.</h3>';
		}
		
	} catch(PDOException $e) {
		echo '<h3>Error in verification. Please try again.</h3>';
	} finally {
		$stmt=null;
	}
	
	// $query = 'UPDATE users
	// SET UserEmailVerified=1
	// WHERE UserEmail="'.$email.'" 
	// AND UserVerificationCode="'.$authCode.'" ';
	
	// if (mysqli_query($conn, $query)) {
		// echo '<h3>Account has been verified.</h3>';
		// echo '<h3>You can now login in login page.</h3>';
	// } else {
		// echo '<h3>Error in verification. Please try again.</h3>';
	// }
?>
					
				</div>
				<div class="col_1_of_3 span_1_of_3">
				</div>
			</div>
			
 </div>
</div>
<?php include 'inc.footer.php'; ?>
