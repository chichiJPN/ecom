<?php 
	require 'dbconnect.php';
	require 'constants.php';	
	require 'inc.header.php'; 
	require 'inc.menu.php';
	
?>
</div>
<?php
	require 'inc.modal.php';
?>
 <div class="main">

	<div class="content">
		<div>
			<form action="<?php echo BASE_URL; ?>/delivery" method="POST">
				<table class="table">
					<tr>
						<td>Tracking Number: </td>
						<td><input type="text" class="form-control" name="trackingnumber" placeholder="Enter tracking number here" value="<?php if(isset($_POST['trackingnumber'])) echo $_POST['trackingnumber']; ?>"></td>
					</tr>
				</table>
			</form>
		</div>
		<div>
			<?php
				
				if(isset($_POST['trackingnumber']) && !empty($_POST['trackingnumber'])) {
					try {
						$trackingNumber = $_POST['trackingnumber'];
						
						$stmt = $conn->prepare("SELECT OrderStatus
							FROM orders
							WHERE OrderTrackingNumber=:trackingNumber
							LIMIT 1");
							
						
						$stmt->bindParam(':trackingNumber', $trackingNumber);
						
						if($stmt->execute() && $stmt->rowCount() > 0)
						{
							$row=$stmt->fetch(PDO::FETCH_ASSOC);
							switch($row['OrderStatus']) {
								case 'fordelivery':
									echo 'This order is on delivery.';
									break;
								case 'cancelled':
									echo 'This order is cancelled.';
									break;
								case 'delivered':
									echo 'This order has been delivered.';
									break;
								case 'processing':
									echo 'This order is still being processed.';
									break;
							}
						}
					} catch(PDOException $e) {}
					finally {
						$stmt=null;
					}
				}
			?>
		</div>
	</div>
			
 </div>
</div>
<?php include 'inc.footer.php'; ?>
