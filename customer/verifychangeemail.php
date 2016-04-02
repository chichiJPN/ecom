<?php 
	require '../dbconnect.php';
	require '../constants.php';	
	require '../inc.header.php'; 
	require '../inc.menu.php';
	require '../inc.verifyuser.php';	
	
?>
</div>
 <div class="main">
    <div class="content">
    	<div class="section group">
				<div class="col_1_of_3 span_1_of_3">
				</div>
				<div class="col_1_of_3 span_1_of_3">
<?php 
	$userID = $_SESSION['user_session'];
	$authCode = $_GET['key'];
	
	
	try {
		$stmt = $conn->prepare("UPDATE users
								   SET UserEmail= UserEmail2
								   where UserID = :userID
								   AND UserVerificationCode=:authCode");
		$stmt->execute(array(':userID'=>$userID,':authCode'=>$authCode));
		// $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
		if($stmt->rowCount() > 0) {
			session_unset(); 
			session_destroy(); 
			echo '<h3>Account has been verified.</h3>';
			echo '<h3>Please login in</h3>';
			echo '<h3> with the new email.</h3>';
		} else {
			echo '<h3>Error in verification. Please try again.</h3>';
		}
		
	} catch(PDOException $e) {
		echo '<h3>Error in verification. Please try again.</h3>';
		echo '<h3>If error persists, someone made an account with this username</h3>';
	} finally {
		$stmt=null;
	}
?>

		</div>
		<div class="col_1_of_3 span_1_of_3">
		</div>
	</div>
			
 </div>
</div>
<?php include '../inc.footer.php'; ?>
