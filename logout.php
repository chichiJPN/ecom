<?php
	require 'dbconnect.php';
	require 'constants.php';
	
	session_regenerate_id();
	session_unset(); 
	session_destroy(); 
	
	require 'inc.header.php'; 
	require 'inc.menu.php';
?>

 </div>
 
  <div class="main">
    <div class="content">
    	<div class="section group">
			<div class="col_1_of_3 span_1_of_3">
			</div>
			<div class="col_1_of_3 span_1_of_3">
				<h3>You have logged out</h3>
				<h3>Thanks for using Smart Store! :)</h3>
			</div>
			<div class="col_1_of_3 span_1_of_3">
			</div>
		</div>
			
 </div>
</div>
<?php include 'inc.footer.php'; ?>