<?php 
	require 'dbconnect.php';
	require 'constants.php';	
	
	
	$category = '';
	
	// if there are no search parameters then relocate
	if(!isset($_GET['by'])){

	} else {
		$category = $_GET['by'];
	}
	
	require 'inc.header.php'; 
	require 'inc.menu.php';
	
?>
</div>
<?php
	require 'inc.modal.php';
?>
 <div class="main">

	<div class="content">
		<div class="content_top">
    		<div class="heading">
				<h3>Category: <?php echo $category; ?></h3>
<?php
	try {
		
		
		$stmt = $conn->prepare("SELECT count(p.ProductID) as count
									 	FROM products p
										INNER JOIN productcategories category
										ON category.CategoryID = p.ProductCategoryID
										WHERE category.CategoryName=:category
								");
		
		if($stmt->execute(array(':category' =>$category)) && $stmt->rowCount() > 0)
		{
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			echo '<p>'.$row['count'].' results found</p>';
		}
	} catch(PDOException $e) {
		$errmessage = 'An error has occurred. Please try again.';
	}finally {
		$stmt=null;
	}
?>

				
    		</div>
    		<div class="sort">
				<p>Sort by:
					<select id="category_sortselect">
						<option value="">None</option>
						<option value="instock" <?php if(isset($_GET['sort']) && $_GET['sort'] === 'instock') { echo 'selected'; } ?>>In Stock</option>
						<option value="lowestprice" <?php if(isset($_GET['sort']) && $_GET['sort'] === 'lowestprice') { echo 'selected'; } ?>>Lowest Price</option>
						<option value="highestprice" <?php if(isset($_GET['sort']) && $_GET['sort'] === 'highestprice') { echo 'selected'; } ?>>Highest Price</option>
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
					$sort = ' AND p.ProductStock > 0 ';
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
								INNER JOIN productcategories category
								ON category.CategoryID = p.ProductCategoryID
								WHERE category.CategoryName=:category
								".$sort."
								LIMIT 8
								");
		
		if($stmt->execute(array(':category' =>$category)) && $stmt->rowCount() > 0)
		{
			
			$count = 0;
			while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
				// $name = $row['ProductName'];
				$discount = floatval($row['discount']);
				$stock = intval($row['ProductStock']);
				
				if($count % 4 == 0 ) echo '<div class="images section group">';
				echo '<div class="grid_1_of_4 images_1_of_4 image_index">
					 <a href="'.BASE_URL.'/preview?id='.$row['ProductID'].'"><img class="img-display-dimensions margin-bottom-10" src="uploads/'.$row['image'].'" alt="" /></a>';
					 
				
				echo '<h2>'.$row['ProductName'].'</h2> ';
				
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
<?php include 'inc.footer.php'; ?>
