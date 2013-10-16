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
	jQuery('.infinite-scroll').jscroll({
		loadingHtml: '<strong>...</strong>',
		nextSelector: 'a.jscroll-next:last'
	});
});