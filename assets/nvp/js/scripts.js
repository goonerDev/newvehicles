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
	// Submit log in
	signin = function() {
		var p = $('#password');
		$.ajax(host + 'login/process', {type:'POST', data:{email:$('#email').val(), password:p.val()}, dataType:'json', success:function(x){
			var n = x._er.length, t;
			if( n > 0) {
				t = '<ul>';
				for(var i=0; i < n; i++)
					t+= '<li>' + x._er[ i ] + '</li>';
				t+= '</ul>';
				$('#login-error-msg-wrapper').html( t );
				p.val('');
			}
			else
				window.location.href = x.referer
		},complete:function(x){console.log(x);}})
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
	update_quick_access_cart_items=function(o){
		var that=o?o:$('#cart-dropdown-container');
		$.ajax(host+'cart/items',{
			type:'post',
			data:{origin:'checkout'},
			success:function(x){
				$('.dropdown-menu',that).html(x)
			}
		});
	};
	// Click on "Cart" nav link displays list of items within a popup. So the user can quickly delete
	// items
	$('#cart-dropdown-container').on('show.bs.dropdown', function(){
		if($(window).width()<768){
			window.location.href=host+'checkout';
			return false
		}
		update_quick_access_cart_items(this)
	});
	$('[data-toggle=dropdown]').on('click', function(){
		if($(window).width()<768){
			window.location.href=host+'checkout';
		}
	})
	//=======================================================================================
	// Delete an item from cart by quick access
	$(document).on('click','#cart-dropdown-container a.mini-cart-delete', function(e){
		e.preventDefault();

		$.ajax(this.href,{
			data:{ajax:1,origin:'checkout'},
			type:'post',
			success:function(){
				update_quick_access_cart_items();
				update_cart_amount();
			}
		});

		return false;
	});
	//=======================================================================================
	on_add_item=function(e,u,that,qty){
		e.preventDefault();

		var o = that.clone(),
		from = that.offset(),
		to = $('#cart').offset();

		o.css('position','absolute').css('left',from.left).css('top',from.top).css('opacity',.5);
		$('body').append(o);

		// Update the server
		$.ajax(u, {data:{qty:qty},dataType: 'json', type:'POST', success:function(x){
		
			if(x)
				if(x.err)
					alert(x.err);
				else if(x.ui) // Update the ui according to the new qty
					that.parents('div[id^=add-to-cart-]').html(x.ui)
			update_cart_amount();
		},
		complete:function(x){console.log(x.responseText)}
		});

		// Animation displaying the cart button moving to cart
		o.animate({top:to.top,left:to.left,width:30,height:30}, 1000,function(){o.remove();})
		return false
	};

	// Submit the "add item" action either by typing ENTER on keyboard ...
	$('[name=add-cart-form]').submit(function(e){on_add_item(e,this.action,$('button',this),$('[name=qty]',this).val())});
	// ... or clicking on "Add to cart" button
	$(document).on('click', 'a.add-to-cart', function(e){on_add_item(e,this.href,$(this),1)});
	//=======================================================================================
	// $(document).on('click','table#items a[id^=update-qty-]', function(e){
		// e.preventDefault();

		// if(this.id){
			// var id=this.id.substr(11);
			// update_total_price(document.getElementById('input-'+id))
		// }

		// return false
	// });
	//=======================================================================================
	$('#log-out-btn').on('click', function(e) {
		e.preventDefault();
		e.target = e.currentTarget || e.srcElement;
		if( e.target && e.target.href ) {
			$.ajax(e.target.href, {success:function(x){
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
		
		window.location.href = host + manu + '/' + model + '/' + year;
	})
	//=======================================================================================
	Array.prototype.indexOfText=function(t){
		var n=this.length-1;
		while(n>=0){
			if(typeof this[n] == 'string' && this[n].toLowerCase()==t.toLowerCase())
				return n;
			if(this[n].text&&this[n].text.toLowerCase()==t.toLowerCase())
				return n;
			n--
		}
		return -1
	};
	// Launch simple search
	var simple_search_val;
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
							if(o.text!=undefined && b.indexOfText(o.text)==-1)
								b.push(o);
							i++
						}
						cb(b)
					}
				}
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
	}).on('typeahead:cursorchanged',function(e,o){
		simple_search_val = o.url
	});
	
	$('#simple-search-input button').click(function(e){
		e.preventDefault();
		if(simple_search_val!=undefined)
			window.location.href = simple_search_val;
		return false
	});
	//=======================================================================================
	// Discard the cart
	// $(document).on('click', 'a#cart-discard-trigger', function(e) {
		// e.preventDefault();

		// var that = this;

		// $.ajax(that.href, {success:function(x){
			// window.location.href = host + 'checkout'
		// }});
	// });
	//=======================================================================================
	// Confirm the order
	// $('button#place-the-order-btn').bind('click', function(e){
		// this.disabled = 'disabled';
		// $.ajax(host + 'process-order',{
		// type:'POST',
		// success:function(x){
			// window.location.href = host + 'thank-you/checkout'
		// }, complete:function(x){console.log(x.responseText)}})
	// });
	//=======================================================================================
	$('input#contact-send-msg-btn,input#sign-up-btn').click(function(){
		$('#loading-modal').modal();
	});
	//=======================================================================================
	// Upload a document by ajax
	// Alert on error else redirect
	$('iframe#exporter,iframe#ajax_poster').bind('load', function(){
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
	$('form.rewrite-url').on('submit', function(){
		var qs=[];
		$('input[data-rewrite-url-order]', this).each(function(c, u){
			qs[c]=u.value
		})
		window.location.href=this.action+'/'+qs.join('/');
		return false
	});
	var div_wrapper = $('div#mini-quick-search-wrapper'),
	ul_wrapper = $('ul#mini-quick-search'),
	so = $('ul#mini-quick-search-selected-options'),
	cb = $('a#mini-quick-search-close-btn');
	o = {};
	//=======================================================================================
	$(document).on('click', 'a#mini-quick-search-btn,a#products-categories-btn', function(e){
		e.preventDefault();

		div_wrapper.css('display', 'block');
		cb.css('visibility', 'visible');
		so.html('');

		$.ajax(this.href, {
			dataType: 'html',
			success:function(x){
				ul_wrapper.html('<li>' + x + '</li>');
			}
		})

		return false
	});
	//=======================================================================================
	$(document).on('click', '#mini-quick-search a.manufacture-item-list,#mini-quick-search-selected-options a.manufacture-item-list,#mini-quick-search a.model-item-list', function(e){
		e.preventDefault();

		e.target = e.currentTarget || e.srcElement || e.target;
		if(!e.target)
			return false;

		var u = e.target.href + '/ajax/html';

		$.ajax(u, {
			dataType:'html',
			success:function(x){
				u = u.replace(host,'').replace(/^\/|\/$/,'').split('/'); // http://www.domain.com/make/ajax/html or http://www.domain.com/make/model/html
				if(u[1] == 'ajax'){ // http://www.domain.com/make/ajax/html
					o.manu_url = u[0];
					o.manu_readable = e.target.innerHTML;
					u = host + o.manu_url;
					c = 'manufacture-item-list';
					o.thumbnail = '<li>&nbsp;&raquo;&nbsp;<a href="' + u + '" class="' + c + '">' + o.manu_readable + '</a></li>'
				}
				else if(u[2] == 'ajax'){ // http://www.domain.com/make/model/html
					o.model_url = u[1];
					o.model_readable = e.target.innerHTML;
					u = host + 'catalog/start_end_year/' + o.manu_url + '/' + o.model_url;
					c = 'model-item-list';
					o.thumbnail += '<li>&nbsp;&raquo;&nbsp;' + o.model_readable + '</li>';
				}

				so.html(o.thumbnail);

				ul_wrapper.html('<li>' + x + '</li>')
			}
		});
		return false
	});
	//=======================================================================================
	cb.bind('click', function(){
		div_wrapper.css('display', 'none');
		cb.css('visibility', 'hidden');
		ul_wrapper.html('');
		so.html('')
	})
	//=======================================================================================
	var cat_filter=$('#product-list-cols-category-filter>div');
	cat_filter.accordion({active:false,collapsible:true,heightStyle:'content',animate:false,
	beforeActivate:function(e,ui){
// At this time just leave the nested level off
		// if($(ui.newHeader[0]).text()=='All')
			window.location.href=ui.newHeader.data('url');
		// else
			// ui.newPanel.load(ui.newHeader.data('url'));
	},
	activate:function(){
		// cookie_set('cat_filter',cat_filter.accordion('option','active'));
	}});

	// var cf=cookie_get('cat_filter');
	// if(cf&&!isNaN(cf))
		// cat_filter.accordion('option','active',parseInt(cf))
	//=======================================================================================
	$('a#mini-cart-close-btn').on('click', function(e){
		e.preventDefault();
		$('##cart-dropdown-container .dropdown-menu').dropdown('toggle')
	})
	//=======================================================================================
	var waitForFinalEvent=(function () {
		var timers = {};
		return function (callback, ms, uniqueId) {
			if (!uniqueId)
				uniqueId = '0wA./:?!è-(*';
			if (timers[uniqueId])
				clearTimeout (timers[uniqueId]);
			timers[uniqueId] = setTimeout(callback, ms);
		};
	})();
	$(window).resize(function(){
		waitForFinalEvent(function(){
			cookie_set('screen_width',$(window).width(),1);
		},500,'03azef)0@');
	});
	cookie_set('screen_width',$(window).width());
	//=======================================================================================
	$('img.zoomable').smoothZoom();
})