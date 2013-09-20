function buildLinks() {
	jQuery('[data-behavior=link]').click(function(e){
		e.preventDefault();
		//console.log(jQuery(this).data('target'));
		document.location = ENV_SCRIPT + jQuery(this).data('target');
	});	
}

function updateLazyImages() {
	jQuery("img.lazy").lazyload({ 
		container: jQuery("#site"),
		threshold: 200,
		failure_limit : 20,
		effect : "fadeIn"
	});	
}