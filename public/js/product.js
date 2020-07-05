var Product = function () {
	var formPageActions  = function() {
		// on page load call this function..
		fetch_product();

		$(document).on('change', '.fetch_product', function(event) {
			fetch_product();
		});

		// alert(fetch_product);
        /* Begin: funtion to calculate actual selling amount after user enters customer item_discount & wifyee commission */
        function fetch_product() {
	    	var currency_str 	 = $('#currency_code').val();
	    	var currency_data 	 = currency_str.split("|");
	    	var currency_code 	 = currency_data[0];
	    	var currency_symbol  = currency_data[1];
	    	var product_category = $('#product_category').val();
	    	var sorting_by 		 = $('#sorting_by').val();
	    	var sorting_order 	 = $('#sorting_order').val();
			$.ajax({
				url: '/fetch_product',
				type: 'POST',
                dataType: 'json',
				data: {'currency_code': currency_code,'product_category': product_category,'sorting_by': sorting_by,'sorting_order': sorting_order},
			})
			.done(function(result) {
			    if (result.success) 
			    {
			        var data_res = '';
			        $.each(result.product, function( key, value ) {
			        	var price = (result.per_rupee_amt * value.product_prize).toFixed(2);

			        	data_res +='<div class="block text-center">'
			        	    +'<p class="my-3 prod-name">'+ value.product_name +'</p>' 
			        	    +'<img class="image" src="../../../images/'+value.product_image+'">'
			        	    +'<div class="price">'
			        	        +'<h6 class="mb-0">'+ currency_symbol + price +'</h6>'
			        	    +'</div>'
			        	+'</div>';
			        });
                	$("#product_div").html('');
                	$("#product_div").html(data_res);
			    }
			    else if (!result.success)
			    {
			    	alert(result.msg);
			    }
			})
        }
	}
	return {
        handleFormPage: function () {
        	formPageActions();
        }
	};

}();