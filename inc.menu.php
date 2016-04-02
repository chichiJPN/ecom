<body>
  <div class="wrap">
	<div class="header">
		<div class="header_top">
			<div class="logo">
				<a href="<?php echo BASE_URL; ?>/home"><img src="<?php echo BASE_URL; ?>/images/logo.png" alt="" /></a>
			</div>
			  <div class="header_top_right">
			    <div class="search_box">
				    <form action="<?php echo BASE_URL; ?>/catalog" method="get">
				    	<input type="text" name="search" value="<?php if(isset($_GET['search'])) { echo $_GET['search']; } ?>" placeholder="Search for Products">
						<input type="submit" value="SEARCH">
				    </form>
			    </div>
				<div class="shopping_cart" onclick="window.location.href='<?php echo BASE_URL; ?>/cart'">
					<div class="cart">
						<a href="javascript:void(0)" title="View my shopping cart" rel="nofollow">
							<strong class="opencart"> </strong>
							<span class="cart_title">Cart</span>
							<?php
								if(isset($_SESSION['cart'])) {
									$count = 0;
									foreach($_SESSION['cart'] as $value) {
										$count += $value['quantity'];
									}
									echo '<span id="product_count" class="has_product">('.$count.' items)</span>';
								}
								else {
									echo '<span id="product_count" class="no_product">(0 items)</span>';
								}
							
							?>
							
						</a>
					</div>
				 </div>
					<?php
					if($user->is_loggedin()) {
						echo '<div class="setting">
								<span><a><img src="'.BASE_URL.'/images/setting.png" alt="" title="settings"/></a></span>
								<div id="myDropdown" class="dropdown-content">
									<a href="'.BASE_URL.'/customer/accountinfo">My Account</a>
									<a href="'.BASE_URL.'/customer/orders">My orders</a>
									<a href="'.BASE_URL.'/logout">Log out</a>
								</div>
							 </div>';
					   
					} else {
					   echo '<div class="login">
							  <span><a href="login"><img src="images/login.png" alt="" title="login"/></a></span>
							 </div>';
					}
					?>

			<div class="clear"></div>
	 </div>
	 <div class="clear"></div>
 </div>
	<div class="menu">
	  <ul id="dc_mega-menu-orange" class="dc_mm-orange">
		 <li><a href="<?php echo BASE_URL; ?>/home">Home</a></li>
    <li><a href="<?php echo BASE_URL; ?>/home">Products</a>
    <ul>

	  <li><a href="<?php echo BASE_URL; ?>/categorize?by=Laptop">Laptop</a></li>
	  <li><a href="<?php echo BASE_URL; ?>/categorize?by=Mobile+Phone">Mobile Phone</a></li>
	  <li><a href="<?php echo BASE_URL; ?>/categorize?by=Desktop+PC">Desktop PC</a></li>
	  <li><a href="<?php echo BASE_URL; ?>/categorize?by=Software">Software</a></li>
	  <li><a href="<?php echo BASE_URL; ?>/categorize?by=Hardware">Hardware</a></li>
	  <li><a href="<?php echo BASE_URL; ?>/categorize?by=Accessories">Accessories</a></li>
	</ul>

  </li>
  <li><a href="<?php echo BASE_URL; ?>/about">About</a></li>
   <li><a href="<?php echo BASE_URL; ?>/delivery">Delivery</a></li>
  <li><a href="<?php echo BASE_URL; ?>/faq">FAQS</a></li>
  <li><a href="<?php echo BASE_URL; ?>/contact">Contact</a> </li>
  <?php
  	if( isset($_SESSION['type']) && !empty($_SESSION['type']) && $_SESSION['type'] === '2') {
		echo '<li><a href="'.BASE_URL.'/admin/admin">Admin</a> </li>';
	}
  ?>
  <div class="clear"></div>
</ul>
</div>