jQuery(document).ready(function(){
	jQuery("a.club-title").click(function(){
		if(jQuery(this).hasClass('active'))
			jQuery(this).removeClass('active');
		else
			jQuery(this).addClass('active');
		jQuery(this).siblings('div').slideToggle();
	});
});