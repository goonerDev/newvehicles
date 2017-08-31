jQuery(document).ready(function($){
	var div_wrapper = $('div#mini-quick-search-wrapper'),
	ul_wrapper = $('ul#mini-quick-search'),
	so = $('ul#mini-quick-search-selected-options'),
	cb = $('a#mini-quick-search-close-btn');
	o = {};
	//=======================================================================================
	$(document).on('click', 'a#mini-quick-search-btn', function(e){
		e.preventDefault();

		div_wrapper.css('display', 'block');
		cb.css('visibility', 'visible');
		so.html('');

		$.ajax(host+'catalog/manufactures/ajax/html', {
			dataType: 'html',
			success:function(x){
				ul_wrapper.html('<li>' + x + '</li>');
			}
		})

		return false
	});
	//=======================================================================================
	$(document).on('click', 'a.manufacture-item-list,a.model-item-list', function(e){
		e.preventDefault();

		e.target = e.currentTarget || e.srcElement;
		if(!e.target)
			return false;

		var u = e.target.href + '/ajax/html';

		$.ajax(u, {
			dataType:'html',
			success:function(x){
				u = u.replace(host,'').replace(/^\/|\/$/,'').split('/'); // http://www.domain.com/catalog/models/xxxxxx or http://www.domain.com/catalog/start_end_year/xxxxxx
				if(u[1] == 'models'){
					o.manu_url = u[2];
					o.manu_readable = e.target.innerHTML;
					u = host + 'catalog/models/' + o.manu_url;
					c = 'manufacture-item-list';
					o.thumbnail = '<li>&nbsp;&raquo;&nbsp;<a href="' + u + '" class="' + c + '">' + o.manu_readable + '</a></li>'
				}
				else if(u[1] == 'start_end_year'){
					o.model_url = u[3];
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
})