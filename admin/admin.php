<?php 
	require '../dbconnect.php';
	require '../constants.php';
	require 'inc.admin.session.check.php';
	$errmessage = '';
	
	// echo '<pre>';
	// print_r($_POST);
	// echo '</pre>';
	if(isset($_POST['productName']) && !empty($_POST['productName']) 
		&& isset($_POST['productPrice']) && !empty($_POST['productPrice']) 
	&& isset($_POST['productCartDescription']) && !empty($_POST['productCartDescription']) 
	&& isset($_POST['productLongDescription']) && !empty($_POST['productLongDescription'])
	&& isset($_POST['productShortDescription']) && !empty($_POST['productShortDescription']) 
	&& isset($_POST['productStock']) && !empty($_POST['productStock']) 
	&& isset($_POST['productCategory']) && !empty($_POST['productCategory'])) 
	{
		$name = $_POST['productName'];
		$price = $_POST['productPrice'];
		$cartDesc = $_POST['productCartDescription'];
		$longDesc = $_POST['productLongDescription'];
		$shortDesc = $_POST['productShortDescription'];
		$category = $_POST['productCategory'];
		$stock = $_POST['productStock'];
		
		try {
			$stmt = $conn->prepare("INSERT INTO products (ProductName
														, ProductPrice
														, ProductCartDesc
														, ProductShortDesc
														, ProductLongDesc
														, ProductCategoryID
														, ProductStock)
												VALUES (:name
													  , :price
													  , :cartDesc
													  , :longDesc
													  , :shortDesc
													  , :category
													  , :stock)");
			
			if ($stmt->execute(array(':name' => $name
									,':price' => $price
									,':cartDesc' => $cartDesc
									,':longDesc' => $longDesc
									,':shortDesc' => $shortDesc
									,':category' => $category
									,':stock' => $stock))) {
				
			} else {
				$errmessage = 'An error has occurred. Pleasse try again.';
			}
		} catch(PDOException $e) {
			$errmessage = 'An error has occurred. Please try again.';
		}finally {
			$stmt=null;
		}
	}
	
/*
    [productName] => name
    [productPrice] => 111
    [productCartDescription] => cart
    [productLongDescription] => long
    [productShortDescription] => short
    [productStock] => 2
    [productCategory] => 1	
*/	

	require '../inc.header.php'; 
	require '../inc.menu.php';
?>

 </div>
 <div class="main">
    <div class="content font-segoe">
	
		<div class="col_1_of_4 span_1_of_4 sidebarborder">
			<div class="margin-bottom-10">
				<a href="<?php echo BASE_URL; ?>/admin/admin_orders?order=processing"><button class="btn btn-default">Orders page</button></a>
			</div>
			<div class="borders customer_sidebar padding-top-10">
				<h2 id="sidebar_header" class="padding-bottom-10">Recent updates</h2>
				<ul>
<?php
		
		try {
			$stmt = $conn->prepare("SELECT ProductName
										 , ProductID
									FROM products 
									ORDER BY ProductUpdateDate desc
									LIMIT 20
									");
			
			if($stmt->execute() && $stmt->rowCount() > 0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
					echo '<li><a href="'.BASE_URL.'/admin/edit?id='.$row['ProductID'].'">'.$row['ProductName'].'</a></li>';
				}
			}
		} catch(PDOException $e) {
			$errmessage = 'An error has occurred. Please try again.';
		}finally {
			$stmt=null;
		}

?>					
				</ul>
			</div>

		</div>
			
		<div class="col_3_of_4 span_3_of_4">
			<?php
				require 'admin_header.php';
			?>
					
			<div class="customer_header padding-bottom-20">
				<h2>Admin Panel</h2>
			</div>
			<form action="admin" method="post" enctype="multipart/form-data">
				<table class="admin-padding">
					<tbody>
						<tr>
							<td><p>Product Name:</p></td>
							<td><input class="form-control" type="text" name="productName" required></td>
						</tr>
						<tr>
							<td><p>Product Price:</p></td>
							<td><input class="form-control" type="number" name="productPrice" step="any" min="0" required></td>
						</tr>
						<tr>
							<td><p>Product Cart Description:</p></td>
							<td><textarea class="form-control" rows="4" cols="50" name="productCartDescription" required></textarea></td>
						</tr>
						<tr>
							<td><p>Product Long Description:</p></td>
							<td><textarea class="form-control" rows="4" cols="50" name="productLongDescription" required></textarea></td>
						</tr>
						<tr>
							<td><p>Product Short Description:</p></td>
							<td><textarea class="form-control" rows="4" cols="50" name="productShortDescription" required></textarea></td>
						</tr>
						<tr>
							<td><p>Product Stock:</p></td>
							<td><input class="form-control" type="number" name="productStock" required></td>
						</tr>
						<tr>
							<td><p>Category:</p></td>
							<td>
							<select class="form-control" name="productCategory">
								<option value="NULL">None</option>
								<?php 
									try {
									  $stmt = $conn->prepare("SELECT * FROM productcategories");
									  $stmt->execute();
									  if($stmt->rowCount() > 0)
									  {
										while($userRow=$stmt->fetch(PDO::FETCH_ASSOC)) {
											echo '<option value="'.$userRow['CategoryID'].'">'.$userRow['CategoryName'].'</option>';
										}
									  } else {
										$errmessage .= '<br>No categories found';
									  }
									}
									catch(PDOException $e)
									{
										$errmessage .= '<br>An error has occurred';
									} finally {
										$stmt=null;
									}
								?>
							</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input class="btn btn-default float-right" type="submit" value="Add Product" name="submit">
							</td>
						</tr>
					</tbody>
					
				</table>
			</form>
			<pre><?php echo $errmessage; ?></pre>

		</div>


		
		<script>
		  $("input").change(function(e) {

				if(!e.target.files || !window.FileReader) return;

				
				var files = e.target.files;
				var filesArr = Array.prototype.slice.call(files);
				for(var x = 0 ;x < filesArr.length; x++) {
					var f = files[x];
					console.log(f);
					if(!f.type.match("image.*")) {
						return;
					}

					var reader = new FileReader();
					reader.onload = function (e) {
						var html = "<img src=\"" + e.target.result + "\">";
						$("#images").append(html);

						// selDiv.innerHTML += html;
					}
					reader.readAsDataURL(f); 
				}
				
			});
		</script>
		<div id="images">
		</div>
      <div class="clear"></div>
    </div>
 </div>
</div>

<?php include '../inc.footer.php'; ?>
