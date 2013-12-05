jQuery(document).ready(function(){
	jQuery(".timeago").timeago();
	
});

function updateLazyImages() {
	jQuery("img.lazy").lazyload({ 
		container: jQuery("#site"),
		threshold: 200,
		failure_limit : 20,
		effect : "fadeIn"
	});	
}

function fetchNextPage() {
	if(jQuery('#np').val() !== "") {
		console.log('Fetching next page: ' + jQuery('#np').val());
		jQuery.ajax({
			url: jQuery('#np').val(),
			dataType: 'html',
			success: function(data) {
				jQuery('#photo-list').append(data);
				montaTudo();
				//console.log(data);
			}
		});
	}
}