<?php 
	require '../dbconnect.php';
	require '../constants.php';
	require 'inc.admin.session.check.php';
	
	
	// put checker if get ID exists
	if(!(isset($_GET['id']) && !empty($_GET['id']))) {
		header('Location: '.BASE_URL.'/admin/admin');
		die();
	}
	
	$errmessage = '';
	$errpmessage = '';
	
	$name = '';
	$price = '';
	$cartDesc = '';
	$longDesc = '';
	$shortDesc = '';
	$category = '';
	$stock = '';
	$live = '';
	$featured = '';
	$discount = '';
	$id = '';
	
	$images  = [];
		// checks if user wants to make a picture a thumbail
		if(isset($_POST['btnthumbnail']) && isset($_POST['delete']) && !empty($_POST['delete']) && count($_POST['delete']) === 1) {
			$imageID = $_POST['delete'][0];
			$id = $_GET['id'];
			try {
				$stmt = $conn->prepare("UPDATE products 
										   SET ProductThumbID=:imageID
										   WHERE ProductID=:productID");
											
				$stmt->execute(array(':imageID' => $imageID,':productID' => $id));
				if ($stmt->rowCount()) {
					$errpmessage = 'Image has been made into thumbnail';
				} else {
					$errpmessage = 'An error has occurred. Pleasse try again.';
				}
			} catch(PDOException $e) {
				$errmessage = 'An error has occurred. Please try again.';
			}finally {
				$stmt=null;
			}
			
			
			echo 'id is  '.$imageID;
		}
		
		// checks if user wants to delete images
		if(isset($_POST['btndelete']) && isset($_POST['delete']) && !empty($_POST['delete'])) {
		
		$imageIDs = $_POST['delete'];
		try {
			
			foreach($imageIDs as $imageID) {
				
				$stmt = $conn->prepare("SELECT CONCAT(ImageName,'.',Extension) as image
										FROM images
										WHERE ImageID=:imageID
										LIMIT 1
										");
				
				if($stmt->execute(array(':imageID'=>$imageID)) && $stmt->rowCount() > 0)
				{
					$row=$stmt->fetch(PDO::FETCH_ASSOC);
					$imageFile = $row['image'];
					
					
					$stmt = $conn->prepare("DELETE FROM images
										WHERE ProductID = :productID 
										AND ImageID=:imageID");
					
					$stmt->execute(array('productID' => $_GET['id']
										,'imageID'  => $imageID));
					
					if ($stmt->execute(array('productID' => $_GET['id']
											,'imageID'  => $imageID)) 
										&& @unlink('../uploads/'.$imageFile)) {	
						$errpmessage = 'Delete Successful';
					} else {
						$errpmessage = 'An error has occurred. Please try again.';
						break;
					}
				
					
					
				} else {
					$errpmessage = 'An error has occurred';
					break;
				}
							
				
			}
		} catch(PDOException $e) {
			$errmessage = 'An error has occurred. Please try again.';
		}finally {
			$stmt=null;
		}
	}

	// echo '<pre>';
	// print_r($_POST);
	// echo '</pre>';
	
	// checks if user wants to save information data
	if(isset($_POST['productName']) && !empty($_POST['productName']) 
	&& isset($_POST['productPrice']) && !empty($_POST['productPrice']) 
	&& isset($_POST['productCartDescription']) && !empty($_POST['productCartDescription']) 
	&& isset($_POST['productLongDescription']) && !empty($_POST['productLongDescription'])
	&& isset($_POST['productShortDescription']) && !empty($_POST['productShortDescription']) 
	&& isset($_POST['productStock']) && !empty($_POST['productStock']) 
	&& isset($_POST['productCategory']) && !empty($_POST['productCategory'])
	&& isset($_POST['productLive'])
	&& isset($_POST['featured'])
	&& isset($_POST['discount'])
	) 
	{
		$id= $_GET['id'];
		$name = $_POST['productName'];
		$price = $_POST['productPrice'];
		$cartDesc = $_POST['productCartDescription'];
		$longDesc = $_POST['productLongDescription'];
		$shortDesc = $_POST['productShortDescription'];
		$category = $_POST['productCategory'];
		$stock = $_POST['productStock'];
		$live = $_POST['productLive'];
		$featured = $_POST['featured'];
		$discount = $_POST['discount'];
		try {
			
			$stmt = $conn->prepare("UPDATE products 
										SET ProductName=:name
										  , ProductPrice=:price
										  , ProductCartDesc=:cartDesc
										  , ProductShortDesc=:shortDesc
										  , ProductLongDesc=:longDesc
										  , ProductCategoryID=:category
										  , ProductStock=:stock
										  , ProductLive=:live
										  , Featured=:featured
										  , Discount=:discount
										WHERE ProductID=:productID");
			
			$stmt->execute(array(':name' => $name
								,':price' => $price
								,':cartDesc' => $cartDesc
								,':longDesc' => $longDesc
								,':shortDesc' => $shortDesc
								,':category' => $category
								,':stock' => $stock
								,':live' => $live
								,':featured' => $featured
								,':discount' => $discount
								,':productID' => $id));

			if ($stmt->rowCount()) {
				$errmessage = 'Update Successful';
			} else {
				$errmessage = 'An error has occurred. Pleasse try again.';
			}
		} catch(PDOException $e) {
			$errmessage = 'An error has occurred. Please try again.';
		}finally {
			$stmt=null;
		}
	}
	
		
	// checks if user wants to save images
	if(isset($_FILES["fileToUpload"]) && !empty($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["name"][0] != '') {
		$id = $_GET['id'];
		$target_dir = "../uploads/";
		
		// loops x number of times depending on number of images loaded
		echo count($_FILES["fileToUpload"]["name"]);
		for($x = 0, $length = count($_FILES["fileToUpload"]["name"]) ; $x < $length ; $x++) {
			
			try {
				
				
				$stmt = $conn->prepare("SELECT  (
											SELECT MAX(imageID)
											FROM `images`
											) AS imageID,
										(
											SELECT COUNT(ProductID)
											FROM images
											WHERE ProductID=:productID
										) AS productImageCount");
				
				if($stmt->execute(array(':productID'=>$id)) && $stmt->rowCount() > 0)
				{
					$row=$stmt->fetch(PDO::FETCH_ASSOC);
					$imageID = $row['imageID']; //largest image ID
					$imageCount = intval($row['productImageCount']) + 1; // number of images the product has
					
					$extension = end(explode(".", $_FILES["fileToUpload"]["name"][$x])); 
					$fileName = $imageID.'_'.$imageCount.'_'.$id; // filename without extension
					$newFileName = $imageID.'_'.$imageCount.'_'.$id.'.'.$extension;
			
					$target_file = $target_dir . basename($newFileName);
					$uploadOk = 1;
					$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
					
					// Check if image file is a actual image or fake image
					$check = getimagesize($_FILES["fileToUpload"]["tmp_name"][$x]);
					
					if($check === false) {
						$errpmessage =  "File ".$_FILES["fileToUpload"]["name"][$x]." is not an image.<br>";
						$uploadOk = 0;
					}

					// Check if file already exists
					else if (file_exists($target_file)) {
						echo $target_file;
						$errpmessage =  "Sorry, file ".$_FILES["fileToUpload"]["name"][$x]." already exists.<br>";
						$uploadOk = 0;
					}
					// Check file size
					else if ($_FILES["fileToUpload"]["size"][$x] > 500000) {
						$errpmessage = "Sorry, your file ".$_FILES["fileToUpload"]["name"][$x]." is too large.<br>";
						$uploadOk = 0;
					}
					// Allow certain file formats
					else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
						$errpmessage = "Sorry,  ".$_FILES["fileToUpload"]["name"][$x]." needs to be of JPG, JPEG, PNG or GIF.<br>";
						$uploadOk = 0;
					}
					
					// Check if $uploadOk is set to 0 by an error
					if ($uploadOk == 0) {
						$errpmessage .= "Sorry, your file  ".$_FILES["fileToUpload"]["name"][$x]." was not uploaded.<br>";
					// if everything is ok, try to upload file
					} else {
						$stmt2 = $conn->prepare("INSERT into images (ProductID
											,ImageName
											,Extension)
									VALUES(:productID
										  ,:imageName
										  ,:extension)");
						if($stmt2->execute(array(':productID'=>$id, ':imageName'=>$fileName, ':extension'=>$extension)) && $stmt2->rowCount() > 0)
						{
							if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$x], $target_dir.$newFileName)) {	
								$errpmessage .= "The file ". basename( $_FILES["fileToUpload"]["name"][$x]). " has been uploaded.<br>";
							} else {
								$errpmessage .= "Sorry, there was an error uploading your file ".$_FILES["fileToUpload"]["name"][$x].".<br>";
							}
						} else {
								$errpmessage .= "Sorry, there was an error uploading your file ".$_FILES["fileToUpload"]["name"][$x].".<br>";
						}
					}
				}
			} catch(PDOException $e) {
				$errpmessage .= 'An error has occurred while uploading '.$_FILES["fileToUpload"]["name"][$x].'. Please try again.';
			}finally {
				$stmt=null;
				$stmt2=null;
			}		
		}
	}

	
	// Retrieves product data from db using product ID
	if(isset($_GET['id']) && !empty($_GET['id'])) {
		$id = $_GET['id'];
		try {
			$stmt = $conn->prepare("SELECT *
									FROM products 
									WHERE ProductID=:productID
									LIMIT 1
									");
			
			if($stmt->execute(array(':productID'=>$id)) && $stmt->rowCount() > 0)
			{
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
				// print_r($row);
				$name = $row['ProductName'];
				$price = $row['ProductPrice'];
				$cartDesc = $row['ProductCartDesc'];
				$longDesc = $row['ProductLongDesc'];
				$shortDesc = $row['ProductShortDesc'];
				$category = $row['ProductCategoryID'];
				$stock = $row['ProductStock'];
				$live = $row['ProductLive'];
				$featured = $row['Featured'];
				$discount = $row['Discount'];
			}
			
			$stmt = $conn->prepare("SELECT CONCAT(ImageName, '.', Extension) as image
										  ,ImageID
									FROM images
									WHERE ProductID=:productID
									");
			if($stmt->execute(array(':productID'=>$id)) && $stmt->rowCount() > 0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
					echo $row['image'];
					$images[] = $row;
				}
			}
			
		} catch(PDOException $e) {
			$errmessage = 'An error has occurred. Please try again.';
		}finally {
			$stmt=null;
		}
	}
	
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
		<div class="admin-edit border-bottom margin-bottom-10 padding-bottom-10">
			
			<form action="<?php echo BASE_URL; ?>/admin/edit.php?id=<?php echo $_GET['id']; ?>" method="post">
				<table class="admin-padding">
					<thead>
						<tr>
							<th></th>
							<th class="padding-bottom-20"><p class="font-weight-600">Product Details:</p></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><p>Product Name:</p></td>
							<td><input class="form-control" type="text" name="productName" value="<?php echo $name; ?>" required></td>
						</tr>
						<tr>
							<td><p>Product Price:</p></td>
							<td><input class="form-control" type="number" step="any" min="0" name="productPrice" value="<?php echo $price; ?>" required></td>
						</tr>
						<tr>
							<td><p>Product Cart Description:</p></td>
							<td><textarea class="form-control" rows="4" cols="50" name="productCartDescription" required><?php echo $cartDesc; ?></textarea></td>
						</tr>
						<tr>
							<td><p>Product Long Description:</p></td>
							<td><textarea class="form-control" rows="4" cols="50" name="productLongDescription" value="" required><?php echo $longDesc; ?></textarea></td>
						</tr>
						<tr>
							<td><p>Product Short Description:</p></td>
							<td><textarea class="form-control" rows="4" cols="50" name="productShortDescription" value="" required><?php echo $shortDesc; ?></textarea></td>
						</tr>
						<tr>
							<td><p>Product Stock:</p></td>
							<td><input class="form-control" type="number" name="productStock" value="<?php echo $stock; ?>" required></td>
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
											if($category == $userRow['CategoryID']) {
												echo '<option value="'.$userRow['CategoryID'].'" selected>'.$userRow['CategoryName'].'</option>';											
											} else {
												echo '<option value="'.$userRow['CategoryID'].'">'.$userRow['CategoryName'].'</option>';
											}
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
							<td><p>Live:</p></td>
							<td>
								<select class="form-control" name="productLive">
									<option value="0">No</option>
									<option value="1" <?php if($live == "1") { echo 'selected'; }?>>Yes</option>
								</select>
							</td>
						</tr>
						<tr>
							<td><p>Featured:</p></td>
							<td>
								<select class="form-control" name="featured">
									<option value="0">No</option>
									<option value="1" <?php if($featured == "1") { echo 'selected'; }?>>Yes</option>
								</select>
							</td>
						</tr>
						<tr>
							<td><p>Discount( IN percent ):</p></td>
							<td>
								<input class="form-control" type="number" name="discount" value="<?php echo $discount; ?>" required>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input class="btn btn-default" type="submit" value="Save" name="submit">
							</td>
						</tr>

					</tbody>
					
				</table>
				<pre><?php echo $errmessage; ?></pre>
			</form>
		</div>
		
		<div class="border-bottom margin-bottom-10 padding-bottom-10">
			<form action="<?php echo BASE_URL; ?>/admin/edit.php?id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data">
				<table class="admin-padding">
					<tbody>
						<tr>
							<td><p>Select image/s to upload:</p></td>
							<td><input class="btn btn-default" type="file" name="fileToUpload[]" id="fileToUpload" multiple></td>
							<td><input class="btn btn-default" type="submit" value="Upload Images" name="submit"></td>
						</tr>
					</tbody>
				<table>
				<div id="images">
				</div>

			</form>
			<br>
			<pre><?php echo $errpmessage; ?></pre>
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
	
		<div class="padding-top-10 border-top">
			<h3 class="font-weight-600">Images of Product:</h3>
		</div>
		<form action="<?php echo BASE_URL; ?>/admin/edit.php?id=<?php echo $_GET['id']; ?>" method="post">
			<?php
				$count = 0;
				foreach($images as $value) {
					if($count % 4 == 0 ) echo '<div class="images section group">';
					
						echo '<div class="grid_1_of_4 images_1_of_4 image_edit">
							<div class="btn_close" data-id="'.$value['ImageID'].'" ><input type="checkbox" name="delete[]" value="'.$value['ImageID'].'"></div>
							 <img class="img-display-dimensions" src="'.BASE_URL.'\\uploads\\'.$value['image'].'">
							 </div>';
					
					if($count % 4 == 3 && $count > 0) { echo '</div>'; };
					$count++;
				}
				
				$count--;
				if($count % 4 != 3 && $count != -1 ) echo '</div>';
				
			?>
			<div class="section_footer">
				
				<input class="details" name="btnthumbnail" type="submit" value="Set Thumbnail">				
				<input class="details" name="btndelete" type="submit" value="Delete Image/s">
			</div>
		</form>
			
			
		<script>
			$(window).load(function(){
				$(".image_edit").click(function() {
					console.log('i was asdasd');
					var checkBoxes = $(this).find("input[name=delete\\[\\]]");
					checkBoxes.prop("checked", !checkBoxes.prop("checked"));
				});
			});
		</script>
		
		<div class="padding-top-10 border-top padding-bottom-20">
			<p>To delete, please click <button id="productdelete" class="btn btn-default" type="button" name="delete" data-productid="<?php echo $id; ?>" value="">Here</button></p>
		</div>

      <div class="clear"></div>
    </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/admin.js"></script>

<?php include '../inc.footer.php'; ?>
