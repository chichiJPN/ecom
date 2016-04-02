<?php
	require 'dbconnect.php';
	
	
	$return = false;
	if(isset($_POST['type']) && !empty($_POST['type'])) {
		$type = $_POST['type'];
		// $userID = $_SESSION['user_session'];
		
		switch($type) {
			case 'addToCart':
				if(isset($_POST['pid']) && !empty($_POST['pid'])) {
					
					$productID = $_POST['pid'];
					if(isset($_SESSION['cart'][$productID]) && !empty($_SESSION['cart'][$productID])) {
						$_SESSION['cart'][$productID]['quantity']++;
					} else {
						$_SESSION['cart'][$productID] = array(
													'quantity' => 1
												);
					}
					
					$date = date_create();
					$date = date_format($date, 'Y-m-d H:i:s');

					// check if user is a registered user
					if(isset($_SESSION['user_session']) && !empty($_SESSION['user_session'])) {
						
						try {
							$sessionData = serialize($_SESSION['cart']);
							$userID = $_SESSION['user_session'];
							$hashID = session_id();
						
							$stmt = $conn->prepare("UPDATE sessions
													SET sessionData=:sessionData
													  , lastUpdated=:lastUpdated
													WHERE UserID=:userID
													AND HashID=:hashID");
													
							$stmt->bindParam(':userID', $userID);
							$stmt->bindParam(':hashID', $hashID);
							$stmt->bindParam(':sessionData', $sessionData);
							$stmt->bindParam(':lastUpdated', $date);
							
							//check if execution successful
							if ($stmt->execute()) {
								$return['flag'] = true;
							} else {
								// reverse addition of item
								if($_SESSION['cart'][$productID]['quantity'] <= 1) { unset($_SESSION['cart'][$productID]); }
								else { $_SESSION['cart'][$productID]['quantity']--; }
								
								$return['flag'] = false;
							}
						} catch(PDOException $e) {
							$return = false;
							$return['error'] = $e;
						}finally {
							$stmt=null;
						}
						
					} else {
						// anonymous user has added already
						if(isset($_SESSION['anonymousAdded'])) {
							try {
								$sessionData = serialize($_SESSION['cart']);
								$hashID = session_id();
							
								$stmt = $conn->prepare("UPDATE sessions
														SET sessionData=:sessionData,
														lastUpdated=:lastUpdated
														WHERE HashID=:hashID
														AND UserID IS NULL");
														
								$stmt->bindParam(':hashID', $hashID);
								$stmt->bindParam(':sessionData', $sessionData);
								$stmt->bindParam(':lastUpdated', $date);
								
								//check if execution successful
								if ($stmt->execute()) {
									$return['flag'] = true;
								} else {
									// reverse addition of item
									if($_SESSION['cart'][$productID]['quantity'] <= 1) { unset($_SESSION['cart'][$productID]); }
									else { $_SESSION['cart'][$productID]['quantity']--; }
									
									$return['flag'] = false;
								}
							} catch(PDOException $e) {
								$return['flag'] = false;
								$return['error'] = $e;
							}finally {
								$stmt=null;
							}
						} else {
							try {
								$hashID = session_id();
								$sessionData = serialize($_SESSION['cart']);
								
								$stmt = $conn->prepare("INSERT INTO sessions (`HashID`
																			  ,`sessionData`) VALUES (:hashID
																									, :sessionData)");
							
														
								$stmt->bindParam(':hashID', $hashID);
								$stmt->bindParam(':sessionData', $sessionData);
								
								//check if execution successful
								if ($stmt->execute()) {
									$_SESSION['anonymousAdded'] = true;
									$return['flag'] = true;
								} else {
									session_regenerate_id();
									if($_SESSION['cart'][$productID]['quantity'] <= 1) { unset($_SESSION['cart'][$productID]); } 
									else { $_SESSION['cart'][$productID]['quantity']--; }
									$return['flag'] = false;
								}
							} catch(PDOException $e) {
								session_regenerate_id();
								$return['flag'] = false;
								$return['error'] = $e;
							}finally {
								$stmt=null;
							}
						}
					}
				}
				break;
			case 'cart_addminus':
				if(isset($_POST['pid']) && !empty($_POST['pid']) 
				&& isset($_SESSION['cart'][$_POST['pid']])
				&& isset($_POST['sign']) && !empty($_POST['sign'])
				&& ($_POST['sign'] === '+' || $_POST['sign'] === '-')) {
					$sign = $_POST['sign'];
					
					$date = date_create();
					$date = date_format($date, 'Y-m-d H:i:s');

					$val = ($sign == '+') ? 1 : -1;
					if(!($val == -1 && $_SESSION['cart'][$_POST['pid']]['quantity'] < 1)) {
						$_SESSION['cart'][$_POST['pid']]['quantity'] += $val;
						

						try {
							$sessionData = serialize($_SESSION['cart']);
							
							$sql    = isset($_SESSION['user_session']) ? ' = :userID ': ' IS NULL ';
							$userID = isset($_SESSION['user_session']) ? $_SESSION['user_session'] : NULL;
							$hashID = session_id();
						
							$stmt = $conn->prepare("UPDATE sessions
													SET sessionData=:sessionData
													   ,lastUpdated=:lastUpdated
													WHERE UserID ". $sql ."
													AND HashID=:hashID");
													
							if($userID !== NULL) { $stmt->bindParam(':userID', $userID); }
							$stmt->bindParam(':hashID', $hashID);
							$stmt->bindParam(':sessionData', $sessionData);
							$stmt->bindParam(':lastUpdated', $date);

							//check if execution successful
							if ($stmt->execute()) { 
								$return['flag'] = true; 
							} else {
								// reverse addition of item
								$_SESSION['cart'][$_POST['pid']]['quantity'] -= $val;
								$return['flag'] = false;
							}
						} catch(PDOException $e) {
							$return['error'] = $e;
							$return['flag'] = false;
						}finally {
							$stmt=null;
						}
					}
					
					$return['newvalue'] = $_SESSION['cart'][$_POST['pid']]['quantity'];
				} else {
					$return['flag'] = false;					
				}
				break;

			case 'scrolldowncategory':
				if(isset($_POST['offset']) && !empty($_POST['offset'])
				&& isset($_POST['sort'])
				&& isset($_POST['category']) && !empty($_POST['category'])) {
					
					$offset = intval($_POST['offset']);
					$sort = $_POST['sort'];
					$category = $_POST['category'];
					
					switch($sort) {
						case 'lowestprice': $sort = ' ORDER BY p.ProductPrice ASC '; break;
						case 'highestprice': $sort = ' ORDER BY p.ProductPrice DESC '; break;
						case 'instock' : $sort = ' AND p.ProductStock > 0 '; break;
						default: $sort = '';
					}
					
					try {
						$stmt = $conn->prepare("SELECT p.ProductID
													 , p.ProductName
													 , p.ProductPrice
													 , p.ProductStock
													 , p.discount
													 , p.ProductShortDesc
													 , CONCAT(images.imageName,'.',images.Extension) as image
												FROM products p
												INNER JOIN images
												ON images.ImageID = p.ProductThumbID
												INNER JOIN productcategories category
												ON category.CategoryID = p.ProductCategoryID
												WHERE category.CategoryName=:category
												".$sort."
												LIMIT 4
												OFFSET :offset
												");
						$row = [];
						
						$stmt->bindParam(':category', $category, PDO::PARAM_STR);
						$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
						
						if($stmt->execute() && $stmt->rowCount() > 0)
						{
							while($var = $stmt->fetch(PDO::FETCH_ASSOC)) {
								$row[] = $var;
							}
						}
						$return['values'] = $row;
						$return['flag'] = true;
					} catch(PDOException $e) {
						$return['error'] = $e;
						$return['flag'] = false;
					}finally {
						$stmt=null;
					}
					
				} else {
					$return['flag'] = false;
				}
			
				break;
			case 'scrolldownhome':
				if(isset($_POST['offset']) && !empty($_POST['offset'])
				&& isset($_POST['sort'])) {
					
					$offset = intval($_POST['offset']);
					$sort = $_POST['sort'];
					
					switch($sort) {
						case 'lowestprice': $sort = ' ORDER BY p.ProductPrice ASC '; break;
						case 'highestprice': $sort = ' ORDER BY p.ProductPrice DESC '; break;
						case 'instock' : $sort = ' WHERE p.ProductStock > 0 '; break;
						default: $sort = '';
					}
					
					try {
						$stmt = $conn->prepare("SELECT p.ProductID
													 , p.ProductName
													 , p.ProductPrice
													 , p.ProductStock
													 , p.discount
													 , p.ProductShortDesc
													 , CONCAT(images.imageName,'.',images.Extension) as image
												FROM products p
												INNER JOIN images
												ON images.ImageID = p.ProductThumbID
												".$sort."
												LIMIT 4
												OFFSET :offset
												");
						$row = [];
						
						$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
						
						if($stmt->execute() && $stmt->rowCount() > 0)
						{
							while($var = $stmt->fetch(PDO::FETCH_ASSOC)) {
								$row[] = $var;
							}
						}
						$return['values'] = $row;
						$return['flag'] = true;
					} catch(PDOException $e) {
						$return['error'] = $e;
						$return['flag'] = false;
					}finally {
						$stmt=null;
					}
					
				} else {
					$return['flag'] = false;
				}

				break;
			case 'scrolldowncatalog':
				if(isset($_POST['offset']) && !empty($_POST['offset'])
				&& isset($_POST['searchTerm']) 
				&& isset($_POST['sort'])) {
					
					$offset = intval($_POST['offset']);
					$searchTerm = '%'. $_POST['searchTerm'] .'%';
					
					$sort = $_POST['sort'];
					
					switch($sort) {
						case 'lowestprice': $sort = ' ORDER BY p.ProductPrice ASC '; break;
						case 'highestprice': $sort = ' ORDER BY p.ProductPrice DESC '; break;
						case 'instock' : $sort = ' AND p.ProductStock > 0 '; break;
						default: $sort = '';
					}
					
					try {
						$stmt = $conn->prepare("SELECT p.ProductID
													 , p.ProductName
													 , p.ProductPrice
													 , p.ProductStock
													 , p.discount
													 , p.ProductShortDesc
													 , CONCAT(images.imageName,'.',images.Extension) as image
												FROM products p
												INNER JOIN images
												ON images.ImageID = p.ProductThumbID
												WHERE ProductName LIKE :productName
												".$sort."
												LIMIT 4
												OFFSET :offset
												");
						$row = [];
						
						$stmt->bindParam(':productName', $searchTerm, PDO::PARAM_STR);
						$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
						if($stmt->execute() && $stmt->rowCount() > 0)
						{
							while($var = $stmt->fetch(PDO::FETCH_ASSOC)) {
								$row[] = $var;
							}
						}
						$return['values'] = $row;
						$return['flag'] = true;
					} catch(PDOException $e) {
						$return['flag'] = false;
					}finally {
						$stmt=null;
					}
					
				} else {
					$return['flag'] = false;
				}
				break;
			case 'checkout':
			
				if(isset($_POST['email']) && !empty($_POST['email'])
				&& isset($_POST['userPhone']) && !empty($_POST['userPhone'])
				&& isset($_POST['fullName']) && !empty($_POST['fullName'])
				&& isset($_POST['address']) && !empty($_POST['address'])
				&& isset($_POST['paymentType']) && !empty($_POST['paymentType'])
				&& isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {

					try {
						
						$email = $_POST['email'];
						$userPhone = $_POST['userPhone'];
						$fullName = $_POST['fullName'];
						$address = $_POST['address'];
						$paymentType = $_POST['paymentType'];
						$hashID = session_id();
						$sessionData = serialize($_SESSION['cart']);
						$userID = isset($_SESSION['user_session']) ? $_SESSION['user_session'] : NULL;
						
						$conn->beginTransaction();
						
						$stmt = $conn->prepare("INSERT INTO orders (`OrderUserID`
																   ,`HashID`
																   ,`OrderUserName`
																   ,`OrderShipAddress`
																   ,`OrderPhone`
																   ,`OrderPaymentType`
																   ,`OrderEmail`
																   ) VALUES (:userID
																		   , :hashID
																		   , :fullName
																		   , :shipAddress
																		   , :userPhone
																		   , :paymentType
																		   , :email)");
					
						$stmt->bindParam(':userID', $userID);
						$stmt->bindParam(':hashID', $hashID);
						$stmt->bindParam(':fullName', $fullName);
						$stmt->bindParam(':shipAddress', $address);
						$stmt->bindParam(':userPhone', $userPhone);
						$stmt->bindParam(':paymentType', $paymentType);
						$stmt->bindParam(':email', $email);
						
						$stmt2 = $conn->prepare("SELECT OrderID
												  FROM  orders
												  WHERE ". (($userID === NULL) ? "OrderUserID is NULL" : "OrderUserID=:userID")."
												  AND   HashID=:hashID
												  AND   OrderFinalized=0
												  LIMIT 1");
						
						if($userID !== NULL) { $stmt2->bindParam(':userID', $userID); }
						$stmt2->bindParam(':hashID', $hashID);
						
						// 00000103221604
												  
						if ($stmt->execute() 
						 && $stmt2->execute()
						 && $row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
							 
							$orderID = $row['OrderID'];
							$finalized = ($paymentType === '') ? 1: 0;
							$cart = $_SESSION['cart'];
							$flag = true;
							$numberofitems = 0;
							foreach($cart as $productID => $array) {
								$stmt = $conn->prepare("INSERT INTO orderdetails (`DetailOrderID`
																			     ,`DetailProductID`
																			     ,`DetailName`
																			     ,`DetailPrice`
																			     ,`DetailQuantity`)
																					SELECT :orderID
																						 , ProductID
																						 , ProductName
																						 , (ProductPrice - (ProductPrice * (Discount / 100)))
																						 , :quantity
																					FROM products
																					WHERE ProductID=:productID
																					LIMIT 1");
								$stmt->bindParam(':orderID', $orderID);
								$stmt->bindParam(':quantity', $array['quantity']);
								$stmt->bindParam(':productID', $productID);
								
								$stmt3 = $conn->prepare("UPDATE products
															SET ProductStock = ProductStock - :quantity
															WHERE ProductID=:productID
															AND ProductStock >= :orderquantity");
															
								$orderquantity = $array['quantity'];
								
								$stmt3->bindParam(':productID', $productID);
								$stmt3->bindParam(':quantity', $array['quantity']);
								$stmt3->bindParam(':orderquantity', $orderquantity);
								
								$numberofitems += $array['quantity'];
								if(!$stmt->execute() || !$stmt3->execute() || !($stmt3->rowCount() > 0)){
									$flag = false;
									break;
								}
							}
							$return['row'] = $row;
							
							
							// no errors
							if($flag === true) {
								$trackingNumber = substr('000000', strlen($orderID)).$orderID.'-'.date("mdy").'-'.substr('000000', strlen($numberofitems)).$numberofitems;

								$stmt = $conn->prepare("UPDATE orders
														SET OrderAmount=
															(SELECT SUM(DetailPrice * DetailQuantity) 
																				 FROM orderdetails
																				 WHERE DetailOrderID=:orderID),
															OrderTrackingNumber=:trackingNumber
														WHERE OrderID=:orderID2
														AND ". (($userID === NULL) ? "OrderUserID is NULL" : "OrderUserID=:userID")."
														AND   HashID=:hashID
														AND   OrderFinalized=0");

								if($userID !== NULL) { $stmt->bindParam(':userID', $userID); }
								$stmt->bindParam(':trackingNumber', $trackingNumber);
								$stmt->bindParam(':orderID', $orderID);
								$stmt->bindParam(':orderID2', $orderID);
								$stmt->bindParam(':hashID', $hashID);
								
								if($stmt->execute()) {
									
									if($paymentType === "cashondeliver") {
										$stmt = $conn->prepare("UPDATE orders
																SET OrderFinalized=1
																WHERE OrderID=:orderID2
																AND ". (($userID === NULL) ? "OrderUserID is NULL" : "OrderUserID=:userID")."
																AND   HashID=:hashID
																AND   OrderFinalized=0");
										if($userID !== NULL) { $stmt->bindParam(':userID', $userID); }
										$stmt->bindParam(':orderID2', $orderID);
										$stmt->bindParam(':hashID', $hashID);
										
										$sql    = isset($_SESSION['user_session']) ? ' = :userID ': ' IS NULL ';
										$stmt2 = $conn->prepare("UPDATE sessions
																SET sessionData=''
																WHERE UserID ". $sql ."
																AND HashID=:hashID");
										if($userID !== NULL) { $stmt2->bindParam(':userID', $userID); }
										$stmt2->bindParam(':hashID', $hashID);
																				
										if($stmt->execute() && $stmt2->execute()) { 
											$conn->commit();
											unset($_SESSION['cart']);
											$return['trackingnumber'] = $trackingNumber;
											$return['flag'] = true;
											
										} else {
											$return['flag'] = false;
											$conn->rollBack();
											
										}
										
										
									} else {
										// do paypal thing
										$return['foo'] = 'Paypal not yet implemented';
										$return['flag'] = false;
										$conn->rollBack();
									}
								} else {
									$return['flag'] = false;			
									$conn->rollBack();
								}
							} else {
								$return['flag'] = false;
								$conn->rollBack();
							}
						} else {
							$conn->rollBack();
							$return['flag'] = false;
						}
					} catch(PDOException $e) {
						$conn->rollBack();
						$return['flag'] = false;
						$return['error'] = $e;
					}finally {
						$stmt=null;
					}
				}
				break;

		}
	} 
	$conn = null;
	echo json_encode($return);
?>