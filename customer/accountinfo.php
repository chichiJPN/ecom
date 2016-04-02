<?php 
	require '../dbconnect.php';
	require '../constants.php';	
	require '../inc.header.php'; 
	require '../inc.menu.php';
	require '../inc.verifyuser.php';
	
	$errmessage = '';
	if(isset($_POST['fName']) && !empty($_POST['fName']) && isset($_POST['lName']) && !empty($_POST['lName'] && isset($_POST['lName']))) {
		$fName = $_POST['fName'];
		$lName = $_POST['lName'];
		$mobile = $_POST['mobile'];
		if($mobile === "") $mobile = null;
		
		try {
			
			$stmt = $conn->prepare("UPDATE `users`   
									   SET `UserFirstName` = :fName,	
										   `UserLastName` = :lName,
										   `UserPhone` = :mobile
									 WHERE `UserID` = :user_id");
			
			$stmt->bindParam(':fName', $fName);
			$stmt->bindParam(':lName', $lName);
			$stmt->bindParam(':mobile', $mobile);
			$stmt->bindParam(':user_id', $_SESSION['user_session']);
			
			if ($stmt->execute()) {
				$errmessage = "Update Successful!<br>Please re-login for the changes to take effect.";
			} else {
				$errmessage = "Error: " . $query . "<br>" . mysqli_error($conn);
			}
		} catch(PDOException $e) {
			// echo '<pre>';
			// echo print_r($e);
			// echo '</pre>';
			// if($e->errorinfo[1] == 1062) {
				// $errmessage = 'Email already exists.';
			// } else {
				$errmessage = 'An error has occurred. Please try again.';								
		}finally {
			$stmt=null;
		}
	}

	
	$fName = '';
	$lName = '';
	$mobile = '';
	
	try {
		$stmt = $conn->prepare("SELECT UserFirstName, UserLastName, UserPhone
								FROM users
								WHERE `UserID` = :user_id
								LIMIT 1");
		
		$stmt->bindParam(':user_id', $_SESSION['user_session']);
			
		
		if ($stmt->execute() && $stmt->rowCount() > 0) {
			$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
			$fName = $userRow['UserFirstName'];
			$lName= $userRow['UserLastName'];
			$mobile = $userRow['UserPhone'];
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
					<h2>Account Information</h2>
				</div>
				<form action="<?php echo BASE_URL;?>/customer/accountinfo.php" name="accountinfo" onsubmit="return validate.accountinfoForm()" method="post">
					<table class="accountinfo">
						<tbody>
							<tr>
								<td>Name:</td>
								<td><input id="fName" name="fName" type="text" value="<?php echo $fName; ?>"></td>						
								<td><input id="lName" name="lName" type="text" value="<?php echo $lName; ?>"></td>						
								<td></td>
							</tr>
							<tr>
								<td>Mobile:</td>
								<td><input id="mobile" name="mobile" type="text" value="<?php echo $mobile; ?>"></td>
								<td></td>
								<td><pre id="errmessage"><?php echo $errmessage; ?></pre></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td class="link">
									<div class="buttons"><button type="submit" class="grey">Save Info</button></div>
								</td>							
								<td></td>
							</tr>
						</tbody>
					</table>
				</form>
					
				<table class="accountinfo">
					<tbody>					
						<tr>
							<td>Email:</td>
							<td><?php echo $_SESSION['email']; ?></td>
							<td><a href="<?php echo BASE_URL; ?>/customer/changeemail.php">Change email</a></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td><a href="<?php echo BASE_URL; ?>/customer/changepassword.php">Change password</a></td>
						</tr>

					</tbody>
				</table>

			</div>
		</div>
			
	</div>
 </div>
<?php include '../inc.footer.php'; ?>
