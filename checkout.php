<?php 
	require 'dbconnect.php';
	require 'constants.php';
	
	if(!isset($_POST['part']) || empty($_POST['part'])) {
		header("Location: ".BASE_URL);
		die();		
	}

	$part = $_POST['part'];
	
	require 'inc.header.php'; 
	require 'inc.menu.php';	
	
	// echo '<pre>';
	// print_r($_SESSION['cart']);
	// echo '</pre>';
?>
</div>
<?php
	require 'inc.modal.php';
?>
 <div class="main">
    <div class="content">
		<div class="section group customersection font-segoe">
			<?php
				switch($part){
					case 'one': 
						$email = '';
						$fullName = '';
						$userPhone = '';
						$address = '';

						if(isset($_SESSION['user_session']) && !empty(isset($_SESSION['user_session']))) {
							
							if(isset($_SESSION['email'])) {
								$email = $_SESSION['email'];
							}

							if(isset($_SESSION['firstName']) && !empty($_SESSION['firstName'])
							&& isset($_SESSION['lastName']) && !empty($_SESSION['lastName'])) {
								$fullName = $_SESSION['firstName'].' '. $_SESSION['lastName'];
							}	
							if(isset($_SESSION['userPhone'])) {
								$userPhone = $_SESSION['userPhone'];
							}
							
							if(isset($_SESSION['address'])) {
								$address = $_SESSION['address'];
							}
						}
						
						echo '<div class="content_top margin-bottom-20">
								<p class="payment-header"><strong>Verify User Details</strong> >> Verify Payment</p>
							</div>';
						echo '<form action="'. BASE_URL.'/checkout" method="POST" onsubmit="">
								<table class="checkout-table">
									<tr>
										<td>Full Name:</td>
										<td>
											<input class="form-control" placeholder="Full Name" name="fullName" type="text" value="'. $fullName .'" required>
										</td>
									</tr>
									<tr>
										<td>Email:</td>
										<td>
											<input class="form-control" placeholder="Email" name="email" type="text" value="'. $email .'" requried>
										</td>
									</tr>
									<tr>
										<td>Phone number:</td>
										<td>
											<input class="form-control" placeholder="Phone Number" name="userPhone" type="number" value="'. $userPhone .'" required>
										</td>
									</tr>
									<tr>
										<td>Shipping Address:</td>
										<td>
											<textarea class="form-control" rows="4" cols="50" name="address" required>'.$address.'</textarea>
										</td>
									</tr>
									
									<tr>
										<td id="errmessage"></td>
										<td>
											<input type="hidden" name="part" value="two">
											<input class="btn btn-default float-right" type="submit">
										</td>
									</tr>
								</table>
							 </form>';
						
						break;
					case 'two': 
						if(isset($_POST['fullName']) && !empty($_POST['fullName'])
						&& isset($_POST['email']) && !empty($_POST['email'])
						&& isset($_POST['userPhone']) && !empty($_POST['userPhone'])
						&& isset($_POST['address']) && !empty($_POST['address'])
						) {
							
							$email = $_POST['email'];
							$userPhone = $_POST['userPhone'];
							$fullName = $_POST['fullName'];
							$address = $_POST['address'];
							
							echo '<div class="content_top margin-bottom-20">
									<p class="payment-header">Verify User Details >> <strong>Verify Payment</strong></p>
								</div>';
									echo ' <div>
											<table class="checkout-table">
												<tr>
													<td>Full Name:<td>
													<td>
														<label>'. $fullName .'</label>
													</td>
												</tr>
												<tr>
													<td>Email:<td>
													<td>
														<label>'. $email .'</label>
													</td>
												</tr>
												<tr>
													<td>Contact number:<td>
													<td>
														<label>'. $userPhone .'</label>
													</td>
												</tr>
												<tr>
													<td>Shipping Address:<td>
													<td>
														<label>'. $address .'</label>
													</td>
												</tr>

											</table>
										</div>
										<div>
											<p>Payment Method:</p>
										</div>
										<form id="form_payment" method="POST" >
											<input type="hidden" name="email" value="'.$email.'">
											<input type="hidden" name="userPhone" value="'.$userPhone.'">
											<input type="hidden" name="fullName" value="'.$fullName.'">
											<input type="hidden" name="address" value="'.$address.'">
											<div class="images section group">
												<div class="grid_1_of_4 images_1_of_4 image_index payment-tab background-cash padding-0">
													<label>
														<div>
															<input type="radio" name="paymentType" value="cashondeliver" checked="checked">
														</div>
													</label>
												</div>
												<div class="grid_1_of_4 images_1_of_4 image_index payment-tab background-paypal padding-0">
													<label>
														<div>
															<input type="radio" name="paymentType" value="paypal">
														</div>
													</label>
												</div>
											</div>
											<div>
												<button class="btn btn-default" type="submit">Place Order</button>
											</div>
										</form>
										';
							
							
						}
						break;
						case 'three':
							if(isset($_POST['trackingnumber']) && !empty($_POST['trackingnumber']) ) {
								$trackingnumber = $_POST['trackingnumber'];
								echo '<div class="content_top margin-bottom-20">
										<p class="payment-header">Verify User Details >> <strong>Verify Payment</strong></p>
									</div>';
								
								echo '<div>
									<p>Your order has been received and will undergo processing</p>
									<p>Please remember your order\'s tracking number:<strong class="font-weight-600">'.$trackingnumber.'</strong> </p>
									<p>so that you will be able to know the status of your purchase.</p>
									<p>Thank for shopping at ecom. :)</p>
								</div>';
							}
						break;
				}
			?>
		</div>
			
			
	</div>
</div>
<?php include 'inc.footer.php'; ?>
