<?php 
	require 'dbconnect.php';
	require 'constants.php';
	require 'inc.header.php'; 
	require 'inc.menu.php';
	
	$productID = '';
	$productName = '';
	$productPrice = '';
	$productShortDesc = '';
	$productLongDesc = '';
	$productImage = '';
	$productCategory = '';
	$productStock = '';
	$discount = 0;
	$images = [];
	
	if(isset($_GET['id']) && !empty($_GET['id'])) {
		$productID = $_GET['id'];
		try {
			$stmt = $conn->prepare("SELECT p.ProductID
										 , category.CategoryName
										 , p.ProductName
										 , p.ProductPrice
										 , p.ProductStock
										 , p.ProductShortDesc
										 , p.ProductLongDesc
										 , p.Discount
										 , CONCAT(images.imageName,'.',images.Extension) as image
									FROM products p
									INNER JOIN images
									ON images.ImageID = p.ProductThumbID
									INNER JOIN productcategories category
									ON category.CategoryID = p.ProductCategoryID
									WHERE p.productID=:productID
									LIMIT 1");
			
			if($stmt->execute(array(':productID'=>$productID)) && $stmt->rowCount() > 0)
			{
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
				$productID = $row['ProductID'];
				$productName = $row['ProductName'];
				$productPrice = intval($row['ProductPrice']);
				$productShortDesc = $row['ProductShortDesc'];
				$productLongDesc = $row['ProductLongDesc'];
				$productImage = $row['image'];
				$productCategory = $row['CategoryName'];
				$productStock = $row['ProductStock'];
				$discount = $row['Discount'];
				
				
				$stmt = $conn->prepare("SELECT CONCAT(images.imageName,'.',images.Extension) as image
										FROM images
										WHERE productID=:productID");
				
				if($stmt->execute(array(':productID'=>$productID)) && $stmt->rowCount() > 0)
				{
					while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
						$images[] = $row;
					}
				} else {
					$errmessage = 'An error has occurred';
				}
			} else {
				$errmessage = 'An error has occurred';
			}
		}
		catch(PDOException $e)
		{
			$errmessage = 'An error has occurred';
		} finally {
			$stmt=null;
		}
	} else {
		header('Location: '.BASE_URL.'/home');
		die();
	}

?>
</div>
<?php
	require 'inc.modal.php';
?>
 <div class="main">
    <div class="content">
	    	<div class="content_top">
    		<div class="back-links">
    		<p><a href="index.html">Home</a> >> <a href="#"><?php echo $productCategory; ?></a></p>
    	    </div>
    		<div class="clear"></div>
    	</div>

		<div class="section group">
			<div class="cont-desc span_1_of_2">				
				<div class="grid images_3_of_2">
					<img id="preview-image" src="<?php echo BASE_URL.'/uploads/'.$productImage; ?>" alt="" />
				</div>
				<div class="desc span_3_of_2">
					<h2><?php echo $productName; ?></h2>
					<p><?php echo $productShortDesc; ?></p>					
					<div class="price">
						<?php
							if($discount != 0) {
								echo '<p>Price: <span class="strike">$'.$productPrice.'</span>   <span class="price">$'.($productPrice - $productPrice * ($discount /100.0)).'</span></p>';
							} else {
								echo '<p>Price: <span>$'.$productPrice.'</span></p>';
							}
						?>
					</div>
					<div class="available">
						<p>Available Options :</p>
					<ul>
						<li>Color:
							<select>
							<option>Silver</option>
							<option>Black</option>
							<option>Dark Black</option>
							<option>Red</option>
						</select></li>
						<li>Size:<select>
							<option>Large</option>
							<option>Medium</option>
							<option>small</option>
							<option>Large</option>
							<option>small</option>
						</select></li>
						<li>Quality:<select>
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
						</select></li>
					</ul>
					</div>

					<div class="add-cart">
						<div class="rating">
							<p>Rating:<img src="images/rating.png" alt="" /><span>[3 of 5 Stars]</span></p>
						</div>
						
						<div class="button"><span><a href="javascript:void(0)" data-type="addToCart" data-productid="<?php echo $productID; ?>" class="cart-button addcartbtn">Add to Cart</a></span></div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="div-product-pics">
					<?php
						// for($x = 0 ; $x < 10; $x++)
						foreach($images as $value) {
							$class = ($value['image'] == $productImage) ? "previewed-other-pics selected" : "previewed-other-pics";
							echo '<img class="'.$class.'" src=uploads/'.$value['image'].'>';
						}
					?>
				</div>
				<div class="product-desc">
					<h2>Product Details</h2>
					<p><?php echo $productLongDesc; ?></p>
				</div>
			</div>
			<div class="rightsidebar span_3_of_1">
				<h2>CATEGORIES</h2>
				<ul>
					<li><a href="<?php echo BASE_URL; ?>/categorize?by=Laptop">Laptop</a></li>
					<li><a href="<?php echo BASE_URL; ?>/categorize?by=Mobile+Phone">Mobile Phone</a></li>
					<li><a href="<?php echo BASE_URL; ?>/categorize?by=Desktop+PC">Desktop PC</a></li>
					<li><a href="<?php echo BASE_URL; ?>/categorize?by=Software">Software</a></li>
					<li><a href="<?php echo BASE_URL; ?>/categorize?by=Hardware">Hardware</a></li>
					<li><a href="<?php echo BASE_URL; ?>/categorize?by=Accessories">Accessories</a></li>
				</ul>
			</div>
 		</div>
     </div>
 </div>
</div>
<?php include 'inc.footer.php'; ?>