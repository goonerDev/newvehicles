jQuery(document).ready(function($){
	var country=$('[name=country]'), postcode=$('[name=postcode]'), sub_total_shipping_rate=$('#sub-total-shipping-rate'),sub_total_shipping_msg=$('#sub-total-shipping-msg'),total_msg=$('#cart-checkout-form #total');

	function update_client_total_value(){
		var t=0;
		$('#cart-checkout-form div[id^=tt-]').each(function(o,i){
			t=t*1+parseFloat($(i).html());
		});
		var shipping=sub_total_shipping_rate.html();
		if(!isNaN(shipping))
			t=(t*1+shipping*1).toFixed(2);

		var s=t+'',i=s.indexOf('.');
		if(i==-1)
			s=s+'.00';
		else if(s.length-i-1<2)
			s=s+'0';
			
		total_msg.html(s);
	}
	update_client_total_value();

	function get_flate_shipping_rate(){
		var postcode_val=postcode.val(),
		country_val=country.val();

		$.ajax(host+'cart/get_shipment_flate_rate', {type:'POST',dataType:'json',data:{origin:'checkout',country:country_val, postcode:postcode_val},
		success:function(x){
			if(x&&(x.err==0||x.err==1)){
				if(x.err==0) {
					var ship_rate=x.msg.replace('&#163;&nbsp;','');
					sub_total_shipping_rate.html(ship_rate==0?'':ship_rate);
					sub_total_shipping_msg.html('Shipping').removeClass('alert alert-danger');
					update_client_total_value();
				}
				if(x.err==1){
					sub_total_shipping_rate.html('');
					sub_total_shipping_msg.html(x.msg).addClass('alert alert-danger');
					no_delivery=1;
					update_client_total_value();
				}
			}

			if(country_val=='United Kingdom'&&postcode_val==''){
				sub_total_shipping_rate.html('<span style="font-weight:700;color:#fc0202">postcode required</span>');
			}

			update_cart_amount();
		}
		});
	}

	country.on('change', get_flate_shipping_rate);
	postcode.on('keyup', function(e){if(e.which!=13)get_flate_shipping_rate()});

	$('[name=firstname],[name=lastname],[name=email],[name=telephone],[name=address],[name=town],[name=postcode]').on('blur',function(){
		var val=this.value,
		field=this.name;
		if(field=='firstname'||field=='lastname'){
			field='recipient';
			val=$('[name=firstname]').val()+' '+$('[name=lastname]').val()
		}
		$.ajax(host+'cart/save_userdetails',{
			type:'POST',
			data:{origin:'checkout',key:field,val:val},
			complete:function(x){
				console.log(x.responseText)
			}
		})
	});

	var email_re=/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i;
	$('[name=email]').on('blur',function(){
		if(!email_re.test(this.value)){
			//alert('Your email address is not-well formed')
			this.value=''
		}
	});

	// var tel_re=/^[\d\s]+$/;
	// $('[name=telephone]').on('blur',function(){
		// if(!tel_re.test(this.value)){
			// alert('Your telephone has invalid characters');
			// this.value=''
		// }
	// });

	$('form :input').on('keypress', function(e){
		if(e.which==13){
			e.preventDefault();
			var tab_index=$(this).data('tab-index'), next=$('[data-tab-index='+(tab_index+1)+']');
			if(next.length>0)
				next.focus()
		}
	});

	$('#cart-checkout-form input[id^=input-]')
	.on('keypress',function(e){
		var c=e.which;
		return 48<=c && c<=57
	})
	.on('keyup', function(e){
		var id=this.id.substr(6), v=$('#pu-'+id).html()*this.value;
		$('#tt-'+id).html(v.toFixed(2));
		// update_client_total_value();
		$.ajax(host+'cart/update_qty',{type:'POST',data:{origin:'checkout',id:id,qty:this.value},success:function(x){
			get_flate_shipping_rate();
			update_cart_amount();

			// update the paypal instant update form
			checkout_btn_img.prop('src',host+'assets/nvp/images/icon_animated_prog_dkgy_42wx42h.gif')
			$.ajax(host+'cart/payment_form/Paypal%20instant%20update/raw',{type:'post',data:{origin:'checkout'},
				success:function(x){
					var new_action=$(x).prop('action');
					if(new_action)
						$('form[name=pp_payment_form]').prop('action',new_action);
					checkout_btn_img.prop('src',host+'assets/nvp/images/pp-express-checkout.png')
				}
			})
		}})
	})
	//=======================================================================================
	$('form[name=checkout_form]').on('submit', function(){
		$('#loading-modal').modal('show');
	});
	$('iframe[name=checkout_target]').on('load', function(){
		var x=this.contentDocument.activeElement.innerText||this.contentDocument.activeElement.textContent;
		x=eval('('+x+')');
		if(x.err==1){
			$('#loading-modal').modal('hide');
			$('#cart-checkout-form #alert-msg').addClass('alert alert-danger').html(x.msg);
		}
		else{
			$('body').append(x.msg);
//                        alert($('[name=payment_form]').serialize());
			$('[name=payment_form]').submit()
		}
	});
	//=======================================================================================
	var checkout_btn=$('#pp-express-checkout-btn');
	checkout_btn.click(function(e){
		e.preventDefault();
		$.ajax(host+'cart/save_payment_method',{type:'post',data:{origin:'checkout',pm:'Paypal instant update'},success:function(){$('form[name=pp_payment_form]').submit()}});
	})
	//=======================================================================================
	// When the customer updates the qty of an item, modify the total according to
	var checkout_btn_img=$('img',checkout_btn);
	$(document).on('keydown', 'table#items input[id^=input-]', function(e){
		if(e.which!=13)
			return;

		// checkout_btn_img.prop('src',host+'assets/nvp/images/icon_animated_prog_dkgy_42wx42h.gif')
		// update price
		// update_total_price(this);
		// update the paypal instant update form
		// $.ajax(host+'cart/payment_form/Paypal%20instant%20update/raw',{type:'post',data:{origin:'checkout'},
			// success:function(x){
				// var new_action=$(x).prop('action');
				// if(new_action)
					// $('form[name=pp_payment_form]').prop('action',new_action);
				// checkout_btn_img.prop('src',host+'assets/nvp/images/pp-express-checkout.png')
			// }
		// })
	})
})