var $a = jQuery.noConflict();
$a(document).ready(function(){

	var content = "ul.content";
	var altTotal, altBox, altG, altP, altDis1, altDis2, altDis3, esp, espL;
	var ph, blc, wid, widTotal;
	var col1, col2, col3, col4, col5;
	var posAtual, totCom, posComent;
	
	if($a(content).hasClass("location") || $a(content).hasClass("event")) 		var LocEve = true;
	if($a(content).hasClass("explorer") || $a(content).hasClass("favorite")) 	var ExpFav= true;
	
	defineWidth();
	mountGrid();
	
	var delay = (function(){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
	})();

	$a(window).resize(function(){
		delay(function(e){
			$a(site).mCustomScrollbar("update");
			defineWidth();
			mountGrid();
			ajustImage();
		}, 50);
	});
	
	function defineWidth(){
		altTotal = 0; altBox = 0; altG = 0; altP = 0; altDis1 = 0; altDis2 = 0; altDis3 = 0; esp = 2; espL = 0;
		ph = 1; blc = 0; wid = 0; widTotal = 0;
		col1 = 0; col2 = 0; col3 = 0; col4 = 0; col5 = 0;
		comentario = 0; posAtual = 0; totCom = 1; posComent = 0;
		
		$a(".page").css({width : $a(window).width(), height : $a(window).height()});
		
		altTotal = $a(content).height();
		altBox = altTotal - 70;
		altG = Math.round(altBox * (66.66666666666667 / 100));
		altP = Math.round(altBox * (33.17460317460317 / 100));

		altDis2 = altP + esp;
		altDis3 = altDis2 * esp;

		if(LocEve) espL = 470; 
		if(ExpFav) espL = 400;
		
		col2 = esp + altP;		col3 = esp + 2 + (2 * altP);		col4 = esp + 4 + (3 * altP);		col5 = esp + 6 + (4 * altP);
	}
	function mountGrid(){
		$a(".photo").each(function(i){
			if(!($a(this).hasClass("date"))) {
				if(ph === 1) {
					posAtual = col1 + wid + espL;
					$a(this).animate({width : altG, height : altG},500).addClass("phG");
					$a(this).find("img").animate({width : altG, height : altG},500);
					$a(this).css({ top : altDis1, left : posAtual });
					$a(this).find(".info").width(altG - 20);
					widTotal = widTotal + altG + esp;
					posComent = posAtual + altG;
				}
				if(ph === 2 || ph === 3 || ph === 4 || ph === 5 || ph === 6 || ph === 7 || ph === 8){
					$a(this).animate({width : altP, height : altP},500).addClass("phP");
					$a(this).find(".info").width(altP - 20);
				}
				if(ph === 2){
					posAtual = col1 + wid + espL;
					$a(this).css({ top : altDis3, left : posAtual });
				}
				if(ph === 3){
					posAtual = col2 + wid + espL;
					$a(this).css({ top : altDis3, left : posAtual });
				}
				if(ph === 4){
					posAtual = col3 + wid + espL;
					$a(this).css({ top : altDis1, left : posAtual });
					widTotal = widTotal + altP + esp;
					posComent = posAtual + altP;
				}
				if(ph === 5){
					posAtual = col3 + wid + espL;
					$a(this).css({ top : altDis2, left : posAtual });
				}
				if(ph === 6){
					posAtual = col3 + wid + espL;
					$a(this).css({ top : altDis3, left : posAtual });
				}
				if(ph === 7){
					posAtual = col4 + wid + espL;
					$a(this).css({ top : altDis1, left : posAtual });
					widTotal = widTotal + altP + esp;
					posComent = posAtual + altP;
				}
				if(ph === 8) {
					posAtual = col5 + wid + espL;
					$a(this).css({ top : altDis1, left : posAtual });
					widTotal = widTotal + altP + esp;
					posComent = posAtual + altP;
				}
				if(ph === 9){
					posAtual = col4 + wid + espL;
					$a(this).animate({width : altG, height : altG},500).addClass("phG");
					$a(this).find("img").animate({width : altG, height : altG},500);
					$a(this).css({ top : altDis2, left : posAtual });
					$a(this).find(".info").width(altG - 20);
					ph = 0;
					blc++;
					wid = blc * (col5 + altP + 2);
					posComent = posAtual + altG;
				}
				if($a(this).next().hasClass("comentario")) {
					ph = 0;
					var com = $a(this).next(".comentario");
					comentario = 270;
					$a(com).css({ left : posComent });
					if(totCom === 1) {
						widTotal = widTotal;
						totCom++;
					}
				}
				ph++;
			}else {
				$a(this).css({ left : posAtual });
			} 
		});
		
		$a(".create").width(widTotal + espL);
	}

	var site = $a("#site"); 
	var time = '';
	time = setTimeout(function(){
		$a(site).mCustomScrollbar({
			horizontalScroll:true,
			advanced:{
				updateOnBrowserResize: true,
				updateOnContentResize: true
			},
			callbacks:{
				whileScrolling: function(){
					updateLazyImages();
					WhileScrolling();		
				}
			}
		});

		$a(".mCSB_container").height(altTotal);
		$a(".photo").fadeIn('slow');
		updateLazyImages();
		
		var esq = [];
		var box = [];
		var cai = "ul .date";
		var tot = $a(cai).length;
		
		$a(cai).each(function(i, obj){
			esq[i] = $a(this).offset().left;
			box[i] = obj;
		});
		
		function WhileScrolling(){
			var moveu = mcs.draggerLeft;
			for(var j = 0; j <= tot; j++){
				$a(box[j]).html(j);
				if (moveu > esq[j] -300){
					$a(box[j]).css({position : 'fixed', left : 150});
				} else {
					$a(box[j]).css({position : 'absolute', left : esq[j]});
				}
			}
		}
	},1000);
	
	$a(".photo").hover(function(){
		$a(this).find(".back").fadeIn('fast');
		$a(this).find(".time").fadeIn('fast');
		$a(this).find(".more").slideDown('fast');
	}, function(){
		$a(this).find(".more").slideUp('fast');
		$a(this).find(".time").fadeOut('fast');
		$a(this).find(".back").fadeOut('fast');
	});

	$a(content).hover(function(){
		$a(".top-bar .back").fadeOut('fast');
		$a(".top-bar-ico").fadeIn('slow');
	});
	$a(".top-bar-ico").click(function(){
		$a(this).hide();
		$a(".top-bar .back").fadeIn('fast');
	});
	var content = $a("#site"); //
	
	$a(site).mousemove(function(event) {
		//var posicao = parseInt((event.pageX / $a(window).width()) * 100);
		if(event.pageX < 45 ) { 
			$a(".pg-prev").fadeIn("slow");
		} else {
			$a(".pg-prev").fadeOut("fast");
		}
		if(($a(window).width() - event.pageX) < 50 ) {
			$a(".pg-next").fadeIn("slow");
		} else {
			$a(".pg-next").fadeOut("fast");
		}
	});
	$a(site).mouseout(function(event) {
		$a(this).mCustomScrollbar("stop");
	});
	$a(".pg-prev").hover(function(){
		site.mCustomScrollbar("scrollTo","left",{ scrollInertia:20000});
	}, function(){
		site.mCustomScrollbar("stop");
	});
	$a(".pg-next").hover(function(){
		site.mCustomScrollbar("scrollTo","right",{ scrollInertia:20000});
	}, function(){
		site.mCustomScrollbar("stop");
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
		$a(this).find("span").addClass("ros");
		$a(this).prev().find(".br").removeClass("nor");
	}, function(){
		$a(this).find("span").removeClass("ros");
		$a(this).prev().find(".br").addClass("nor");
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
		$a(this).find("span").addClass("ros");
		$a(this).prev().find(".br").removeClass("nor");
	}, function(){
		$a(this).find("span").removeClass("ros");
		$a(this).prev().find(".br").addClass("nor");
	});
	
	//GALLERY
	var widBox = 0, heiBox = 0;
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
			ajustImage(altCap);
			$a(box).fadeIn('slow');
			atual = index;
			return false;
		});
	});
	function ajustImage(heiCap){
		if($a(window).height() > 500){
			heiBox = altTotal - 100;
			widBox = heiBox - 50;
			$a(box + " .content").css({ width : widBox - 25 });
			$a(box + " .content .img").css({ height : widBox - 25 });
			$a(box + " .content .img img").css({ height : widBox - 25 });
			$a(".slide-icos").css({ left : widBox + 10 });
			$a(box).css({ width : widBox, height: heiBox + heiCap, marginTop: -(heiBox / 2), marginLeft : -(widBox / 2) });
		}
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