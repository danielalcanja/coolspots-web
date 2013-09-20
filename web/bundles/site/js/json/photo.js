//jQuery(document).ready(function(){
//	jQuery.ajaxSetup({
//		async: true
//    });
//	jQuery.getJSON(ENV_SCRIPT + '/json/location', function(data){
//		if(data.length === 0) {
//			// resultado retornou vazio
//		} else {
//			// resultado retornou OK
//			jQuery('ul.content').setTemplateElement('locations_template');
//			jQuery('ul.content').processTemplate(data);
//			buildScreen();
//			updateLazyImages();	
//		}
//	}).error(function(){
//		console.log('Error trying to fetch json data');
//	});
//	
//});