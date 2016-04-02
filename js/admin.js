$(window).load(function(){
	modal.start();

	
	$("#productdelete").click(function() {
		
		var productid=$(this).data('productid');
		if(confirm('Are you sure want to delete this product?')) {
			modal.display('loading');
			$.ajax({
				type:'POST',
				url: 'admin_ajax_functions',
				dataType: 'json',
				data: 'productid='+ productid + '&type=delete'
				
				,
				success:
				function(data) {
					console.log(data);
					console.log(data.error);
					if(data.flag === true) {
						
						modal.display('removed');
						
						setTimeout(function(){ 
							window.location = window.location.origin + '/' + window.location.pathname.substr(1).split('/')[0] + '/admin/admin';
						},3000);
					} else {
						console.log('its false');
					}
					runflag = false;
				},
				error: function(data) {
					modal.display('error');
				}
			});
		}
	});
	
	$(".cancel_order").click(function() {
		var orderid = $(this).data('orderid')
		   ,data = {orderid:orderid};
		modal.display('cancelorder',data);
	});
	
	$(".order_details").click(function() {
		console.log('orderdetails was clicked');
		var orderid = $(this).data('orderid')
		
		modal.display('loading');
		$.ajax({
			type:'POST',
			url: 'admin_ajax_functions',
			dataType: 'json',
			data: 'orderid='+ orderid + '&type=orderdetails'
			,
			success:
			function(data) {
				console.log(data);
				if(data.flag === true) {
					
					modal.display('orderdetails', data.data);
					
				} else {
					console.log('its false');
				}
				runflag = false;
			},
			error: function(data) {
				modal.display('none');
			}
		});
	});
	
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
			case 'removed':
				this.modalheader.hide();
				this.modalcontainer.width(400);
				
				modal.body(
					'Item has been removed<br>'+
					'You will now be redirected shortly...'
				);
				this.loadingGIF.hide();
				this.modalcontent.show();
				this.modal.show();
				break;
			case 'orderdetails':
			
				this.loadingGIF.hide();
				this.modalheader.show();
				this.modalcontainer.width(800);
				var orders = data,
					html = '';
				
				html += '<table class="table text-align-center borders order_details_table">' +
								'<tr>'+
									'<td class="padding-bottom-20 font-weight-600">Product Name</td>'+
									'<td class="font-weight-600">Price at order</td>'+
									'<td class="font-weight-600">Items ordered	</td>'+
									'<td class="font-weight-600"></td>'+
								'</tr>';
							
				for(var x = 0, length = orders.length ; x < length; x++) {
					html += '<tr>';
					html += '<td>' + orders[x].DetailName + '</td>';
					html += '<td>$' + orders[x].DetailPrice + '</td>';
					html += '<td>' + orders[x].DetailQuantity + '</td>';
					html += '<td><a href="../preview?id='+ orders[x].DetailProductID+'">Preview Item</a></td>';
					html += '</tr>';
				}
				
				html += '</table>';
				
				modal.body(html);
				this.modalcontent.show();
				this.modal.show();
				
				break;
			case 'cancelorder':
				var orderid = data.orderid;
				this.modalheader.show();
				this.modalcontainer.width(400);
				modal.body(
					'<form action="admin_orders?order=processing" method="POST">'+
						'<h4 class="margin-bottom-10">Are you sure you want to cancel this order?</h4>'+
						'<input type="hidden" name="type" value="cancel">'+
						'<input type="hidden" name="orderid" value="'+ orderid +'">'+
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
				case 'error':
				this.modalheader.show();
				this.modalcontainer.width(400);
				modal.body(
					'An error has occured<br>'+
					'Please try again.'
				);
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
