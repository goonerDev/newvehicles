jQuery(document).ready(function($){
	//=======================================================================================
	get_year_by_model = function(manu, model) {
		var year = $('#year');
		$('option', year).remove();

		if(!manu || !model){
			year.append('<option value="">Year</option>');
			year.selectpicker('refresh');
			return
		}

		$.ajax(host + 'catalog/start_end_year/' + manu + '/' + model + '/ajax/htmlselect', {dataType:'html',success:function(x){
			year.append(x);
			year.selectpicker('refresh');
			$('button', 'select#year + div.bootstrap-select').click()
		}})
	}
	//=======================================================================================
	// List all available years by model - Called after selecting a model
	$('#model').on('change', function() {
		get_year_by_model($('#manufacture').val(), this.value);
	});
	//=======================================================================================
	// When a manufacture is selected display available models
	$('#manufacture').on('change', function() {
		var manu=this.value;

		var model = $('#model');
		$('option', model).remove();

		if(!manu){
			model.selectpicker('refresh');
			get_year_by_model('', '');
			return;
		}

		$.ajax(host + 'catalog/models/' + manu + '/ajax/htmlselect', {dataType:'html',success:function(x){
			var year = $('#year');
			x='<option value="">Model</option>' + x;
			model.append(x);
			model.selectpicker('refresh');
			$('button','select#model + div.bootstrap-select').click();

			get_year_by_model('', '')
		}});
	});
	//=======================================================================================
	// After adding an item to cart refresh the latter to display the new total in the basket
	update_cart_amount=function(cart_id){
		$.ajax(host+'cart/total',{data:{cart_id:cart_id},dataType:'json',type:'POST',success:function(y){
			$('span#cart-total').html(y.total)
		}})
	};
	//=======================================================================================
	update_quick_access_cart_items=function(o){
		var that=o?o:$('#cart-dropdown-container');
		$.ajax(host+'cart/items',{
			success:function(x){
			$('.dropdown-menu',that).html(x);
		}});
	};
	// Click on "Cart" nav link displays list of items within a popup. So the user can quickly delete
	// items
	$('#cart-dropdown-container').on('show.bs.dropdown', function(){
		update_quick_access_cart_items(this)
	});
	//=======================================================================================
	// Delete an item from cart by quick access
	$(document).on('click','#cart-dropdown-container a.mini-cart-delete', function(e){
		e.preventDefault();

		$.ajax(this.href,{
			data:{ajax:1},
			success:function(){
				update_quick_access_cart_items();
				update_cart_amount(cookie_get('_c'));
		}});

		return false;
	});
	//=======================================================================================
	// Click on "Add to cart" button. The main role is to add the item to cart
	$(document).on('click', 'a.add-to-cart,a.add-to-cart-cwws,a.add-to-cart-item-quote', function(e){
		e.preventDefault();

		var u = this.href,
		that = $(this),
		o = that.clone(),
		from = that.offset(),
		to = $('#cart').offset(),
		cart_id = cookie_get('_c');

		o.css('position','absolute').css('left',from.left).css('top',from.top).css('opacity',.5);
		$('body').append(o);

		// Update the server
		$.ajax(u, {dataType: 'json', data:{ cart_id: cart_id, vrm: cookie_get('_vrm')}, type:'POST', success:function(x){
			if(x.err)
				alert(x.err);
			else {
				cart_id = x.cart_id;
				cookie_set( '_c', cart_id );
				update_cart_amount(cart_id);
				// Update the ui according to the new qty
				if(x.ui){
					that.parents('div[id^=add-to-cart-]').html(x.ui)
				}
			}
		}});

		// Animation displaying the cart button moving to cart
		o.animate({top:to.top,left:to.left,width:30,height:30}, 1000,function(){o.remove();})
		return false
	});
	//=======================================================================================
	update_total_price = function(o) {
		var line = o.id.substr(6), // The ID is in the format « input-<item_id> »
		qty = parseFloat(o.value),
		pu = parseFloat($('input[id=pu-'+line+']').val()),
		tt = (qty * pu).toFixed(2);
		$.ajax(host+'cart/update_qty',{dataType:'json',data:{id:line,qty:qty},type:'POST',success:function(x){
			if(x && x.err) {
				alert(x.err);
				o.value=x.qty
			}
			else {
				$('input[id=tt-'+line+']').val(tt);
				update_cart_amount(cookie_get('_c'));
			}
		}})
	};
	//=======================================================================================
	// In the checkout page when the customer updates the qty of an item, modify the total according to
	$(document).on('keydown', 'table#items input[id^=input-]', function(e){
		if(e.which!=13)
			return;

		// update price
		update_total_price(this)
	})
	//=======================================================================================
	$(document).on('click','table#items a[id^=update-qty-]', function(e){
		e.preventDefault();

		if(this.id){
			var id=this.id.substr(11);
			update_total_price(document.getElementById('input-'+id))
		}

		return false
	});
	//=======================================================================================
	// Submit log in
	signin = function() {
		var p = $('#password');
		$.ajax(host + 'login/index', {type:'POST', data:{email:$('#email').val(), password:p.val()}, dataType:'json', success:function(x){
			var n = x._er.length, t;
			if( n > 0) {
				t = '<ul>';
				for(var i=0; i < n; i++)
					t+= '<li>' + x._er[ i ] + '</li>';
				t+= '</ul>';
				$('#login-error-msg-wrapper').html( t );
				p.val('');
			}
			else {
				cookie_delete( '_c' );
				cookie_set('_s',x._s);
				// cookie_set('_a',x._a);
				window.location.href = x.referer
			}
		}/*,complete:function(x){console.log(x);}*/})
	};
	//=======================================================================================
	// Submit log in upon ENTER keystroke in email or password field
	$('#email, #password').on('keydown', function(e) {
		if(e.which == 13)
			signin()
	});
	//=======================================================================================
	// Forget password btn clicked
	$('#login-forget-password-btn').on('click', function() {
		$('#signing-modal').modal('hide');
	});
	recover_password=function(){
		var v=$('#forgot-password-modal #email').val();
		if(!v||v=='')
			alert('Provide your email address');
		else
			$.ajax(host+'forgot-password',{
				type:'POST',
				data:{email:v},
				success:function(x){
					alert(x)
				}
			});
		return false
	};
	$('#forgot-password-form').on('submit', function(){
		return recover_password();
	});
	$('#recover-password-btn').on('click', function() {
		recover_password();
	});
	// Submit log in upon click on "Sign in" button
	$('#login-connect-btn').on('click', function() {
		signin()
	});
	//=======================================================================================
	$('#log-out-btn').on('click', function(e) {
		e.preventDefault();
		e.target = e.currentTarget || e.srcElement;
		if( e.target && e.target.href ) {
			$.ajax(e.target.href, {success:function(x){
				cookie_delete( '_c' );
				cookie_delete( '_s' );
				// cookie_delete( '_a' );
				window.location.href = host
			}})
		}
		return false
	});
	//=======================================================================================
	// Change all elements that have selectpicker class into dropdown combobox
	$('.selectpicker').selectpicker();
	//=======================================================================================
	// Launch a quick search
	$('#quick-search-form').on('submit', function(e) {
		e.preventDefault();

		var manu=$('#manufacture').val(),
			model=$('#model').val(),
			year=$('#year').val();
		if(manu=='' || model=='' || year=='')
			return false;
		
		window.location.href = host + 'quick-search/' + manu + '/' + model + '/' + year;
	})
	//=======================================================================================
	Array.prototype.indexOfText=function(t){
		var n=this.length-1;
		while(n>=0){
			if(t&&this[n].text&&this[n].text.toLowerCase()==t.toLowerCase())
				return n;
			n--
		}
		return -1
	};
	// Launch simple search
	$('input#simple-search').typeahead({minLength:3},
	{
		valueKey:'url',
		displayKey:'text',
		source:function(qry, cb){
			$.ajax(host + 'search/process_free_search/q-' + encodeURIComponent(qry) + '/json',{
				dataType:'json',
				success:function(x){
					var b=[],i=0,o;
					if(x){
						while(true){
							o=x[i];
							if(!o)
								break;
							if(b.indexOfText(o.text)==-1)
								b.push(o);
							i++
						}
						cb(b)
					}
				}/*,
				complete:function(x){console.log(x)}*/
			})
		},
		indexOf:function(t){
			var i=b.length-1;
			while(i>=0){
				if(b[i].text.toLowerCase()==t.toLowerCase())
					return true;
				i--;
			}
			return false
		}
	}).on('typeahead:selected',function(e,o){
		window.location.href = o.url
	});
	//=======================================================================================
	// Launch a search by vrm
	$('form#search-by-vrm').on('submit', function(e) {
		e.preventDefault();
		window.location.href = host + 'search/process_vrm/q-' + encodeURIComponent(this.q.value);
		
		return false
	});
	//=======================================================================================
	// Discard the cart
	$(document).on('click', 'a#cart-discard-trigger', function(e) {
		e.preventDefault();

		var that = this;

		$.ajax(that.href, {success:function(x){
			if(x == '0') {
				cookie_delete( '_c' );
				window.location.href = host + 'checkout'
			}
		}});
	});
	//=======================================================================================
	// Confirm the order
	$('button#place-the-order-btn').bind('click', function(e){
		this.disabled = 'disabled';
		$.ajax(host + 'process-order',{
		type:'POST',
		success:function(x){
			cookie_delete( '_c' );
			window.location.href = host + 'thank-you/checkout'
		}/*, complete:function(x){console.log(x.responseText)}*/})
	});
	//=======================================================================================
	$(document).on('click','#export-submit-btn',function(e){
		e.preventDefault();

		var f = window.export_form;

		if($('input[name=export_output]:checked').val()=='download') {
			f.target='_blank';
			f.submit();
			return true
		}

		f.target='exporter';
		f.submit();
		// Display loading animation
		$('#loading-modal').modal();
		return true;
	})
	//=======================================================================================
	$('input#contact-send-msg-btn,input#sign-up-btn').click(function(){
		$('#loading-modal').modal();
	});
	//=======================================================================================
	// Upload a document by ajax
	// Alert on error else redirect
	$('iframe#exporter,iframe#ajax_poster').bind('load', function(e){
		$('#loading-modal').modal('hide');
		var r=eval('('+this.contentDocument.activeElement.innerHTML+')');
		if(r.err)
			alert(r.err)
		else if(r.redirect)
			window.location.href=r.redirect
	})
	//=======================================================================================
	// Prevent loading the whole page upon click on a link.
	// Instead load it in a defined target
	$(document).on('click', 'div.ajax-loader-container a', function(e){
		e.preventDefault();

		$('#loading-modal').modal();
		$('#'+$($(this).parents('div')[0]).data('target')).load(this.href, function(){
			$('#loading-modal').modal('hide');
		})
	});
	//=======================================================================================
	// The next functions are related to admin vrm report
	update_vrm_report_display=function(){
		var from = encodeURIComponent($('#vrmrep_date_from').val()),
		to = encodeURIComponent($('#vrmrep_date_to').val()),
		email = encodeURIComponent($('#vrmrep_email').val()),
		order_by = $('#vrmrep_order_by').val(),
		dir = $('#vrmrep_order_dir').val();

		if(!from || from == '')
			from = '-';
		if(!to || to == '')
			to = '-';
		if(!email || email == '')
			email = '-';
		if(!order_by || order_by == '')
			order_by = '-';
		if(!dir || dir == '')
			dir = '-';

		window.location.href = host+'vrm-report/'+from+'/'+to+'/'+email+'/'+order_by+'/'+dir+'/0';
	};

	$('#vrmrep_date_from,#vrmrep_date_to,#vrmrep_email').on('keydown',function(e){
		if(e.which==13)
			update_vrm_report_display()
	});
	//=======================================================================================
	$('form.rewrite-url').on('submit', function(){
		var qs=[];
		$('input[data-rewrite-url-order]', this).each(function(c, u){
			qs[c]=u.value
		})
		window.location.href=this.action+'/'+qs.join('/');
		return false
	});
	//=======================================================================================
	$(document).on('hover', '.category-html-list a.category', function(e){
		var target = e.target || e.currentTarget || e.srcElement, t=$(target),p=t.parent('li');
		if(t.next('ul').length==0){
			$.ajax(target.href+'/sides',{
				success:function(x){
					t.attr('data-toggle','dropdown').parent('li').attr('class','dropdown').append(x);
				}
			})
		}
	})
})