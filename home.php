<?php 
	require 'dbconnect.php';
	require 'constants.php';
	// $date = date_create();
    // echo date_format($date, 'Y-m-d H:i:s');
	require 'inc.header.php'; 
	require 'inc.menu.php';
	require 'inc.header.bottom.php'; 
	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';
?>
</div>
<?php
	require 'inc.modal.php';
?>

 <div class="main">
    <div class="content">
    	<div class="content_top">
    		<div class="heading">
    		<h3>Feature Products</h3>
    		</div>

    		<div class="clear"></div>
    	</div>
<?php
	try {
		$stmt = $conn->prepare("SELECT count(*) as count
									 , MIN(p.ProductID) as min
									 , MAX(p.ProductID) as max
								FROM products p
								WHERE Featured=1
								AND ProductLive=1
								");
		
		if($stmt->execute() && $stmt->rowCount() > 0)
		{
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			$min = intval($row['min']);
			$max = intval($row['max']);
			$count = intval($row['count']);
			$rand = rand($min, $max - 5);
		}
		
		$sqlrand = '';
		if($count > 10) {
			$sqlrand = "p.ProductID > :rand AND ";
		}
		
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
								WHERE ".$sqlrand." 
								Featured=1
								AND ProductLive=1
								LIMIT 4
								");
								
		if($count > 10) {
			$stmt->bindParam(':rand', $rand, PDO::PARAM_INT);
		}
		
		if($stmt->execute() && $stmt->rowCount() > 0)
		{
			$count = 0;
			while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
				// $name = $row['ProductName'];
				$discount = floatval($row['discount']);
				$stock = intval($row['ProductStock']);
				
				if($count % 4 == 0 ) echo '<div class="images section group">';
				echo '<div class="grid_1_of_4 images_1_of_4 image_index">
						<a href="'.BASE_URL.'/preview?id='.$row['ProductID'].'">
							<img class="img-display-dimensions margin-bottom-10" src="uploads/'.$row['image'].'" alt="" />
						</a>
						<h2>'.$row['ProductName'].'</h2>';
				
				if($stock > 0) {
					echo '<div class="stock"> <p>'.$stock.' items in stock</p></div>';
				} else {
					echo '<div class="stock outOfStock"> <p>Out of Stock</p></div>';
				}
				
				if($discount !== 0.0) { 
					echo '<div class="discount">
							<span class="percentage">'.$discount.'%</span>
						 </div>
						 <p>
							<span class="strike">$'.$row['ProductPrice'].'</span>
							<span class="price">$'.round(($row['ProductPrice'] - $row['ProductPrice'] * ($discount /100.0)),2).'</span>
						</p>'; 
				} else {
					echo '<p>
							<span class="price margin-left-0">$'.$row['ProductPrice'].'</span>
						  </p>'; 
				}
				
				echo '<div class="button">
						<span>
							<img src="images/cart.jpg" alt="" />
								<a href="javascript:void(0)" data-type="addToCart" data-productid="'.$row['ProductID'].'" class="cart-button addcartbtn">Add to Cart</a>
						</span>
					  </div>
						<div class="button">
							<span>
								<a href="'.BASE_URL.'/preview?id='.$row['ProductID'].'" class="details">Details</a>
							</span>
						</div>
					</div>';

				if($count % 4 == 3 && $count > 0) { echo '</div>'; };
				$count++;
			}
			$count--;
			if($count % 4 != 3 && $count != -1 ) echo '</div>';
				
			
		}		
	} catch(PDOException $e) {
		$errmessage = 'An error has occurred. Please try again.';
	}finally {
		$stmt=null;
	}
?>
			<div class="content_bottom">
    		<div class="heading">
    		<h3>Products</h3>
    		</div>
    		<div class="sort">
				<p>Sort by:
					<select id="home_sortselect">
						<option value="">None</option>
						<option value="instock" <?php if(isset($_GET['sort']) && $_GET['sort'] === 'instock') { echo 'selected'; } ?>>In Stock</option>
						<option value="lowestprice" <?php if(isset($_GET['sort']) && $_GET['sort'] === 'lowestprice') { echo 'selected'; } ?>>Lowest Price</option>
						<option value="highestprice" <?php if(isset($_GET['sort']) && $_GET['sort'] === 'highestprice') { echo 'selected'; }?>>Highest Price</option>
					</select>
				</p>
    		</div>
    		<div class="clear"></div>
    	</div>
		<div id="catalog">
<?php
	try {
		$sort = '';
		
		if(isset($_GET['sort'])) {
			switch($_GET['sort']) {
				case 'lowestprice':
					$sort = ' ORDER BY p.ProductPrice ASC ';
					break;
				case 'highestprice':
					$sort = ' ORDER BY p.ProductPrice DESC ';
					break;
				case 'instock':
					$sort = ' WHERE p.ProductStock > 0 ';
					break;
			}
		}
			
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
								LIMIT 8");
		
		if($stmt->execute() && $stmt->rowCount() > 0)
		{
			
			$count = 0;
			while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
				// $name = $row['ProductName'];
				$discount = floatval($row['discount']);
				$stock = intval($row['ProductStock']);
				
				if($count % 4 == 0 ) echo '<div class="images section group">';
				echo '<div class="grid_1_of_4 images_1_of_4 image_index">
					 <a href="'.BASE_URL.'/preview?id='.$row['ProductID'].'"><img class="img-display-dimensions margin-bottom-10" src="uploads/'.$row['image'].'" alt="" /></a>';
					 
				
				echo '<h2>'.$row['ProductName'].'</h2>';
				
				if($stock > 0) {
					echo '<div class="stock"> <p>'.$stock.' items in stock</p></div>';
				} else {
					echo '<div class="stock outOfStock"> <p>Out of Stock</p></div>';
				}
				
				if($discount !== 0.0) { 
					echo '<div class="discount">
						 <span class="percentage">'.$discount.'%</span>
						 </div>';
					echo '<p><span class="strike">$'.$row['ProductPrice'].'</span><span class="price">$'.($row['ProductPrice'] - $row['ProductPrice'] * ($discount /100.0)).'</span></p>'; 
				} else {
					echo '<p><span class="price margin-left-0">$'.$row['ProductPrice'].'</span></p>'; 
				}
				
				echo '<div class="button"><span><img src="images/cart.jpg" alt="" /><a href="javascript:void(0)" data-type="addToCart" data-productid="'.$row['ProductID'].'" class="cart-button addcartbtn">Add to Cart</a></span> </div>
				     <div class="button"><span><a href="'.BASE_URL.'/preview?id='.$row['ProductID'].'" class="details">Details</a></span></div>
				</div>';

				if($count % 4 == 3 && $count > 0) { echo '</div>'; };
				$count++;
			}
			$count--;
			if($count % 4 != 3 && $count != -1 ) echo '</div>';
		}
	} catch(PDOException $e) {
		$errmessage = 'An error has occurred. Please try again.';
	}finally {
		$stmt=null;
	}
?>		
	</div>
    </div>
 </div>
</div>
<?php include 'inc.footer.php'; ?>