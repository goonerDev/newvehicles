jQuery(document).ready(function($){
	//=======================================================================================
	// After adding/deleting an item to/from cart refresh the latter to display the new total in the basket
	update_cart_amount=function(){
		$.ajax(host+'cart/total/ajax/json',{dataType:'json',type:'POST',data:{origin:'checkout'},success:function(y){
			if(y.total=='&#163;&nbsp;0.00'){
				$('#cart-dropdown-container').removeClass('bg-success');
				y.total = 'Empty';
			}
			else
				$('#cart-dropdown-container').addClass('bg-success');
			$('span#cart-total').html(y.total);
		}})
	};
	//=======================================================================================
	update_total_price = function(o) {
		var line = o.id.substr(6), // The ID is in the format « input-<item_id> »
		qty = parseFloat(o.value),
		pu = parseFloat($('input[id=pu-'+line+']').val()),
		tt = (qty * pu).toFixed(2);
		$.ajax(host+'cart/update_qty',{dataType:'json',async:false,data:{id:line,qty:qty,origin:'checkout'},type:'POST',success:function(x){
			if(x && x.err) {
				alert(x.err);
				o.value=x.qty
			}
			else {
				$('input[id=tt-'+line+']').val(tt);
				update_cart_amount();
			}
		}})
	};
})