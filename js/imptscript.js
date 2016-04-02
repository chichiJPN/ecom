$(window).load(function(){

	modal.start();
	
	var tokens = window.location.href.split("/"),
		type = tokens[tokens.length - 2],
		site = (function() {
			var site = tokens[tokens.length - 1]
			  , index = tokens[tokens.length - 1].indexOf('?');
			 
			return (index == -1) ? site : site.substr(0, index);
		})(),
		listnum = (function() { switch(site) {
				case 'accountinfo': return 0;
				case 'address': return 1;
				case 'orders': return 2;
			}
		})(),
		runflag = false,
		offset = 8;
	
	console.log(site);
	console.log(type);
	
	switch(type) {
		case 'customer': // user is in 'my account' panel
			$(".customer_sidebar ul").children().eq(listnum).addClass('selected');
			break;
		default:
			switch(site) {
				case 'checkout':
					console.log('im in here');
					$("#form_payment").submit(function() {
						console.log($(this).serialize() + '&type=checkout');
						$.ajax({
							type:'POST',
							url: 'ajax_functions',
							dataType: 'json',
							data: $(this).serialize() + '&type=checkout'
							,
							success:
							function(data) {
								console.log(data);
								
								if(data.flag === true) {
									var form = $('<form style="display:none;"action="checkout" method="post">' +
									  '<input type="hidden" name="trackingnumber" value="'+data.trackingnumber+'" />'+
									  '<input type="hidden" name="part" value="three" />' +
									  '</form>');
									$('body').append(form);
									form.submit();
								} else {
									modal.display('checkoutfail');
								}
								runflag = false;
							},
							error: function(data) {
								console.log(data);
							}
						});
						return false;
					});
				
					break;
				case 'categorize':
					var	sort = '';
						category = '';
    				
						location.search
							.substr(1)
							.split("&")
							.forEach(function (item) {
								tmp = item.split("=");
								
								switch(tmp[0]) {
									case 'sort': sort = decodeURIComponent(tmp[1]); break;
									case 'by': category = decodeURIComponent(tmp[1]); break;
								}
							});
						
						$(window).scroll(function() {
							if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
								if(runflag === false) {
									runflag = true;
									console.log("category!");
									console.log(sort);
									console.log(category);
									
									$.ajax({
										type:'POST',
										url: 'ajax_functions',
										dataType: 'json',
										data: {
											type: 'scrolldowncategory',
											offset: offset,
											sort : sort,
											category: category
										},
										success:
										function(data) {
											console.log(data);
											
											if(data.flag === true) {
												
												var catalog = document.getElementById('catalog')
												  , row = create.row();
												for(var x = 0, length = data.values.length ; x < length; x++) {
													var item = create.item(data.values[x]);
													row.appendChild(item);
													offset++;
												}
												catalog.appendChild(row);
											}
											else {
												console.log('its false');
											}
											runflag = false;
										},
										error: function(data) {
											console.log(data);
										}
									});
								}
							}
						});
						
						$('#category_sortselect').change(function() {
							window.location = window.location.origin + '/' + window.location.pathname.substr(1).split('/')[0] + '/categorize?by='+category+'&sort=' + this.value;
						});
				
					break;
				case 'home':
					var	sort = '';
    				
						location.search
							.substr(1)
							.split("&")
							.forEach(function (item) {
								tmp = item.split("=");
								
								switch(tmp[0]) {
									case 'sort': sort = decodeURIComponent(tmp[1]); break;
								}
							});
						
						$(window).scroll(function() {
							if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
								if(runflag === false) {
									runflag = true;
									console.log("home!");

									$.ajax({
										type:'POST',
										url: 'ajax_functions',
										dataType: 'json',
										data: {
											type: 'scrolldownhome',
											offset: offset,
											sort : sort
										},
										success:
										function(data) {
											console.log(data);
											
											if(data.flag === true) {
												
												var catalog = document.getElementById('catalog')
												  , row = create.row();
												for(var x = 0, length = data.values.length ; x < length; x++) {
													var item = create.item(data.values[x]);
													row.appendChild(item);
													offset++;
												}
												catalog.appendChild(row);
											}
											else {
												console.log('its false');
											}
											runflag = false;
										},
										error: function(data) {
											console.log(data);
										}
									});
								}
							}
						});
						
						$('#home_sortselect').change(function() {
							window.location = window.location.origin + '/' + window.location.pathname.substr(1).split('/')[0] + '/home?sort=' + this.value;
						});
						break;
				case 'catalog':
					var	searchTerm = '',
						sort = '';
    				
						location.search
							.substr(1)
							.split("&")
							.forEach(function (item) {
								tmp = item.split("=");
								
								switch(tmp[0]) {
									case 'search': searchTerm = decodeURIComponent(tmp[1]); break;
									case 'sort': sort = decodeURIComponent(tmp[1]); break;
								}
							});
						
						console.log(sort + '  ' + searchTerm);
						$(window).scroll(function() {
							if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
								if(runflag === false) {
									runflag = true;
									console.log("catalog!");
									$.ajax({
										type:'POST',
										url: 'ajax_functions',
										dataType: 'json',
										data: {
											type: 'scrolldowncatalog',
											offset: offset,
											searchTerm: searchTerm,
											sort : sort
										},
										success:
										function(data) {
											console.log(data);
											
											if(data.flag === true) {
												
												var catalog = document.getElementById('catalog')
												  , row = create.row();
												for(var x = 0, length = data.values.length ; x < length; x++) {
													var item = create.item(data.values[x]);
													row.appendChild(item);
													offset++;
												}
												catalog.appendChild(row);
											}
											else {
												console.log('its false');
											}
											runflag = false;
										},
										error: function(data) {
											console.log(data);
										}
									});
								}
							}
						});
						
						$('#catalog_sortselect').change(function() {
							window.location = window.location.origin + '/' + window.location.pathname.substr(1).split('/')[0] + '/catalog?sort=' + this.value + '&search='+ searchTerm;
						});
						
					break;
			}
			break;
	}


	
	// adds toggle to dropdown
	$('.setting').bind('click', function(event) {
		document.getElementById("myDropdown").classList.toggle("showDD");
	}).children().bind("click",function(event){
		document.getElementById("myDropdown").classList.toggle("showDD");
		event.stopPropagation(); // if you don't want event to bubble up
	});
	
	
	$(".div-product-pics .previewed-other-pics").click(function() {
		var previewImage = document.getElementById('preview-image');
		previewImage.src = this.src;
		
		$(this).parent().children().attr('class','previewed-other-pics');
		$(this).attr('class','previewed-other-pics selected');
		
	});

	// add quantity to what is in cart
	$(".cart_left .arrow").click(function() {
		var el = $(this);

		if(!el.hasClass('disabled')) {
			$.ajax({
				type:'POST',
				url: 'ajax_functions',
				dataType: 'json',
				data: {
					type: 'cart_addminus',
					pid: $(this).data('productid'),
					sign: $(this).data('sign')
				},
				success:
				function(data) {
					// console.log(data);
					
					var grandparent = el.parent().parent(),
						increment = 0.0;
					if(data.flag === true) {
						// console.log('its true');
						var productCount = $("#product_count")
						  , numItems = parseInt(productCount.html().match(/(\d+)/)[0])
						  , el_fullPrice = $("#fullPrice")
						  , fullPrice = parseFloat(el_fullPrice.text().substr(1, el_fullPrice.text().length))
						  , el_totalPrice = $("#totalPrice")
						  , totalPrice = parseFloat(el_totalPrice.text().substr(1, el_totalPrice.text().length))
						  , productPrice = parseFloat(grandparent.data('price'));

						
						if(el.data('sign') == '-') {
							increment = -1;
							if(parseInt(data.newvalue) == 1) {
								grandparent.children().eq(0).find('div').addClass('disabled');
							}
						} else {
							grandparent.children().eq(0).find('div').removeClass('disabled');
							increment = 1;
						}
						
						// console.log(fullPrice);
						// console.log(totalPrice);
						productCount.removeClass()
									   .addClass('has_product')
									   .html('('+ ( numItems + increment ) +' items)');
						
						
						el_fullPrice.text('$'+ (fullPrice + (productPrice * increment)).toFixed(2) );
						el_totalPrice.text('$'+ (totalPrice + (productPrice * increment)).toFixed(2) );
						grandparent.find(".quantity_text").text(data.newvalue);
						// console.log('price is ' + productPrice);
					} else {
						console.log('its false');
						
					}
						// console.log($(this).parent());
					
					
				},
				error: function(data) {
					console.log(data);
				}
			});
		}
	});
	

	
	$(".cart_left .btn_remove").click(function() {
		
		console.log('i was clicked');
		var data = {productid : $(this).data('productid')};
		
		modal.display('removefromcart',data);
	});
	
	// when add to cart button is clicked	
	$(".addcartbtn").click(handler.addtocart);
});




var modal = {
	start: function() {
		this.modal = $("#myModal");
		this.modalheader = $("#modal-head");
		this.modalcontainer = $("#modal-container");
		this.loadingGIF = $("#modal-loading");
		this.modalcontent = $("#modal-content");
		$(".close").click(function() {
			modal.display('none');
		});
		
	},

	header: function() {},
	body: function(content) {
		this.modalcontent.html(content);
	},
	display: function(div,data) {
		switch(div){
			case 'loading':
				this.modalheader.hide();
				this.modalcontainer.width(75);
				this.loadingGIF.show();
				this.modalcontent.hide();
				this.modal.show();
				break;
			case 'addtocart':
				modal.body('Item has been added to Cart');
				this.modalcontainer.width(400);
				this.loadingGIF.hide();
				this.modalheader.show();
				this.modalcontent.show();
				this.modal.show();
				break;
			case 'removefromcart':
				var productid = data.productid;
				this.modalheader.show();
				this.modalcontainer.width(400);
				
				modal.body(
					'<form action="cart" method="POST">'+
						'<h4 class="margin-bottom-10">Are you sure you want to remove this item from cart?</h4>'+
						'<input type="hidden" name="pid" value="'+ productid +'">'+
						'<div class="height-40">'+
							'<div class="width_50 float-left height-auto text-align-center">'+
								'<button type="button" class="btn btn-default margin-auto" onclick="modal.display()">No</button>'+
							'</div>'+
							'<div class="width_50 float-left height-auto text-align-center">'+
								'<button name="remove" value="remove" type="submit" class="btn btn-default margin-auto" >Yes</button>'+
							'</div>'+
						'</div>'+
					'</form>'
				);
				this.loadingGIF.hide();
				this.modalcontent.show();
				this.modal.show();
				break;
			case 'checkoutfail':
				this.modalheader.show();
				this.modalcontainer.width(400);
				modal.body('Error validating orders.<br>'+
							'Please check if one of the items are still available.');
				this.loadingGIF.hide();
				this.modalcontent.show();
				this.modal.show();
				break;
				
			case 'none':
			default:
				this.modal.fadeOut();
		}
	}
}


// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
	
	var target = $(event.target);    
	if (!event.target.matches('.setting') || target.parents('div.setting').length) {
		var dropdowns = document.getElementsByClassName("dropdown-content");
		var i;
		for (i = 0; i < dropdowns.length; i++) {
		  var openDropdown = dropdowns[i];
		  if (openDropdown.classList.contains('showDD')) {
			openDropdown.classList.remove('showDD');
		  }
		}
	}
	
	// if (event.target == document.getElementById('myModal')) {
        // modal.display(false);
    // }
}


// validation scripts for forms
var validate = {
	loginForm: function() {
		var form = document.forms["login"],
			user = form["user"].value,
			pwd = form["pass"].value,
			regexLetters = /[^a-z0-9]/gi,
			flag = true,
			message = '\n';
		
	
		if(user.match(regexLetters)) {
			document.getElementById('luser').style.borderColor = "red"; flag = false;
			message += 'Username can only contain letters and numbers.\n';
		} else if(user === null || user === "Username" || user === "") {
			document.getElementById('luser').style.borderColor = "red"; flag = false; 
		} else {
			document.getElementById('luser').style.borderColor = "";; 
		}
		
		if (pwd === null || pwd === "" || pwd === 'Password') { 
			document.getElementById('lpass').style.borderColor = "red"; flag = false; 
		} else {
			document.getElementById('lpass').style.borderColor = ""; 
		}
		
		console.log(flag);
		document.getElementById('errMessage').innerHTML = message;
		return flag;	
	},
	registerForm: function() {
		var form = document.forms["register"],
			username = form["username"].value,
			fName = form["fname"].value,
			lName = form["lname"].value,
			email = form["email"].value,
			pwd = form["password"].value,
			rpwd = form["rpassword"].value,
			regexLetters = /[^a-z0-9]/gi,
			regexEmail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
			flag = true,
			message = '\n';
			
		if(username.match(regexLetters)) {
			document.getElementById('username').style.borderColor = "red"; flag = false;
			message += 'Username can only contain letters and numbers.\n';
		} else if (username == null || username == "" || username == "First Name") { 
			document.getElementById('username').style.borderColor = "red"; flag = false;
		} else { 
			document.getElementById('username').style.borderColor = ""; 
		}
		
		
		if (fName == null || fName == "" || fName == "First Name") { 
			document.getElementById('fName').style.borderColor = "red"; flag = false;
		} else { 
			document.getElementById('fName').style.borderColor = ""; 
		}
		
		if (lName == null || lName == "" || lName == "Last Name" ) { 
			document.getElementById('lName').style.borderColor = "red"; flag = false; 
		} else { 
			document.getElementById('lName').style.borderColor = ""; 
		}
		if(!email.match(regexEmail)) {
			document.getElementById('email').style.borderColor = "red"; flag = false; 
			message += 'Please enter a valid email.\n';
		} else if (email == null || email == "" || email == "E-Mail") {
			document.getElementById('email').style.borderColor = "red"; flag = false; 
		} else { 
			document.getElementById('email').style.borderColor = ""; 
		}
		
		if (pwd == null || pwd == "" || pwd == 'Password') { 
			document.getElementById('password').style.borderColor = "red"; flag = false; 
		} else {
			document.getElementById('password').style.borderColor = ""; 
		}
		
		if (rpwd == null || rpwd == "" || rpwd == "Password" ) {
			document.getElementById('rpassword').style.borderColor = "red"; flag = false;
		} else if(pwd != rpwd){
			message += 'Passwords do not match.\n';
			document.getElementById('rpassword').style.borderColor = "red"; flag = false;		
		} else {
			document.getElementById('rpassword').style.borderColor = ""; 
		}
		console.log(flag);
		document.getElementById('errRMessage').innerHTML = message;
		return flag;
	},
	accountinfoForm: function() {
		
		var form = document.forms["accountinfo"],
			fName = form["fName"].value,
			lName = form["lName"].value,
			mobile = form["mobile"].value,
			regexLetters = /^[a-zA-Z0-9- ]*$/,
			flag = true,
			message = '\n';
			
		if (fName == null || fName == "" || fName == "First Name") { 
			document.getElementById('fName').style.borderColor = "red"; flag = false;
			message += "No first name specified\n";
		} else { 
			document.getElementById('fName').style.borderColor = ""; 
		}
		
		if (lName == null || lName == "" || lName == "Last Name" ) { 
			document.getElementById('lName').style.borderColor = "red"; flag = false; 
			message += "No last name specified\n";
		} else { 
			document.getElementById('lName').style.borderColor = ""; 
		}
		
		if(/^\d+$/.test(mobile) || mobile === "") {
			document.getElementById('mobile').style.borderColor = ""; 
		} else {
			document.getElementById('mobile').style.borderColor = "red"; flag = false;
			message += "Mobile can only contain numbers\n";
		}
			
		document.getElementById('errmessage').innerHTML = message;
		return flag;
	},
	changeemailForm: function() {
		var form = document.forms["accountinfo"],
			fName = form["fName"].value,
			lName = form["lName"].value,
			mobile = form["mobile"].value,
			regexLetters = /^[a-zA-Z0-9- ]*$/,
			flag = true,
			message = '\n';
			
		if (fName == null || fName == "" || fName == "First Name") { 
			document.getElementById('fName').style.borderColor = "red"; flag = false;
			message += "No first name specified\n";
		} else { 
			document.getElementById('fName').style.borderColor = ""; 
		}
		
		if (lName == null || lName == "" || lName == "Last Name" ) { 
			document.getElementById('lName').style.borderColor = "red"; flag = false; 
			message += "No last name specified\n";
		} else { 
			document.getElementById('lName').style.borderColor = ""; 
		}
		
		if(/^\d+$/.test(mobile) || mobile === "") {
			document.getElementById('mobile').style.borderColor = ""; 
		} else {
			document.getElementById('mobile').style.borderColor = "red"; flag = false;
			message += "Mobile can only contain numbers\n";
		}
			
		document.getElementById('errmessage').innerHTML = message;
		return flag;
	},
	changepasswordForm: function() {
		var form = document.forms["changepassword"],
			oldpass = form["oldpass"].value,
			newpass = form["newpass"].value,
			rpass = form["rpass"].value,
			regexLetters = /^[a-zA-Z0-9- ]*$/,
			flag = true,
			message = '';
			
		if (oldpass == null || oldpass == "") { 
			document.getElementById('oldpass').style.borderColor = "red"; flag = false;
			message += "No old password specified\n";
		} else { 
			document.getElementById('oldpass').style.borderColor = ""; 
		}
		
		if (newpass == null || newpass == "" ) { 
			document.getElementById('newpass').style.borderColor = "red"; flag = false; 
			message += "No password specified\n";
		} else { 
			document.getElementById('newpass').style.borderColor = ""; 
		}
		
		console.log(rpass);
		if(rpass == null || rpass == "" ) {
			document.getElementById('rpass').style.borderColor = "red"; flag = false;
			message += "No password specified\n";
		} else if(newpass !== rpass){
			console.log('not same!');
			document.getElementById('rpass').style.borderColor = "red"; flag = false;
			document.getElementById('newpass').style.borderColor = "red";
			message += "Passwords do no match\n";
		} else { 
			document.getElementById('rpass').style.borderColor = ""; 
		}
			
		document.getElementById('errmessage').innerHTML = message;
		return flag;
	},
	paymentForm: function() {
		var form = document.forms["register"],
			fullName = form["fullName"].value,
			email = form["email"].value,
			userPhone = form["userPhone"].value,
			regexEmail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
			regexNumbers = /[^0-9]/gi,
			flag = true,
			message = '\n';
			
			if(userPhone.match(regexNumbers)) {
				message += 'Phone Number can only contain Numbers\n';
				flag = false;
			}
			
			if(!email.match(regexEmail)) {
				document.getElementById('email').style.borderColor = "red"; flag = false; 
				message += 'Please enter a valid email.\n';
			} else if (email == null || email == "" || email == "E-Mail") {
				document.getElementById('email').style.borderColor = "red"; flag = false; 
			} else {
				document.getElementById('email').style.borderColor = ""; 
			}
			
			document.getElementById('errmessage').innerHTML = message;
			return flag;
	},
	removeForm: function() {
		modal.display('removefromcart');
	}
};

var handler = {
	addtocart: function() {
		var productID = $(this).data('productid');
		
		modal.display('loading');
		$.ajax({
			type:'POST',
			url: 'ajax_functions',
			dataType: 'json',
			data: {
				type: $(this).data('type'),
				pid: $(this).data('productid')
			},
			success:
			function(data) {
				console.log(data);
					
				if(data.flag == true) {
					var productCount = $("#product_count")
					  , numItems = parseInt(productCount.html().match(/(\d+)/)[0]);
					  
					productCount.removeClass()
									   .addClass('has_product')
									   .html('('+ ( numItems + 1 ) +' items)');
					
					modal.display('addtocart');
				}
				else {
					console.log('its false');
					
				}
					// alert('Item has been added to cart');
			},
			error: function(data) {
				console.log(data);
			}
		});
	}
};

// create stuff
var create = {
	row: function() {
		var row = document.createElement('div');
		row.className = "images section group";
		return row;
	},
	item: function(data) {

		var container = document.createElement('div')
		  , a = document.createElement('a')
		  , img = document.createElement('img')
		  , h2_prodName = document.createElement('h2')
		  , p_shortDesc = document.createElement('p')
		  , divstock = document.createElement('div')
		  , pstock = document.createElement('p')
		  , pPrice = document.createElement('p')
		  , spanPrice = document.createElement('span')
		  , btnCart = document.createElement('btn')
		  , spanCart = document.createElement('span')
		  , imgCart = document.createElement('img')
		  , aCart = document.createElement('a')
		  , btnPreview = document.createElement('btn')
		  , spanPreview = document.createElement('span')
		  , aPreview = document.createElement('a');
		
		container.className = "grid_1_of_4 images_1_of_4 image_index";
		a.href = window.location.origin + '/' + window.location.pathname.substr(1).split('/')[0] + '/preview?id=' + data.ProductID;
		img.className = "img-display-dimensions margin-bottom-10";
		img.src = 'uploads/' + data.image;
		img.alt = "";
		
		a.appendChild(img);
	
		h2_prodName.appendChild(document.createTextNode(data.ProductName));
		p_shortDesc.appendChild(document.createTextNode(data.ProductShortDesc));
		
		if(parseInt(data.ProductStock) > 0) {
			pstock.appendChild(document.createTextNode(data.ProductStock + ' items in stock'));
			divstock.className = 'stock';
		} else {
			pstock.appendChild(document.createTextNode('Out of stock'));			
			divstock.className = 'stock outOfStock';
		}
		divstock.appendChild(pstock);
		
		if(parseFloat(data.discount) !== 0.0) {
			var divdiscount = document.createElement('div')
			  , spanpercent = document.createElement('span')
			  , spanStrike = document.createElement('span')
			  , productPrice = parseFloat(data.ProductPrice)
			  , discount = parseFloat(data.discount);
			  
			divdiscount.className = "discount";
			spanpercent.className = "percentage";
			
			spanpercent.appendChild(document.createTextNode(data.discount + '%'));
			divdiscount.appendChild(spanpercent);
			
			
			spanStrike.className = "strike";
			spanStrike.appendChild(document.createTextNode('$' + data.ProductPrice));
			
			spanPrice.className = "price";
			spanPrice.appendChild(document.createTextNode('$' + (productPrice - productPrice * (discount / 100.0)).toFixed(2)));
			pPrice.appendChild(spanStrike);
			pPrice.appendChild(spanPrice);
			
			container.appendChild(divdiscount);
		} else {
			spanPrice.className = "price";
			spanPrice.appendChild(document.createTextNode('$' + data.ProductPrice));
			pPrice.appendChild(spanPrice);
		}
		
		btnCart.className = 'button';
		imgCart.src = 'images/cart.jpg';
		imgCart.alt = '';
		aCart.onclick = handler.addtocart;
		aCart.href = 'javascript:void(0)';
		aCart.className = 'cart-button addcartbtn';
		aCart.setAttribute('data-type','addToCart');
		aCart.setAttribute('data-productid',data.ProductID);
		aCart.appendChild(document.createTextNode('Add to Cart'));
		spanCart.appendChild(imgCart);
		spanCart.appendChild(aCart);
		btnCart.appendChild(spanCart);
		
		btnPreview.className = 'button';
		aPreview.href = window.location.origin + '/' + window.location.pathname.substr(1).split('/')[0] + '/preview?id=' + data.ProductID;
		aPreview.className = 'details';
		aPreview.appendChild(document.createTextNode('Details'));
		spanPreview.appendChild(aPreview);
		btnPreview.appendChild(spanPreview);
		
		container.appendChild(a);
		container.appendChild(h2_prodName);
		// container.appendChild(p_shortDesc);
		container.appendChild(divstock);
		container.appendChild(pPrice);
		container.appendChild(btnCart);
		container.appendChild(btnPreview);
		return container;
	}
}

