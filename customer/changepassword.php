<?php 
	require '../dbconnect.php';
	require '../constants.php';	
	require '../inc.header.php'; 
	require '../inc.menu.php';
	require '../inc.verifyuser.php';
	
	$errmessage = '';
	// echo password_hash('potato', PASSWORD_BCRYPT);
	if(isset($_POST['oldpass']) && !empty($_POST['oldpass']) && isset($_POST['newpass']) && !empty($_POST['newpass']) && isset($_POST['rpass']) && !empty($_POST['rpass']) && ($_POST['rpass'] === $_POST['newpass'])) {
		$oldpass = $_POST['oldpass'];
		$newpass = $_POST['newpass'];
		
		try {
		  $stmt = $conn->prepare("SELECT UserPassword FROM users WHERE UserID=:userID LIMIT 1");
		  $stmt->execute(array(':userID'=>$_SESSION['user_session']));
		  $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
		  if($stmt->rowCount() > 0)
		  {
			 if(password_verify($oldpass, $userRow['UserPassword']))
			 {
				try {
					$stmt2 = $conn->prepare("UPDATE users
											    SET UserPassword=:newpass
											  WHERE UserID=:userID");
					$stmt2->execute(array(':userID'=>$_SESSION['user_session'], ':newpass'=>password_hash($newpass, PASSWORD_BCRYPT)));

					if($stmt2->rowCount() > 0) {
						$errmessage = 'Password has been updated';
					} else {
						$errmessage = 'An error has occurred';
					}
				
				} catch(PDOException $e) {
					$errmessage = 'An error has occurred';
				} finally {
					$stmt2=null;
				}
			 } else {
				 $errmessage = 'Wrong password.';
			 }
		  } else {
			$errmessage = 'An error has occured.';
		  }
	   }
	   catch(PDOException $e)
	   {
			$errmessage = 'An error has occurred';
		   // echo '<pre>';
		   // echo print_r($e);
		   // echo '</pre>';
	   } finally {
			$stmt=null;
		}


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
					<h2>Change password</h2>
				</div>
				<form action="<?php echo BASE_URL; ?>/customer/changepassword.php" name="changepassword" onsubmit="return validate.changepasswordForm()" method="post">
					<table class="accountinfo">
						<tbody>
							<tr>
								<td>Old Password:</td>
								<td><input id="oldpass" name="oldpass" type="password" value=""></td>						
								<td></td>
							</tr>
							<tr>
								<td>New Password:</td>
								<td><input id="newpass" name="newpass" type="password" value=""></td>						
								<td></td>
							</tr>
							<tr>
								<td>Repeat Password:</td>
								<td><input id="rpass" name="rpass" type="password" value=""></td>						
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td style="text-align:center;" class="link">
									<div class="buttons"><button type="submit" class="grey">Save</button></div>
								</td>
								<td><pre id="errmessage"><?php echo $errmessage; ?></pre></td>						
							</tr>

						</tbody>
					</table>
				</form>

			</div>
		</div>
			
 </div>
</div>
<?php include '../inc.footer.php'; ?>