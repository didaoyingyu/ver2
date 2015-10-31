<div class="row">
	<h3 class="col-md-12">Add Card(s) through CSV File</h3>
</div>
<form action="/fcard/users/addMultipleCards" method="post" name="addMultipleForm" id="addMultipleForm" enctype="multipart/form-data">
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-2"><label for="card_add_file">Card File:</label></div>
		<div class="col-md-3"><input type="file" value="" id="card_add_file" class="form-control" name="filetocheck" /></div>
		<div class="col-md-3" id="card_add_file_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="col-md-2"><input type="hidden" name="addCardMultiple" id="deckOwner" /></div>
	<div class="col-md-6"><input type="button" value="Add Card(s)" id="addCardMultiple" class="btn btn-primary"/></div>
</div>
<div class="row form-row">
	<div class="col-md-2"></div>
	<div class="col-md-6" id="card_file_error" style="color:red;"></div>
</div>
<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-6"><?php echo $this->Html->link('Back to Parent\'s deck','#',['class'=>'viewDeckInfo','style'=>'display:block;']);?></div>
</div>
<iframe id='my_iframe' name='my_iframe' src="" style="display:none;">
</iframe>
</form>
<script type="text/javascript">
	var lockHere = 0;
	jQuery(document).ready(function(){
		jQuery('#dashboard-main-content-area').on('click','#addCardMultiple',function(){
			lockHere = 1;
			var deckid = jQuery(this).attr('deckid');
			jQuery('#card_file_error').html('');
			jQuery('#card_file_error').css({'color' : 'red'});
			var thisa = this;
			jQuery('#deckOwner').val(deckid);
			jQuery(this).val('Adding Card(s)...');
			document.getElementById('addMultipleForm').target = 'my_iframe';
			document.getElementById('addMultipleForm').submit();
			jQuery('#my_iframe').load(function(){
				lockHere = 0;
				jQuery(thisa).val('Add Card(s)');
				var contents = jQuery.parseJSON(jQuery(this).contents().text());
				if(contents['status']=='not ok'){
					if(contents['errors']!=''){
						jQuery('#card_file_error').html(contents['errors']);
					}
				}else{
					jQuery('#card_file_error').html('Successfully saved card(s)');
					jQuery('#card_file_error').css({'color' : 'green'});
				}
				
			})
			
		});
			
		/*
		setTimeout(function(){
			lazyLoad();
		},100);
		
		setTimeout(function(){
			'use strict';
			    // Change this to the location of your server-side upload handler:
			    var url = window.location.hostname === '/users/addMultipleCards/';
			    jQuery('#dashboard-main-content-area #addCardMultiple').fileupload({
			        url: url,
			        dataType: 'json',
			        done: function (e, data) {
			            jQuery.each(data.result.files, function (index, file) {
			                jQuery('<p/>').text(file.name).appendTo('#files');
			            });
			        },
			        progressall: function (e, data) {
			            var progress = parseInt(data.loaded / data.total * 100, 10);
			            jQuery('#progress .progress-bar').css(
			                'width',
			                progress + '%'
			            );
			        }
			    }).prop('disabled', !jQuery.support.fileInput)
			        .parent().addClass(jQuery.support.fileInput ? undefined : 'disabled');
		},800); */
		
	});
	function lazyLoad(){
		var cssHere = document.createElement('link');
		cssHere.type = 'text/css';
		cssHere.href = '/fcard/css/jquery.fileupload.css';
		cssHere.rel = 'stylesheet';
		
		var hd = document.getElementsByTagName('head')[0];
		var bd = document.getElementsByTagName('body')[0];
		
		hd.appendChild(cssHere);


		var jsLists = ['js/vendor/jquery.ui.widget.js','js/jquery.iframe-transport.js','js/jquery.fileupload.js'];

		var lengthJs = jsLists.length - 1;

		for(var x=0;x<=lengthJs;x++){
			var jsHere = document.createElement('script');
			jsHere.type = 'text/javascript';
			jsHere.src = jsLists[x];
			bd.appendChild(jsHere);
		}



		
	}
</script>