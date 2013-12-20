var $a = jQuery.noConflict();
$a(document).ready(function(){
	var content = $a("ul.content");
	var site	= $a("#site");
	var photo	= $a(".photo");
	var timer	= "";
	
	$a(site).height($a(window).height()-55);
	
	$a(photo).show();
	$a(content).each(function(){
		$a(this).addClass("ul-"+$a(this).children("li").size());
		$a(this).children("li").css("display","block");
	});
	
	timer = setTimeout(function(){
		$a(photo).each(function(){
			$a(this).height($a(this).width());
		});
		updateLazyImages();
	},500);
	
	$a(site).mCustomScrollbar({
		scrollInertia: 300,
		advanced:{
			updateOnBrowserResize: true,
			updateOnContentResize: true
		},
		callbacks:{
			whileScrolling: function(){
				updateLazyImages();
				// WhileScrolling();		
			},
			onTotalScroll: function(){
				console.log("scrolled to the end of content.");
				fetchNextPage();
			}
		}
	});
	
	$a(content).hover(function(){
		$a(".top-bar .back").fadeOut('fast');
		$a(".top-bar-ico").fadeIn('slow');
	});
	$a(".top-bar-ico").click(function(){
		$a(this).hide();
		$a(".top-bar .back").fadeIn('fast');
	});
	
	// $a(site).mCustomScrollbar({
		// advanced:{
			// updateOnBrowserResize: true,
			// updateOnContentResize: true
		// },
		// callbacks:{
			// whileScrolling: function(){
				// updateLazyImages();
				//WhileScrolling();		
			// },
			// onTotalScroll: function(){
				// console.log("scrolled to the end of content.");
				// fetchNextPage();
			// }
		// }
	// });
	
	// function updateScrollBar(){
		// var time = '';
		// time = setTimeout(function(){	
			// $a(site).mCustomScrollbar({
				// advanced:{
					// updateOnBrowserResize: true,
					// updateOnContentResize: true
				// },
				// callbacks:{
					// onTotalScrollOffset: 300,
					// whileScrolling: function(){
						// updateLazyImages();
						// WhileScrolling();		
					// },
					// onTotalScroll: function(){
						// console.log("scrolled to the end of content.");
						// fetchNextPage();
					// }
				// }
			// });

			// $a(".photo").fadeIn();
			// updateLazyImages();
			
			// var esq = [];
			// var box = [];
			// var cai = "ul .date";
			// var tot = $a(cai).length;
			
			// $a(cai).each(function(i, obj){
				// esq[i] = $a(this).offset().left;
				// box[i] = obj;
			// });
			
			// function WhileScrolling(){
				// var moveu = mcs.draggerLeft;
				// for(var j = 0; j <= tot; j++){
					// $a(box[j]).html(j);
					// if (moveu > esq[j] -300){
						// $a(box[j]).css({position : 'fixed', left : 150});
					// } else {
						// $a(box[j]).css({position : 'absolute', left : esq[j]});
					// }
				// }
			// }
		// },1000);
	// }
	
	$a(".photo").hover(function(){
		$a(this).find(".back").fadeIn('fast');
		$a(this).find(".time").fadeIn('fast');
		$a(this).find(".more").slideDown('fast');
	}, function(){
		$a(this).find(".more").slideUp('fast');
		$a(this).find(".time").fadeOut('fast');
		$a(this).find(".back").fadeOut('fast');
	});

	
	
	// $a(site).mousemove(function(event) {
		// var posicao = parseInt((event.pageX / $a(window).width()) * 100);
		// if(event.pageX < 45 ) { 
			// $a(".pg-prev").fadeIn("slow");
		// } else {
			// $a(".pg-prev").fadeOut("fast");
		// }
		// if(($a(window).width() - event.pageX) < 50 ) {
			// $a(".pg-next").fadeIn("slow");
		// } else {
			// $a(".pg-next").fadeOut("fast");
		// }
	// });
	// $a(site).mouseout(function(event) {
		// $a(this).mCustomScrollbar("stop");
	// });
	// $a(".pg-prev").hover(function(){
		// site.mCustomScrollbar("scrollTo","left",{ scrollInertia:20000});
	// }, function(){
		// site.mCustomScrollbar("stop");
	// });
	// $a(".pg-next").hover(function(){
		// site.mCustomScrollbar("scrollTo","right",{ scrollInertia:20000});
	// }, function(){
		// site.mCustomScrollbar("stop");
	// });
	
	function updateScrollbar() {
		$a(site).mCustomScrollbar("update");
	}
	
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