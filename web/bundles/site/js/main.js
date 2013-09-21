function updateLazyImages() {
	jQuery("img.lazy").lazyload({ 
		container: jQuery("#site"),
		threshold: 200,
		failure_limit : 20,
		effect : "fadeIn"
	});	
}

jQuery(document).ready(function(){
	jQuery(".timeago").timeago();
});