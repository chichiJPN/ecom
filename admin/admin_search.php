<?php 
	require '../dbconnect.php';
	require '../constants.php';
	require 'inc.admin.session.check.php';
	
	// put checker if get ID exists
	if(!(isset($_POST['searchTerm']))) {
		header('Location: '.BASE_URL.'/admin/admin');
		die();
	} 
	
	$searchTerm = $_POST['searchTerm'];
	
	
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
	<?php
		require 'admin_header.php';
	?>
	
	<p class="padding-bottom-10">List of products related to "<?php echo $searchTerm; ?>":</p>
		<ul class="search-ul">
<?php
	
	if(is_numeric($searchTerm)) {
		$searchTerm = '%'.$searchTerm.'%';
		try {
		
			$stmt = $conn->prepare("SELECT ProductID, ProductName
									FROM products
									WHERE ProductID LIKE :searchTerm");
			$stmt->bindParam(':searchTerm', $searchTerm);
									
			if ($stmt->execute()) {
				while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
					echo '<li><a href="'.BASE_URL.'/admin/edit?id='.$row['ProductID'].'">'.$row['ProductName'].'</a></li>';
				}
			}
		} catch(PDOException $e) {
		}finally {
			$stmt=null;
		}
		
	} else {
		$searchTerm = '%'.$searchTerm.'%';
		try {
		
			$stmt = $conn->prepare("SELECT ProductID, ProductName
									FROM products
									WHERE ProductName LIKE :searchTerm");
			$stmt->bindParam(':searchTerm', $searchTerm);
									
			if ($stmt->execute()) {
				while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
					echo '<li><a href="'.BASE_URL.'/admin/edit?id='.$row['ProductID'].'">'.$row['ProductName'].'</a></li>';
				}
			}
		} catch(PDOException $e) {
		}finally {
			$stmt=null;
		}
		
		
	}

?>
		</ul>
      <div class="clear"></div>
    </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/admin.js"></script>

<?php include '../inc.footer.php'; ?>
