var $a = jQuery.noConflict();
$a(document).ready(function(){
	var content = $a("ul.content");
	var site	= $a("#site");
	var photo	= $a(".photo");
	var timer	= "";
	var pgAtual = "", pgNext = $a('.np:first').val();
	var hasImage= true, second = false;
	
	$a("body").height($a("body").height()-55); 
	//$a(site).height($a(window).height()-55);
	
	$a(site).css("visibility","hidden");
	$a("body").append("<div class='loading'></div>");
	start();
	
	function start(){
		if(!hasImage){
			$a(".photo").show();
			var totUl = $a("ul.content").size();
			var ul = "";
			
			for(i = 0; i < totUl; i++){
				ul = $a("ul.content").eq(i);
				if(!($a(ul).hasClass("ul-9"))){
					li = $a(ul).children("li").size();
					$a(ul).removeClass().addClass("content ul-"+li);
					if(li==0) { $a(ul).remove(); }
				}
			}
		}
		showImages();
	}
	
	function showImages(){
		timer = setTimeout(function(){
			if(!hasImage){
				$a(".photo").each(function(){
					$a(this).height($a(this).find("img").width());
					//if($a(this).find("img").attr("src") == "/coolspots-web/web/bundles/site/images/photo-placeholder.png") $a(this).remove();
				});
				var totUl = $a("ul.content").size();
				for(i = 0; i < totUl; i++){
					ul = $a("ul.content").eq(i);
				
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
			if(($a('.np:first').val()) != undefined) slideGallery();
		},500);
	}
	
	function foundImage(){
		hasImage = false;
		start();
		timer = setTimeout(function(){
			$a(".loading").hide();
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
	
	function fetchNextPage() {
		var sleep = 0;
		if($a('.np:first').val() !== "") {
			console.log('Fetching next page: ' + $a('.np:first').val());
			$a.ajax({
				url: $a('.np:first').val(),
				dataType: 'html',
				success: function(data) {
					$a("#photo-list").append(data);
					sleep = setTimeout(function(){ start(); }, 100);
				}
			});
		}
	}
	
	// $a(window).scroll(function() {
		// if(($a(window).scrollTop() + $a(window).height() + 100) >= $a(document).height()) {
			// $a(window).unbind('scroll');
			// fetchNextPage();
		// }
	// });
	
	$a("body").mCustomScrollbar({
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
				if(pgAtual != pgNext){
					if(($a('.np:first').val()) != undefined){
						fetchNextPage();
					}
				}
				pgAtual = pgNext;
				pgNext = $a('.np:first').val();
			},
			onTotalScrollOffset: $a(site).height()+200
		}
	});
	
	function updateScrollbar() {
		$a("body").mCustomScrollbar("update");
	}
	
	$a(content).hover(function(){
		$a(".top-bar .back").fadeOut('fast');
		$a(".top-bar-ico").fadeIn('slow');
	});
	$a(".top-bar-ico").click(function(){
		$a(this).hide();
		$a(".top-bar .back").fadeIn('fast');
	});
	
	$a("#photo-list").delegate(".favorite",'click', function(event) {
		if($a(this).hasClass("this-fav")) alert("Remove da lista Favoritos!")
		else alert("Adiciona na lista Favoritos!");
		return false;
	});
	
	$a("#photo-list").delegate(".photo",'mouseenter mouseleave', function(event) {
		if(!($a(this).find(".favorite").hasClass("this-fav"))) $a(this).find(".favorite").fadeToggle();
		$a(this).find(".back").fadeToggle();  
		$a(this).find(".time").fadeToggle();
		$a(this).find(".more").slideToggle('fast');
		//$a(this).find(".more").slideToggle( event.type === 'mouseenter' );
	});	
	
	var	shadown = ".shadown",
		box = ".shadown .box",
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
			var altCap	= $a(this).find(".caption").height();
			
			$a(pic[index]).click(function(){
				$a(shadown).fadeIn('slow');
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
	$a(document).delegate(shadown, "click", function(){
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
	}
});