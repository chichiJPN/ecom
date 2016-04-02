<?php 
	require '../dbconnect.php';
	require '../constants.php';
	require 'inc.admin.session.check.php';
	
	
	// put checker if get ID exists
	if(!(isset($_GET['order']) && !empty($_GET['order']))) {
		header('Location: '.BASE_URL.'/admin/admin');
		die();
	}
	
	$order = $_GET['order'];
	
	// echo '<pre>';
	// print_r($_FILES["fileToUpload"]);
	// echo '</pre>';

	require '../inc.header.php'; 
	require '../inc.menu.php';
	
?>

 </div>
<?php
	require '../inc.modal.php';
?>

 <div class="main">
    <div class="content">
		<ul class="nav nav-tabs">
		  <li role="presentation" <?php echo $order === 'processing' ? 'class="active"':''; ?>><a href="<?php echo BASE_URL; ?>/admin/admin_orders?order=processing">For processing</a></li>
		  <li role="presentation" <?php echo $order === 'fordelivery' ? 'class="active"':''; ?>><a href="<?php echo BASE_URL; ?>/admin/admin_orders?order=fordelivery">For delivery</a></li>
		  <li role="presentation" <?php echo $order === 'delivered' ? 'class="active"':''; ?>><a href="<?php echo BASE_URL; ?>/admin/admin_orders?order=delivered">Delivered</a></li>
		  <li role="presentation" <?php echo $order === 'cancelled' ? 'class="active"':''; ?>><a href="<?php echo BASE_URL; ?>/admin/admin_orders?order=cancelled">Cancelled</a></li>
		</ul>
		
		<div class="clear margin-bottom-10"></div>
		<?php
			$flagsuccess = false;
			switch($order) {
				case 'processing':
					if(isset($_POST['type']) && !empty($_POST['type'])
					&& isset($_POST['orderid']) && !empty($_POST['orderid'])) {
						$type = $_POST['type'];
						$orderid = $_POST['orderid'];
						
						switch($type) {
							case 'cancel':
								try {
									$conn->beginTransaction();
									
									$stmt = $conn->prepare("UPDATE orders
															SET OrderStatus='cancelled'
															WHERE OrderID=:orderID");
															
									$stmt->bindParam(':orderID', $orderid);

									if ($stmt->execute()  && $conn->commit()) {
									} else {
										
										$conn->rollBack();
									}
								} catch(PDOException $e) {
									$conn->rollBack();
								} finally {
									$stmt=null;
								}
								break;
							case 'fordelivery':
								try {
									$conn->beginTransaction();
									
									$stmt = $conn->prepare("UPDATE orders
															SET OrderStatus='fordelivery'
															WHERE OrderID=:orderID");
									$stmt->bindParam(':orderID', $orderid);

			 
									//check if execution successful
									if ($stmt->execute()  && $conn->commit()) {
										$flagsuccess = true;
									} else {
										
										$conn->rollBack();
									}
								} catch(PDOException $e) {
									$conn->rollBack();
								} finally {
									$stmt=null;
								}

								break;
						}
					}
					
					if($flagsuccess === true) {
						echo '<div class="content_top">
								<div class="back-links">
									<p class="font-green" >Order is now for delivery</p>
								</div>
								<div class="clear"></div>
							</div>
							';
					}
					
					try {
						$stmt = $conn->prepare("SELECT OrderID
													 , OrderAmount
													 , OrderUserName
													 , OrderShipAddress
													 , OrderPhone
													 , OrderEmail
													 , OrderDate
													 , OrderTrackingNumber
													 , OrderPaymentType
													 , OrderPaid
													 , OrderPaymentDate
												FROM orders
												WHERE OrderFinalized=1
												AND OrderStatus='processing'
												");
						
						
						
						if($stmt->execute() && $stmt->rowCount() > 0)
						{
							while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
								echo '<div class="borders margin-bottom-10 padding-10 float-left font-segoe">
										<table class="table float-left margin-right-30">
											<tr>
												<td><p class="height-30 padding-top-5 font-weight-600">Order Details:</p></td>
												<td class="text-align-right"></td>
											</tr>
											<tr>
												<td><p>Tracking Number:</p></td>
												<td><p>'.$row['OrderTrackingNumber'].'</p></td>
											</tr>
											<tr>
												<td><p>Type of order:</p></td>
												<td><p>'.$row['OrderPaymentType'].'</p></td>
											</tr>
											<tr>
												<td><p>Order Amount:</p></td>
												<td><p>$'.$row['OrderAmount'].'</p></td>
											</tr>
											<tr>
												<td><p>Has paid:</p></td>
												<td>'.(($row['OrderPaid'] == '0') ? '<p class="font-red">No</p>' : '<p class="font-green">Yes</p>'). '</td>
											</tr>
											<tr>
												<td><p>Paid on:</p></td>
												<td><p>'.$row['OrderPaymentDate'].'</p></td>
											</tr>
										</table>
										<table class="table float-left">
											<tr>
												<td><p class="font-weight-600">User Details:</p></td>
												<td class="text-align-right"><input data-orderid="'.$row['OrderID'].'" type="button" class="btn btn-default cancel_order" type="submit" value="X"></td>
											</tr>
											<tr>
												<td><p>Full Name:</p></td>
												<td><p>'.$row['OrderUserName'].'</p></td>
											</tr>
											<tr>
												<td><p>Email:</p></td>
												<td><p>'.$row['OrderEmail'].'</p></td>
											</tr>
											<tr>
												<td><p>Phone Number:</p></td>
												<td><p>'.$row['OrderPhone'].'</p></td>
											</tr>
											<tr>
												<td><p>Ordered On:</p></td>
												<td><p>'.$row['OrderDate'].'</p></td>
											</tr>
											<tr>
												<td class="padding-bottom-20"><p>Shipping address:</p></td>
												<td><p>'.$row['OrderShipAddress'].'</p></td>
											</tr>
											<tr>
												
												<td><input data-orderid="'.$row['OrderID'].'" class="btn btn-default order_details" type="submit" value="More details"></td>
												<td>
													<form action="'.BASE_URL.'/admin/admin_orders?order=processing" method="POST">
														<input type="hidden" name="type" value="fordelivery">
														<input type="hidden" name="orderid" value="'.$row['OrderID'].'">
														<input class="btn btn-default" type="submit" value="For delivery">
													</form>
												</td>
											</tr>
										</table>
									</div>
									<div class="clear"></div>
									';
							}
						}
					} catch(PDOException $e) {
						$errmessage = 'An error has occurred. Please try again.';
					}finally {
						$stmt=null;
					}
					break;
				case 'fordelivery':
					if(isset($_POST['type']) && !empty($_POST['type'])
					&& isset($_POST['orderid']) && !empty($_POST['orderid'])) {
						$type = $_POST['type'];
						$orderid = $_POST['orderid'];
						
						switch($type) {
							case 'cancel':
								try {
									$conn->beginTransaction();
									
									$stmt = $conn->prepare("UPDATE orders
															SET OrderStatus='cancelled'
															WHERE OrderID=:orderID");
															
									$stmt->bindParam(':orderID', $orderid);

									if ($stmt->execute()  && $conn->commit()) {
									} else {
										
										$conn->rollBack();
									}
								} catch(PDOException $e) {
									$conn->rollBack();
								} finally {
									$stmt=null;
								}
								break;
							case 'delivered':
								try {
									$conn->beginTransaction();
									
									$stmt = $conn->prepare("UPDATE orders
															SET OrderStatus='delivered'
															WHERE OrderID=:orderID");
									$stmt->bindParam(':orderID', $orderid);

			 
									//check if execution successful
									if ($stmt->execute()  && $conn->commit()) {
										$flagsuccess = true;
									} else {
										
										$conn->rollBack();
									}
								} catch(PDOException $e) {
									$conn->rollBack();
								} finally {
									$stmt=null;
								}

								break;
						}
					}
					
					if($flagsuccess === true) {
						echo '<div class="content_top">
								<div class="back-links">
									<p class="font-green" >Order status is now delivered.</p>
								</div>
								<div class="clear"></div>
							</div>
							';
					}
					
					try {
						$stmt = $conn->prepare("SELECT OrderID
													 , OrderAmount
													 , OrderUserName
													 , OrderShipAddress
													 , OrderPhone
													 , OrderEmail
													 , OrderDate
													 , OrderTrackingNumber
													 , OrderPaymentType
													 , OrderPaid
													 , OrderPaymentDate
												FROM orders
												WHERE OrderFinalized=1
												AND OrderStatus='fordelivery'
												");
						
						if($stmt->execute() && $stmt->rowCount() > 0)
						{
							while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
								echo '<div class="border-blue margin-bottom-10 padding-10 float-left font-segoe">
										<table class="table float-left margin-right-30">
											<tr>
												<td><p class="height-30 padding-top-5 font-weight-600">Order Details:</p></td>
												<td class="text-align-right"></td>
											</tr>
											<tr>
												<td><p>Tracking Number:</p></td>
												<td><p>'.$row['OrderTrackingNumber'].'</p></td>
											</tr>
											<tr>
												<td><p>Type of order:</p></td>
												<td><p>'.$row['OrderPaymentType'].'</p></td>
											</tr>
											<tr>
												<td><p>Order Amount:</p></td>
												<td><p>$'.$row['OrderAmount'].'</p></td>
											</tr>
											<tr>
												<td><p>Has paid:</p></td>
												<td>'.(($row['OrderPaid'] == '0') ? '<p class="font-red">No</p>' : '<p class="font-green">Yes</p>'). '</td>
											</tr>
											<tr>
												<td><p>Paid on:</p></td>
												<td><p>'.$row['OrderPaymentDate'].'</p></td>
											</tr>
										</table>
										<table class="table float-left">
											<tr>
												<td><p class="font-weight-600">User Details:</p></td>
												<td class="text-align-right"><input data-orderid="'.$row['OrderID'].'" type="button" class="btn btn-default cancel_order" type="submit" value="X"></td>
											</tr>
											<tr>
												<td><p>Full Name:</p></td>
												<td><p>'.$row['OrderUserName'].'</p></td>
											</tr>
											<tr>
												<td><p>Email:</p></td>
												<td><p>'.$row['OrderEmail'].'</p></td>
											</tr>
											<tr>
												<td><p>Phone Number:</p></td>
												<td><p>'.$row['OrderPhone'].'</p></td>
											</tr>
											<tr>
												<td><p>Ordered On:</p></td>
												<td><p>'.$row['OrderDate'].'</p></td>
											</tr>
											<tr>
												<td class="padding-bottom-20"><p>Shipping address:</p></td>
												<td><p>'.$row['OrderShipAddress'].'</p></td>
											</tr>
											<tr>
												
												<td><input data-orderid="'.$row['OrderID'].'" class="btn btn-default order_details" type="submit" value="More details"></td>
												<td>
													<form action="'.BASE_URL.'/admin/admin_orders?order=fordelivery" method="POST">
														<input type="hidden" name="type" value="delivered">
														<input type="hidden" name="orderid" value="'.$row['OrderID'].'">
														<input class="btn btn-default" type="submit" value="Delivered">
													</form>
												</td>
											</tr>
										</table>
									</div>
									<div class="clear"></div>
									';
							}
						}
					} catch(PDOException $e) {
						$errmessage = 'An error has occurred. Please try again.';
					}finally {
						$stmt=null;
					}
					
					break;
				case 'delivered':
					try {
						$stmt = $conn->prepare("SELECT OrderID
													 , OrderAmount
													 , OrderUserName
													 , OrderShipAddress
													 , OrderPhone
													 , OrderEmail
													 , OrderDate
													 , OrderTrackingNumber
													 , OrderPaymentType
													 , OrderPaid
													 , OrderPaymentDate
												FROM orders
												WHERE OrderFinalized=1
												AND OrderStatus='delivered'
												");
						
						if($stmt->execute() && $stmt->rowCount() > 0)
						{
							while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
								echo '<div class="border-green margin-bottom-10 padding-10 float-left font-segoe">
										<table class="table float-left margin-right-30">
											<tr>
												<td><p class="height-30 padding-top-5 font-weight-600">Order Details:</p></td>
												<td class="text-align-right"></td>
											</tr>
											<tr>
												<td><p>Tracking Number:</p></td>
												<td><p>'.$row['OrderTrackingNumber'].'</p></td>
											</tr>
											<tr>
												<td><p>Type of order:</p></td>
												<td><p>'.$row['OrderPaymentType'].'</p></td>
											</tr>
											<tr>
												<td><p>Order Amount:</p></td>
												<td><p>$'.$row['OrderAmount'].'</p></td>
											</tr>
											<tr>
												<td><p>Has paid:</p></td>
												<td>'.(($row['OrderPaid'] == '0') ? '<p class="font-red">No</p>' : '<p class="font-green">Yes</p>'). '</td>
											</tr>
											<tr>
												<td><p>Paid on:</p></td>
												<td><p>'.$row['OrderPaymentDate'].'</p></td>
											</tr>
										</table>
										<table class="table float-left">
											<tr>
												<td><p class="font-weight-600">User Details:</p></td>
												<td class="text-align-right"></td>
											</tr>
											<tr>
												<td><p>Full Name:</p></td>
												<td><p>'.$row['OrderUserName'].'</p></td>
											</tr>
											<tr>
												<td><p>Email:</p></td>
												<td><p>'.$row['OrderEmail'].'</p></td>
											</tr>
											<tr>
												<td><p>Phone Number:</p></td>
												<td><p>'.$row['OrderPhone'].'</p></td>
											</tr>
											<tr>
												<td><p>Ordered On:</p></td>
												<td><p>'.$row['OrderDate'].'</p></td>
											</tr>
											<tr>
												<td class="padding-bottom-20"><p>Shipping address:</p></td>
												<td><p>'.$row['OrderShipAddress'].'</p></td>
											</tr>
											<tr>
												
												<td><input id="order_details" data-orderid="'.$row['OrderID'].'" class="btn btn-default order_details" type="button" value="More details"></td>
												<td></td>
											</tr>
										</table>
									</div>
									<div class="clear"></div>
									';
							}
						}
					} catch(PDOException $e) {
						$errmessage = 'An error has occurred. Please try again.';
					}finally {
						$stmt=null;
					}
					break;
				case 'cancelled':
					try {
						$stmt = $conn->prepare("SELECT OrderID
													 , OrderAmount
													 , OrderUserName
													 , OrderShipAddress
													 , OrderPhone
													 , OrderEmail
													 , OrderDate
													 , OrderTrackingNumber
													 , OrderPaymentType
													 , OrderPaid
													 , OrderPaymentDate
												FROM orders
												WHERE OrderStatus='cancelled'
												");
						
						if($stmt->execute() && $stmt->rowCount() > 0)
						{
							while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
								echo '<div class="border-red margin-bottom-10 padding-10 float-left font-segoe">
										<table class="table float-left margin-right-30">
											<tr>
												<td><p class="height-30 padding-top-5 font-weight-600">Order Details:</p></td>
												<td class="text-align-right"></td>
											</tr>
											<tr>
												<td><p>Tracking Number:</p></td>
												<td><p>'.$row['OrderTrackingNumber'].'</p></td>
											</tr>
											<tr>
												<td><p>Type of order:</p></td>
												<td><p>'.$row['OrderPaymentType'].'</p></td>
											</tr>
											<tr>
												<td><p>Order Amount:</p></td>
												<td><p>$'.$row['OrderAmount'].'</p></td>
											</tr>
											<tr>
												<td><p>Has paid:</p></td>
												<td>'.(($row['OrderPaid'] == '0') ? '<p class="font-red">No</p>' : '<p class="font-green">Yes</p>'). '</td>
											</tr>
											<tr>
												<td><p>Paid on:</p></td>
												<td><p>'.$row['OrderPaymentDate'].'</p></td>
											</tr>
										</table>
										<table class="table float-left">
											<tr>
												<td><p class="font-weight-600">User Details:</p></td>
												<td class="text-align-right"></td>
											</tr>
											<tr>
												<td><p>Full Name:</p></td>
												<td><p>'.$row['OrderUserName'].'</p></td>
											</tr>
											<tr>
												<td><p>Email:</p></td>
												<td><p>'.$row['OrderEmail'].'</p></td>
											</tr>
											<tr>
												<td><p>Phone Number:</p></td>
												<td><p>'.$row['OrderPhone'].'</p></td>
											</tr>
											<tr>
												<td><p>Ordered On:</p></td>
												<td><p>'.$row['OrderDate'].'</p></td>
											</tr>
											<tr>
												<td class="padding-bottom-20"><p>Shipping address:</p></td>
												<td><p>'.$row['OrderShipAddress'].'</p></td>
											</tr>
											<tr>
												<td></td>
												<td><input data-orderid="'.$row['OrderID'].'" class="btn btn-default order_details" type="button" value="More details"></td>
											</tr>
										</table>
									</div>
									<div class="clear"></div>
									';
							}
						}
					} catch(PDOException $e) {
						$errmessage = 'An error has occurred. Please try again.';
					}finally {
						$stmt=null;
					}
					
					break;
			}
		?>
		
		<div class="clear"></div>
    </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/admin.js"></script>

<?php include '../inc.footer.php'; ?>
