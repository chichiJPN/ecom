<?php 
	require '../dbconnect.php';
	require '../constants.php';	
	require '../inc.header.php'; 
	require '../inc.menu.php';
	require '../inc.verifyuser.php';	
?>
</div>
 <div class="main">
    <div class="content">
    	<div class="section group customersection">
			<div class="col_1_of_4 span_1_of_4 sidebarborder">
				<?php include 'inc.sidebar.php'; ?>
			</div>
			
			<div class="col_3_of_4 span_3_of_4">
				<div class="customer_header">
					<h2>My orders</h2>
				</div>
				<div>
					<div class="order">
					
					SELECT  OrderID
						  , OrderAmount
						  , OrderDate
						  , FROM ORDERS WHERE OrderUserID=$_SESSION['user_session']
						asdsadada
					</div>
				</div>
			</div>
		</div>
			
 </div>
</div>
<?php include '../inc.footer.php'; ?>
