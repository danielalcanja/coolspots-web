var pagina = 1;
var part1 = {data:[]}, part2 = {data:[]};
var one = false, two = false;
var objFav = {data:[]};
var pg = 'Default';

function loadFavorites() {
	var url = '/json/favorites';
	var params =  { };
	jsonCall(url, params, callbackFavorites);	
}
function callbackFavorites(obj) {
	if(!obj) {
		console.log("ERRO DURANTE EXECUÇÃO DA CHAMADA AJAX");
		return(false);
	}
	
	if(obj.meta.status === 'ERROR') {
		console.log(obj.meta.message);
		return(false);
	}
	objFav.data = obj.data;
	console.log(objFav);
}
function isFavorite(id){
	var total = objFav.data.length;
	for(var item in objFav.data){
		if(id == objFav.data[item].idLocation.idInstagram) { return 'this-fav'; break; }
		else if(total==item-1) { return ''; }
	}
}	

function loadLocations() {
	var url =  '/json/locations';
	var params =  {
		page: pagina
	};
	jsonCall(url, params, callbackLocations);	
}
function callbackLocations(obj) {
	if(!obj) {
		console.log("ERRO DURANTE EXECUÇÃO DA CHAMADA AJAX");
		return(false);
	}
	
	if(obj.meta.status === 'ERROR') {
		console.log(obj.meta.message);
		return(false);
	}
	
	divide(obj);
	if(one) jQuery("#photo-list").append(compila(part1));
	if(two) jQuery("#photo-list").append(compila(part2));
	pagina++;
}
function divide(obj){
	part1 = {data:[]}, part2 = {data:[]};
	var totalObj = obj.data.length;
	var posObj = 0;
	
	if(totalObj >0){
		one = true;
		for(item = 0; item < totalObj; item++){
			if(totalObj >9) {
				two = true;
				if(item < 9) { 
					part1.data[item] = obj.data[item]
				} else { 
					part2.data[posObj] = obj.data[item]; 
					posObj++; 
				}
			}else{
				part1.data[item] = obj.data[item];
			}
		}
	}
}

var $a = jQuery.noConflict();
$a(document).ready(function(){
	var body 	= $a("body"),
		site	= $a("#site"),
		content = $a("#photo-list ul"),
		photo	= $a(".photo"),
		timer	= "",
		chama	= "",
		pgAtual = 0,
		hasImage= true, 
		second 	= false;
	$a(body).height($a(body).height()-55);
	
	$a(site).css("visibility","hidden");
	
	chama = setInterval(function(){
		if($a(".photo").size() > 0 && $a("#photo-list ul").size() > 0) {
			clearInterval(chama);
			start();
			console.log("Chamou start();");
		}
	},100);
	
	function start(classTemplate){
		console.log("Start");
		if(!hasImage)
		{
			console.log("Start -> hasImage");
			$a(".photo").show();
			
			var totUl = $a("#photo-list ul").size();
			var ul = "";
			
			for(i = 0; i < totUl; i++){
				ul = $a("#photo-list ul").eq(i);
				if(!($a(ul).hasClass("ul-9"))){
					li = $a(ul).children("li").size();
					$a(ul).removeClass().addClass("content ul-"+li);
					if(li==0) { $a(ul).remove(); }
				}
			}
		}
		if(!classTemplate) showImages();
		if(classTemplate) {
			var sl = 0;
			if($a("#photo-list").find(".template")){
				sl = setTimeout(function(){ start(); }, 500);
			}else{
				sl = setTimeout(function(){ start(true); }, 500);
			}
		}
	}
	function showImages(){
		timer = setTimeout(function(){
			if(!hasImage){
				ajustImage();
				
				var totUl = $a("#photo-list ul").size();
				for(i = 0; i < totUl; i++){
					ul = $a("#photo-list ul").eq(i);
				
					if($a(ul).hasClass("ul-9")){
						if(!($a(ul).children("div").hasClass("ph-box-9-1")))
						{
							$a(ul).children(".li-1").wrap("<div class='ph-box-9-1'></div>");
							var atual = $a(ul).children(".ph-box-9-1");
							$a(atual).css("width","40%");
							$a(ul).children(".li-2").appendTo(atual);
							$a(ul).children(".li-3").appendTo(atual);
							$a(atual).children(".li-1").css("width","100%"); 
							$a(atual).children(".li-2").css("width","50%");
							$a(atual).children(".li-3").css("width","50%");
							
							$a(ul).children(".li-4").wrap("<div class='ph-box-9-2'></div>");
							var atual = $a(ul).children(".ph-box-9-2");
							$a(atual).css("width","20%");
							$a(ul).children(".li-5").appendTo(atual);
							$a(ul).children(".li-6").appendTo(atual);
							$a(atual).children(".li-4").css("width","100%");
							$a(atual).children(".li-5").css("width","100%");
							$a(atual).children(".li-6").css("width","100%");
						}
					}
				}
			}
			updateScrollbar();
			updateLazyImages();
			if(pg=='Photos') slideGallery();
		},500);
	}
	function ajustImage(){
		$a(".photo").each(function(){
			$a(this).height($a(this).find("img").width());
		});
	}
	function foundImage(){
		hasImage = false;
		start();
		timer = setTimeout(function(){
			$a(site).css("visibility","visible"); 
		}, 500);
	}
	
	function updateLazyImages() {
		$a("img.lazy").lazyload({ 
			container: $a("#site"),
			threshold: 200,
			failure_limit : 20,
			effect : "fadeIn"
		});
		if(hasImage) foundImage();
	}
	
	function fetchNextPage(pg) {
		if(pg=='Default') loadLocations();
		if(pg=='Photos') loadLocationPhotos();
		var sleep = 0;
		console.log('Fetching next page: '+pagina);
		sleep = setTimeout(function(){ start(true); }, 200);
	}
	
	$a(window).resize(function(){
		ajustImage();
	});
	
	$a(body).mCustomScrollbar({
		scrollInertia: 0,
		advanced:{
			updateOnBrowserResize: true,
			updateOnContentResize: true
		},
		callbacks:{
			whileScrolling: function(){
				updateLazyImages();	
			},
			onTotalScroll: function(){
				if(pagina != pgAtual){
					fetchNextPage(pg);
					pgAtual = pagina;
				}
			},
			onTotalScrollOffset: $a(site).height()+300
		}
	});
	
	function updateScrollbar() {
		$a(body).mCustomScrollbar("update");
	}
	
	$a(site).delegate(content, 'mouseenter mouseleave', function(){
		$a(".top-bar .back").fadeOut('fast');
		$a(".top-bar-ico").fadeIn('slow');
	});
	$a(".top-bar-ico").click(function(){
		$a(this).hide();
		$a(".top-bar .back").fadeIn('fast');
	});

	$a("#photo-list").delegate(".favorite",'click', function(event) {
		event.preventDefault();
		var item = $a(this).closest(".photo");
		var parameters = {
				id_location: item.attr("data")
			};
		$a(this).html("<div class='loading'><div class='load'></div></div>");

		if($a(this).hasClass("this-fav")){
			var url = '/json/favorites/remove';
		} else {
			var url = '/json/favorites/add';
		}
		
		var jsonObject = jsonCall(url, parameters);
		if(jsonObject.meta.status == 'ERROR') {
			// console.log(jsonObject.meta.message);
			$a(this).find(".loading").children().removeAttr("class").addClass("error");
		} else { 
			if($a(this).hasClass("this-fav")) $a(this).removeClass("this-fav")
			else $a(this).addClass("this-fav");
		}
		$a(this).find(".loading").remove();
	});
	
	
	$a("#photo-list").delegate(".photo",'mouseenter mouseleave', function(event) {
		if(!($a(this).find(".favorite").hasClass("this-fav"))) $a(this).find(".favorite").fadeToggle();
		$a(this).find(".back").fadeToggle();  
		$a(this).find(".clock").slideToggle('fast');
		$a(this).find(".tmp").slideToggle('fast');
		$a(this).find(".day-str").fadeToggle();
		$a(this).find(".more").slideToggle('fast');
		// $a(this).find(".more").slideToggle( event.type === 'mouseenter' );
	});
	
	var	shadown = ".shadown",
		shadownPics = ".shadownPics",
		box = ".shadownPics .box",
		atual = 0,
		pic = [];
	//TOP - BAR
	$a(".best").hover(function(){
		$a(this).find("ul").slideToggle('fast');
	});
	$a(".top-ico").hover(function(){
		$a(this).find(".hint").slideToggle('fast');
	});
	
	$a(".add-event").click(function(){
		var html = '';
		html += '<div class=\"box-criar-evento boxDiversos\">                                                                    ';
		html += '	<div class=\"space\">                                                                                        ';
		html += '		<h2 class=\"box-h2\">Crie um evento</h2>                                                                 ';
		html += '		<div class=\"row\">                                                                                      ';
		html += '			<span class=\"col1\">Nome</span>                                                                     ';
		html += '			<div class=\"col2\">                                                                                 ';
		html += '				<input type=\"text\" name=\"nome\" class=\"campos-box\" placeholder=\"ex: Festa de aniversário\">';
		html += '			</div>                                                                                               ';
		html += '		</div>                                                                                                   ';
		html += '		<div class=\"clr h05\"></div>                                                                            ';
		html += '		<div class=\"row\">                                                                                      ';
		html += '			<span class=\"col1\">Detalhes</span>                                                                 ';
		html += '			<div class=\"col2\">                                                                                 ';
		html += '				<textarea name=\"detalhes\" rows=\"2\" class=\"campos-box\" placeholder=\"Adicione mais informações\"></textarea>';
		html += '			</div>                                                                                               ';
		html += '		</div>                                                                                                   ';
		html += '		<div class=\"clr h05\"></div>                                                                            ';
		html += '		<div class=\"row\">                                                                                      ';
		html += '			<span class=\"col1\">Onde</span>                                                                     ';
		html += '			<div class=\"col2\">                                                                                 ';
		html += '				<input type=\"text\" name=\"onde\" class=\"campos-box\" placeholder=\"Adicione um lugar\">       ';
		html += '			</div>                                                                                               ';
		html += '		</div>                                                                                                   ';
		html += '		<div class=\"clr h05\"></div>                                                                            ';
		html += '		<div class=\"row\">                                                                                      ';
		html += '			<span class=\"col1\">Quando</span>                                                                   ';
		html += '			<div class=\"col2\">                                                                                 ';
		html += '				<input type=\"text\" name=\"data\" class=\"campos-boxM\">                                        ';
		html += '				<input type=\"text\" name=\"hora\" class=\"campos-boxM\" placeholder=\"Horário\">                ';
		html += '			</div>                                                                                               ';
		html += '		</div>                                                                                                   ';
		html += '		<div class=\"clr h05\"></div>                                                                            ';
		html += '		<div class=\"row\">                                                                                      ';
		html += '			<span class=\"col1\">Privacidade</span>                                                              ';
		html += '			<div class=\"col2\">                                                                                 ';
		html += '				<div class=\"pri-box\">                                                                          ';
		html += '					<select>                                                                                     ';
		html += '						<option value=\"1\">Público</option>  		                             ';
		html += '						<option value=\"0\">Privado</option>   		                                             ';
		html += '					</select>                                                                                    ';
		html += '				</div>                                                                                           ';
		html += '				<input type=\"hidden\" name=\"privacidade\" value=\"Spooters Amigos\">                           ';
		html += '			</div>                                                                                               ';
		html += '		</div>                                                                                                   ';
		html += '		<div class=\"clr h05\"></div>                                                                            ';
		html += '		<div class=\"row\">                                                                                      ';
		html += '			<span class=\"col1\">Hashtag</span>                                                                 ';
		html += '			<div class=\"col2\">                                                                                 ';
		html += '				<textarea name=\"hashtag\" rows=\"2\" class=\"campos-box\" placeholder=\"Hashtag\"></textarea>';
		html += '			</div>                                                                                               ';
		html += '		</div>                                                                                                   ';
		html += '		                                                                                                         ';
		html += '		<div class=\"clr\"></div><div class=\"rowDivide\"></div>                                                 ';
		html += '		<input class=\"btn-padrao-m dir btn-cancel\" type=\"button\" value=\"Cancelar\">                         ';
		html += '		<input class=\"btn-padrao-m dir marRig05 btCriarEvento\" type=\"button\" value=\"Criar\">                ';
		html += '	</div>                                                                                                       ';
		html += '</div>                                                                                                          ';
		$a(shadown).html(html).fadeIn('slow');
	});
	
	$a(".ind-location").click(function(){
		var html = '';
		html += '<div class=\"box-ind-location boxDiversos\">                                                                    ';
		html += '	<div class=\"space\">                                                                                        ';
		html += '		<h2 class=\"box-h2\">Indicar um lugar</h2>                                                                ';
		html += '		<div class=\"row\">                                                                                      ';
		html += '			<span class=\"col1\">Lugar</span>                                                                    ';
		html += '			<div class=\"col2\">                                                                                 ';
		html += '				<input type=\"text\" name=\"nome\" class=\"campos-box\" placeholder=\"Nome do lugar\">			 ';
		html += '			</div>                                                                                               ';
		html += '		</div>                                                                                                   ';
		html += '		<div class=\"clr h05\"></div>                                                                            ';
		html += '		<div class=\"row\">                                                                                      ';
		html += '			<span class=\"col1\">E-mail</span>                                                                   ';
		html += '			<div class=\"col2\">                                                                                 ';
		html += '				<input type=\"text\" name=\"email\" class=\"campos-box\" placeholder=\"Seu e-mail\">			 ';
		html += '			</div>                                                                                               ';
		html += '		</div>                                                                                                   ';
		html += '		<div class=\"clr h05\"></div>                                                                            ';
		html += '		<div class=\"row\">                                                                                      ';
		html += '			<span class=\"col1\">Cidade</span>             		                                                 ';
		html += '			<div class=\"col2\">                                                                                 ';
		html += '				<input type=\"text\" name=\"cidade\" class=\"campos-box\" placeholder=\"Nome da cidade\">		 ';
		html += '			</div>                                                                                               ';
		html += '		</div>                                                                                                   ';
		html += '		<div class=\"clr h05\"></div>                                                                            ';
		html += '		<div class=\"row\">                                                                                      ';
		html += '			<span class=\"col1\">Estado</span>                        		                                     ';
		html += '			<div class=\"col2\">                                                                                 ';
		html += '				<input type=\"text\" name=\"estado\" class=\"campos-box\" placeholder=\"Nome do estado\">		 ';
		html += '			</div>                                                                                               ';
		html += '		</div>                                                                                                   ';
		html += '		<div class=\"clr h05\"></div>                                                                            ';
		html += '		                                                                                                         ';
		html += '		<div class=\"clr\"></div><div class=\"rowDivide\"></div>                                                 ';
		html += '		<input class=\"btn-padrao-m dir btn-cancel\" type=\"button\" value=\"Cancelar\">                         ';
		html += '		<input class=\"btn-padrao-m dir marRig05 btIndicarLugar\" type=\"button\" value=\"Indicar\">             ';
		html += '	</div>                                                                                                       ';
		html += '</div>                                                                                                          ';
		$a(shadown).html(html).fadeIn('slow');
	});
	
	$a(document).delegate('.btCriarEvento', 'click', function(){
		alert("Chama função para gravar no banco");
		shadownClose();
	});
	
	$a(document).delegate('.btIndicarLugar', 'click', function(){
		alert("Chama função para gravar no banco");
		shadownClose();
	});
	
	$a(".top-ico").hover(function(){
		$a(this).find("span").toggleClass("ros");
		$a(this).prev().find(".br").toggleClass("nor");
	});
	
	//BOTTOM - BAR
	$a(".search").hover(function(){
		$a(".inp-search").animate({ width: 150, paddingLeft: 5, paddingRight: 5 },500).show().focus();
	});
	
	var input = ".inp-search";
	$a(site).click(function(){
		var searchTime = "";
		$a(input).animate({ width: 0, padding: 0 },500);
		searchTime = setTimeout(function(){
			$a(input).hide();
		},500)
		clearTimeout(searchTime);
	});
	$a(input).click(function(){
		return false;
	});
	
	$a(".bottom-bar ul li").hover(function(){
		$a(this).find("span").toggleClass("ros");
		$a(this).prev().find(".br").toggleClass("nor");
	});
	
	$a(document).delegate(".photo .back", "click", function(){
		var link = $a(this).attr("data");
		location.href = link;
	});
	$a(".config").click(function(e){
		e.preventDefault();
		$a(this).toggleClass("config-normal config-click");
		$a(this).find("span").toggleClass("click");
		$a("ul.config-menu").slideToggle('fast');
	});
	
	//GALLERY
	function slideGallery(){
		$a(".eve-pic").each(function(index){
			pic[index] 	= $a(this);
			var imagem 	= $a(this).attr("data");
			var user 	= $a(this).find(".username").val();
			var userpic	= $a(this).find(".userpic").val();
			var data_add= $a(this).find(".dateadded").val();
			var name_loc= $a(this).find(".name_location").val();
			var caption	= $a(this).find(".caption").val();
			
			$a(pic[index]).click(function(){
				$a(shadownPics).fadeIn('slow');
				$a(".box .img img").attr("src",imagem);
				$a(".author img").attr("src",userpic);
				$a("strong.user").html(user);
				$a("span.data_pic").html(data_add);
				$a("strong.name_location").html(name_loc);
				$a("span.caption").html(caption);
				$a(box).fadeIn('slow');
				atual = index;
				return false;
			});
		});
	}

	$a(document).delegate(".slide-eve-prev", "click", function(){
		$a(pic[atual-1]).click();
	});
	$a(document).delegate(".slide-eve-next", "click", function(){
		$a(pic[atual+1]).click();
	});
	$a(document).delegate(".slide-close", "click", function(){
		shadownClose();
	});
	$a(document).delegate(".slide-icos .cur", "click", function(){
		alert("CURTIR IMAGEM DE ID: " + $a(pic[atual]).attr("id"));
	});
	$a(document).delegate(".slide-icos .men", "click", function(){
		alert("ENVIAR MENSAGEM AO USUÁRIO: " + $a(pic[atual]).find(".username").val());
	});
	$a(document).delegate(".slide-icos .com", "click", function(){
		alert("COMPARTIHAR IMAGEM: " + $a(pic[atual]).attr("data"));
	});
	$a(document).delegate(shadown, "click", function(){
		$a(this).fadeOut('slow');
	});
	$a(document).delegate(shadownPics, "click", function(){
		$a(this).fadeOut('slow');
	});
	$a(document).delegate(box, "click", function(){
		return false;
	});
	$a(document).delegate(".boxDiversos", "click", function(){
		return false;
	});
	
	//SOBRE
	$a(document).delegate(".sobre-ul a", "click", function(){
		if(!($a(this).hasClass("ativo"))){
			$a(".sobre-ul a").removeClass("ativo");
			$a(this).addClass("ativo");
			var pg = $a(this).attr("data");
			$a(".sobre-pg").slideUp("slow");
			$a("."+pg).slideDown("slow");
			$a(".rollBar").mCustomScrollbar("update");
		}
		return false;
	});
	
	//EXPLORE
	$a("a.order").hover(function(){
		if(!($a(this).find("span").hasClass("ativo"))){
			$a(this).find("span").addClass("hover");
		}
		return false;
	}, function(){
		$a(this).find("span").removeClass("hover");
	});
	$a("a.order").click(function(){
		if(!($a(this).find("span").hasClass("ativo"))){
			$a("a.order").find("span").removeClass("ativo");
			$a(this).find("span").addClass("ativo");
		} else {
			$a(this).find("span").removeClass("ativo");
		}
		return false;
	});
	
	//FAVORITOS
	$a("a.menuPink").click(function(){
		$a("a.menuPink").removeClass("menuPinkAtivo");
		$a(this).addClass("menuPinkAtivo");
		
		var pg = $a(this).attr("data");
		if(pg !="" && !($a("div."+pg).is(':visible'))){
			$a(".mes-box").slideUp('fast');
			$a("div."+pg).slideDown('fast');
		}
		return false;
	});
	
	//MESSAGES
	$a(".mes-box:first").show();
	$a("a.toggle").click(function(){
		if(!($a(this).find("span").hasClass("ativo"))){
			$a("a.toggle").find("span").removeClass("ativo");
			$a(this).find("span").addClass("ativo");
		} else {
			$a(this).find("span").removeClass("ativo");
		}
		$a(this).next().slideToggle('fast');
		return false;
	});
	
	$a(document).delegate('.btn-cancel', 'click', function(){
		shadownClose();
	});
	
	function shadownClose(){
		$a(shadown).fadeOut('slow');
		$a(shadownPics).fadeOut('slow');
	}
});