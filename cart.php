<?php 
	require 'dbconnect.php';
	require 'constants.php';
	
	if(isset($_POST['remove']) && $_POST['remove'] === 'remove'
	&& isset($_POST['pid']) && !empty($_POST['pid'])
	&& isset($_SESSION['cart'][$_POST['pid']])) {
		$productID = $_POST['pid'];
		$backup = $_SESSION['cart'][$productID];
		unset($_SESSION['cart'][$productID]);
		
		try {
			$sessionData = serialize($_SESSION['cart']);
			
			$sql    = isset($_SESSION['user_session']) ? ' = :userID ': ' IS NULL ';
			$userID = isset($_SESSION['user_session']) ? $_SESSION['user_session'] : NULL;
			$hashID = session_id();

			$stmt = $conn->prepare("UPDATE sessions
									SET sessionData=:sessionData
									WHERE UserID ".$sql."
									AND HashID=:hashID");							
			
			if($userID !== NULL) {
				$stmt->bindParam(':userID', $userID);
			}
			$stmt->bindParam(':hashID', $hashID);
			$stmt->bindParam(':sessionData', $sessionData);
			
			//check if execution successful
			if (!$stmt->execute()) { $_SESSION['cart'][$productID] = $backup; }
		} catch(PDOException $e) {
			print_r($e);
		} finally {
			$stmt=null;
		}
		
	}
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
	if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
?>		
		<div class="cart_left">
			<div>
				<table>
					<thead>
						<tr>
							<th></th>
							<th></th>
							<th>Price</th>
							<th>Quantity</th>
						</tr>
					</thead>
					
					<tbody>
					
<?php
		$fullPrice = 0.0;
		foreach($_SESSION['cart'] as $productID => $array) {
			try {
				$stmt = $conn->prepare("SELECT p.ProductName
											 , p.ProductPrice
											 , p.ProductStock
											 , p.Discount
											 , CONCAT(images.imageName,'.',images.Extension) as image
										FROM products p
										INNER JOIN images
										ON images.ImageID = p.ProductThumbID
										WHERE p.ProductID=:productID
										LIMIT 1
										");
				
				if($stmt->execute(array('productID' => $productID)) && $stmt->rowCount() > 0)
				{
					$row=$stmt->fetch(PDO::FETCH_ASSOC);
					// $name = $row['ProductName'];
					$productName = $row['ProductName'];
					$productPrice = $row['ProductPrice'];
					$stock = intval($row['ProductStock']);
					$discount = floatval($row['Discount']);
					$image = $row['image'];
					
					// echo $productPrice . '  '. $discount;
					$fullPrice += (($productPrice - $productPrice * ($discount / 100.0)) * floatval($array['quantity']));

					
					echo '<tr class="background margin-bottom-10">';
					echo '<td class="width_20"><a><img class="display-image" src="'.BASE_URL.'/uploads/'.$image.'"></a></td>
						<td class="width_40 text-align-left">
							<p>'.$productName.'</p>';
					
					echo $stock > 0 ? '<p class="font-green"> Item is Available</p>' : '<p class="font-red"> Item is not Available</p>';
							
					echo '</td>';
					echo '<td class="width_20 vertical-align-middle">';
					
						echo $discount > 0 ? '<p><span class="strike">$'.$productPrice .'</span></p><p><span>$'.($productPrice - $productPrice * ($discount / 100.0)).'</span></p>' : '<p><span>$'.$productPrice .'</span></p>';
					
					echo '</td>';
					echo '<td class="width_20 vertical-align-middle">
							<div data-price="'. ($productPrice - $productPrice * ($discount / 100.0)) .'">
								<div class="width_30 float-left">
									<div data-sign="-" data-productid="'.$productID.'" class="arrow float-right left-arrow '. ((intval($array['quantity']) <= 1) ? 'disabled' : '') .'"></div>
								</div>
								<p class="quantity_text width_40 float-left">'.$array['quantity'].'</p>
								<div class="width_30 float-left">
									<div data-sign="+" data-productid="'.$productID.'" class="arrow float-left right-arrow"></div>
								</div>
							</div>
						</td>';
					echo '<td class="vertical-align-middle">
							<a class="btn_remove hover-pointer font-size-12" data-productid="'.$productID.'">Remove</a>
						  </td>';
					echo '</tr>';	
					echo '<tr class="paddng-bottom-10"><td></td></tr>';
			
				} else {
					echo 'fail';
				}
			} catch(PDOException $e) {
				echo $errmessage = 'An error has occurred. Please try again.';
				
			}finally {
				$stmt=null;
			}			
		}
?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="cart_right">
			<div class="checkout_sidebar">
				<table class="ordersummary width_100">
					<thead>
						<tr>
							<th class="padding-bottom-20" colspan="2" align="left"><h2>Order Summary</h2></td>
						</tr>					
					</thead>
					<tbody>
						<tr>
							<td>Full Price: </td>
							<td id="fullPrice">$<?php echo $fullPrice; ?></td>
						</tr>
						<tr class="border-bottom">
							<td>Delivery Fee: </td>
							<td>$99.00</td>
						</tr>
						<tr>
							<td>Total Price:</td>
							<td id="totalPrice" class="padding-top-10">$<?php echo $fullPrice + 99.0; ?></td>
						</tr>
					</tbody>
				</table>
		
			<form action="<?php echo BASE_URL; ?>/checkout" method="POST">
				<input name="part" type="hidden" value="one">
				<button class="btn btn-default text-align-center" type="submit">Checkout</button>
			<form>
			</div>
		</div>
<?php
	} else {
		echo 'No items in cart';
	}
?>		
		
		
	</div>
			
			
 </div>
</div>
<?php include 'inc.footer.php'; ?>
