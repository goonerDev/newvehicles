jQuery(document).ready(function($){
	$('#cart-chekout-form').accordion({heightStyle:'content'});

	function submit(f, opened_tab, callback) {
		f.load(function(e){
			var r={err:undefined};
			if(e.currentTarget.contentDocument.body.innerText!='')
				r=eval('('+e.currentTarget.contentDocument.body.innerText+')');
			if(r.err==1)
				alert(r.msg)
			else
				$('#cart-chekout-form').accordion('option', 'active', opened_tab);

			if(callback && typeof callback == 'function' )
				callback()
		})
	}

	submit($('iframe[name=cust_details_target]'), 1);
	submit($('iframe[name=shipping_rate_target]'), 2);
	submit($('iframe[name=payment_option_target]'), 3, function(){
		$.ajax(host+'cart/call_payment_method', {
			success:function(x){
				$('#confirm-order-section').html(x)
			}
		})
	});
})