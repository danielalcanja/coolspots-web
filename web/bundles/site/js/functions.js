var $a = jQuery.noConflict();
$a(document).ready(function(){
	$a(".page").css({width : $a(window).width(), height : $a(window).height()});
	
	var content = "ul.content";
	var altTotal = $a(content).height();
	var altBox = altTotal - 70;
	var altDis1 = 0; altDis2 = 0, altDis3 = 0, esp = 2;

	altG = Math.round(altBox * (66.66666666666667 / 100));
	altP = Math.round(altBox * (33.17460317460317 / 100));

	altDis2 = altP + esp;
	altDis3 = altDis2 * esp;
	
	if($a(content).hasClass("location") || $a(content).hasClass("event")) { espL = 470; }
	else if($a(content).hasClass("explorer")) { espL = 400; } else { espL = 0; }
	
	var ph = 1, blc = 0, wid = 0, widTotal = 0;;
	var col1 = 0, col2 = 0, col3 = 0, col4 = 0, col5 = 0;
	var comentario = 0, posAtual = 0, totCom = 1, posComent = 0;
	col2 = esp + altP;		col3 = esp + 2 + (2 * altP);		col4 = esp + 4 + (3 * altP);		col5 = esp + 6 + (4 * altP);
	
	$a(".photo").each(function(i){
		if(ph === 1) {
			posAtual = col1 + wid + espL + comentario;
			$a(this).width(altG).height(altG).addClass("phG");
			$a(this).css({ top : altDis1, left : posAtual });
			$a(this).find("img").width(altG).height(altG);
			$a(this).find(".info").width(altG - 20);
			widTotal = widTotal + altG + esp;
			posComent = posAtual + altG;
		}
		if(ph === 2 || ph === 3 || ph === 4 || ph === 5 || ph === 6 || ph === 7 || ph === 8){
			$a(this).width(altP).height(altP).addClass("phP");
			$a(this).find("img").width(altP).height(altP);
			$a(this).find(".info").width(altP - 20);
		}
		if(ph === 2){
			posAtual = col1 + wid + espL + comentario;
			$a(this).css({ top : altDis3, left : posAtual });
		}
		if(ph === 3){
			posAtual = col2 + wid + espL + comentario;
			$a(this).css({ top : altDis3, left : posAtual });
		}
		if(ph === 4){
			posAtual = col3 + wid + espL + comentario;
			$a(this).css({ top : altDis1, left : posAtual });
			widTotal = widTotal + altP + esp;
			posComent = posAtual + altP;
		}
		if(ph === 5){
			posAtual = col3 + wid + espL + comentario;
			$a(this).css({ top : altDis2, left : posAtual });
		}
		if(ph === 6){
			posAtual = col3 + wid + espL + comentario;
			$a(this).css({ top : altDis3, left : posAtual });
		}
		if(ph === 7){
			posAtual = col4 + wid + espL + comentario;
			$a(this).css({ top : altDis1, left : posAtual });
			widTotal = widTotal + altP + esp;
			posComent = posAtual + altP;
		}
		if(ph === 8) {
			posAtual = col5 + wid + espL + comentario;
			$a(this).css({ top : altDis1, left : posAtual });
			widTotal = widTotal + altP + esp;
			posComent = posAtual + altP;
		}
		if(ph === 9){
			posAtual = col4 + wid + espL + comentario;
			$a(this).width(altG).height(altG).addClass("phG");;
			$a(this).css({ top : altDis2, left : posAtual });
			$a(this).find("img").width(altG).height(altG);
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
				widTotal = widTotal + comentario;
				totCom++;
			}
		}
		ph++;
	});
	
	$a(".photo").hover(function(){
		$a(this).find(".back").fadeIn('fast');
		$a(this).find(".time").fadeIn('fast');
		$a(this).find(".more").slideDown('fast');
	}, function(){
		$a(this).find(".more").slideUp('fast');
		$a(this).find(".time").fadeOut('fast');
		$a(this).find(".back").fadeOut('fast');
	});
	
	$a("ul.content").hover(function(){
		$a(".top-bar .back").fadeOut('fast');
		$a(".top-bar-ico").fadeIn('slow');
	});
	$a(".top-bar-ico").click(function(){
		$a(this).hide();
		$a(".top-bar .back").fadeIn('fast');
	});
	
	var content = "";
	time = setTimeout(function(){
		var end = 0;
		if($a(".comentario").hasClass("end")) { end = 20; }
		$a(".create").width(widTotal + espL + end);
		
		content=$a("#site");
		$a("#site").mCustomScrollbar({
			horizontalScroll:true,
			advanced:{
				updateOnBrowserResize: true,
				updateOnContentResize: true
			},
			callbacks:{
				whileScrolling: function(){
					updateLazyImages();
				}
			}
		});
				
		$a(".mCSB_container").height(altTotal);
		$a(".comentario").mCustomScrollbar({
			verticalScroll:true,
			autoHideScrollbar:true
		});
		$a(".photo").fadeIn('slow');
		updateLazyImages();
	},1000);
	
	$a("#site").mousemove(function(event) {
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
	$a("#site").mouseout(function(event) {
		content.mCustomScrollbar("stop");
	});
	$a(".pg-prev").hover(function(){
		content.mCustomScrollbar("scrollTo","left",{ scrollInertia:20000});
	}, function(){
		content.mCustomScrollbar("stop");
	});
	$a(".pg-next").hover(function(){
		content.mCustomScrollbar("scrollTo","right",{ scrollInertia:20000});
	}, function(){
		content.mCustomScrollbar("stop");
	});
	
	//TOP - BAR
	var	shadownBox = ".shadownBox";
	$a(".best").hover(function(){
		$a(this).find("ul").slideToggle('fast');
	});
	$a(".top-ico").hover(function(){
		$a(this).find(".hint").slideToggle('fast');
	});
	
	$a(".add-event").click(function(){
		$a(shadownBox).fadeIn('slow');
	});
	
	//BOTTOM - BAR
	$a(".search").hover(function(){
		$a(".inp-search").animate({ width: 150, paddingLeft: 5, paddingRight: 5 },500).show().focus();
	});
	
	var input = ".inp-search";
	$a("#site").click(function(){
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
	
	//COMENTARIO
	$a(".com a.coment").click(function(){
		AutoScrollOn("left");
	});
	
	//GALLERY
	var	shadown = ".shadown",
		box = ".shadown .box",
		atual = 0,
		pic = [];
	$a(".eve-pic").each(function(index){
		pic[index] 	= $a(this);
		var imagem 	= $a(this).attr("data");
		var user 	= $a(this).find(".username").val();
		var userpic	= $a(this).find(".userpic").val();
		var data_add= $a(this).find(".dateadded").val();
		var name_loc= $a(this).find(".name_location").val();
		
		$a(pic[index]).click(function(){
			$a(shadown).fadeIn('slow');
			$a(".box .img img").attr("src",imagem);
			$a(".author img").attr("src",userpic);
			$a("strong.user").html(user);
			$a("span.data_pic").html(data_add);
			$a("strong.name_location").html(name_loc);
			$a(box).fadeIn('slow');
			atual = index;
			return false;
		});
	});
	$a(document).delegate(".slide-eve-prev", "click", function(){
		$a(pic[atual-1]).click();
	});
	$a(document).delegate(".slide-eve-next", "click", function(){
		$a(pic[atual+1]).click();
	});
	var widthIco = 0;
	$a(document).delegate(".slide-icos", "hover", function(event){
		if (event.type === 'mouseenter') {
			if($a(this).hasClass("slide-fav")) { widthIco = 202; }
			if($a(this).hasClass("slide-lug")) { widthIco = 130; }
			if($a(this).hasClass("slide-com")) { widthIco = 114; }
			$a(this).animate({ width : widthIco}, 200);
		} else {
			widthIco = 29;
			$a(this).animate({ width : widthIco}, 200);
		}
	});
	$a(document).delegate(".slide-close", "click", function(){
		$a(shadown).fadeOut('slow');
	});
	$a(document).delegate(shadown, "click", function(){
		$a(this).fadeOut('slow');
	});
	$a(document).delegate(shadownBox, "click", function(){
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
});
