<?php
	require '../dbconnect.php';
	require 'inc.admin.session.check.php';
	
	$return = false;
	if(isset($_POST['type']) && !empty($_POST['type'])
	&& isset($_SESSION['type']) && $_SESSION['type'] == '2'){
		$type = $_POST['type'];
		// $userID = $_SESSION['user_session'];
		
		switch($type) {
			case 'delete':
				if(isset($_POST['productid']) && !empty($_POST['productid'])) {
					
					$productID = $_POST['productid'];

					try {
						$conn->beginTransaction();
						
						$stmt = $conn->prepare("SELECT CONCAT(ImageName,'.',Extension) as image
												FROM images
												WHERE ProductID=:productID");
												
						$stmt->bindParam(':productID', $productID);
						
						$stmt2 = $conn->prepare("DELETE FROM images
												 WHERE ProductID=:productID");

						$stmt2->bindParam(':productID', $productID);

						$stmt3 = $conn->prepare("DELETE FROM products
												 WHERE ProductID=:productID");

						$stmt3->bindParam(':productID', $productID);
 
						//check if execution successful
						if ($stmt->execute() && $stmt2->execute() && $stmt3->execute() && $conn->commit()) {
							
							while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								@unlink('../uploads/'.$row['image']);
							}
							$return['flag'] = true;
							
						} else {
							
							$conn->rollBack();
							$return['asdasd'] = 'asdasd';
							$return['flag'] = false;
						}
					} catch(PDOException $e) {
						$conn->rollBack();
						$return['error'] = $e;
						$return['flag'] = false;
					} finally {
						$stmt=null;
					}
				} else {
					$return['flag'] == false;
				}
				break;
			case 'orderdetails':
				if(isset($_POST['orderid']) && !empty($_POST['orderid'])) {
					
					$orderID = $_POST['orderid'];

					try {
						$stmt = $conn->prepare("SELECT DetailQuantity
													  ,DetailName
													  ,DetailPrice
													  ,DetailProductID
												FROM orderdetails
												WHERE orderdetails.DetailOrderID=:orderID");
												
						$stmt->bindParam(':orderID', $orderID);
						

 
						//check if execution successful
						if ($stmt->execute()) {
							$return['data'] = [];
							while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								$return['data'][] = $row;
							}
							$return['flag'] = true;
						} else {
							$return['flag'] = false;
						}
					} catch(PDOException $e) {
						$return['error'] = $e;
						$return['flag'] = false;
					} finally {
						$stmt=null;
					}
				} else {
					$return['flag'] == false;
				}
				break;
		}
	} 
	$conn = null;
	echo json_encode($return);
?>