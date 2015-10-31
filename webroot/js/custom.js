jQuery(document).ready(function(){


	//this is for captcha
	var t = setInterval(function(){
		if(jQuery('#ExampleCaptcha_CaptchaImage').length>0){
			jQuery('.LBD_CaptchaImageDiv').css({'width' : '100%'});
			jQuery('.LBD_CaptchaIconsDiv').css({'width' : '100%'});
			clearInterval(t);
		}
	},100);

});