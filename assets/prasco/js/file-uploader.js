jQuery(document).ready(function($){
	//=======================================================================================
	$('input[type=file]').bootstrapFileInput();
	//=======================================================================================
	$('input#uploader-submit-btn').bind('click', function(){
		$('#loading-modal').modal()
	});
	//=======================================================================================
	$('iframe#uploader').bind('load', function(e){
		$('#loading-modal').modal('hide');
		// var t = e.currentTarget || e.srcElement;
		// if(!t)
			// return;
		// var x = eval('(' + t.contentDocument.activeElement.innerHTML + ')');
		// $('p#file-upload-msg-wrapper').html(x.err || (x.total + ' inserted / updated'))
	})
})