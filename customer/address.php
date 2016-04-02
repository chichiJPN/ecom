<?php 
	require '../dbconnect.php';
	require '../constants.php';	
	require '../inc.header.php'; 
	require '../inc.menu.php';
	require '../inc.verifyuser.php';
	
	try {
		$stmt = $conn->prepare("SELECT UserAddress1, UserAddres, UserPhone
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
					<h2>Address</h2>
				</div>
				<div class="customer_address">
					
				</div>

			</div>
		</div>
			
 </div>
</div>
<?php include '../inc.footer.php'; ?>
