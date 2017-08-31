jQuery(document).ready(function($){

	//==============================================================================
	$('.selectpicker').selectpicker({noneSelectedText:''});

	//==============================================================================
	
	// In quick search update Model input after selecting a Manufacturer
	$('#qs-manufacture').on('change', function(){
		var v=this.value;
		if(v=='')
			return;

		$.ajax(host+'catalog/models/'+encodeURIComponent(v)+'/ajax/htmlselect', {
			success:function(x){
				x = '<option value="">Select ...</option>' + x;
				$('#qs-model').html(x);
				$('#qs-year').html('<option value=""></option>');
				$('#qs-model').selectpicker('refresh');
				$('#qs-year').selectpicker('refresh');
				$('button','select#qs-model + div.bootstrap-select').click()
			}
		})
	})
	//==============================================================================
	
	// In quick search update Year input after selecting a Model
	$('#qs-model').on('change', function(){
		var mod=this.value;
		if(mod=='')
			return;

		var manu=$('#qs-manufacture').val();
		if(manu=='')
			return;

		$.ajax(host+'catalog/start_end_year/'+encodeURIComponent(manu)+'/'+encodeURIComponent(mod)+'/ajax/htmlselect', {
			success:function(x){
				x = '<option value="">Select ...</option>' + x;
				$('#qs-year').html(x)
				$('#qs-year').selectpicker('refresh');
				$('button','select#qs-year + div.bootstrap-select').click()
			}
		})
	})
	//==============================================================================
	
	// In quick search go to the parts screen when the user has selected an item model year
	$('#qs-year').on('change', function(){
		var manu=$('#qs-manufacture').val(),
		mod=$('#qs-model').val(),
		year=$('#qs-year').val();
		if(manu=='' || mod=='' || year== '')
			return;

		window.location.href = host+'fcatalog/products/'+encodeURIComponent(manu)+'/'+encodeURIComponent(mod)+'/'+encodeURIComponent(year);
	});
})