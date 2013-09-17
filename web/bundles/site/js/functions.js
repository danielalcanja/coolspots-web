var $a = jQuery.noConflict();
$a(document).ready(function(){
	var content = "ul.content";
	var altTotal = $a(content).height();
	var altBox = altTotal - 70;
	var altDis1 = 0; altDis2 = 0, altDis3 = 0, esp = 2;

	altG = Math.round(altBox * (66.66666666666667 / 100));
	altP = Math.round(altBox * (33.17460317460317 / 100));

	altDis2 = altP + esp;
	altDis3 = altDis2 * esp;
	
	if($a(content).hasClass("location")) { espL = 470; } else { espL = 0; }
	
	var ph = 1, blc = 0, wid = 0, widTotal = 0;;
	var col1 = 0, col2 = 0, col3 = 0, col4 = 0, col5 = 0;
	col2 = esp + altP;		col3 = esp + 2 + (2 * altP);		col4 = esp + 4 + (3 * altP);		col5 = esp + 6 + (4 * altP);
	
	$a(".photo").each(function(i){
		if(ph == 1) {
			$a(this).width(altG).height(altG).addClass("phG");
			$a(this).css({ top : altDis1, left : col1 + wid + espL });
			$a(this).find("img").width(altG).height(altG);
			$a(this).find(".info").width(altG - 20);
			widTotal = widTotal + altG;
		}
		if(ph == 2 || ph == 3 || ph == 4 || ph == 5 || ph == 6 || ph == 7 || ph == 8){
			$a(this).width(altP).height(altP).addClass("phP");;
			$a(this).find("img").width(altP).height(altP);
			$a(this).find(".info").width(altP - 20);
		}
		if(ph == 2){
			$a(this).css({ top : altDis3, left : col1 + wid + espL });
			left = altP + 2;
		}
		if(ph == 3){
			$a(this).css({ top : altDis3, left : col2 + wid + espL });
		}
		if(ph == 4){
			$a(this).css({ top : altDis1, left : col3 + wid + espL });
			widTotal = widTotal + altP;
		}
		if(ph == 5){
			$a(this).css({ top : altDis2, left : col3 + wid + espL });
		}
		if(ph == 6){
			$a(this).css({ top : altDis3, left : col3 + wid + espL });
		}
		if(ph == 7){
			$a(this).css({ top : altDis1, left : col4 + wid + espL });
			widTotal = widTotal + altP;
		}
		if(ph == 8) {
			$a(this).css({ top : altDis1, left : col5 + wid + espL});
			widTotal = widTotal + altP;
		}
		if(ph == 9){
			$a(this).width(altG).height(altG).addClass("phG");;
			$a(this).css({ top : altDis2, left : col4 + wid + espL });
			$a(this).find("img").width(altG).height(altG);
			$a(this).find(".info").width(altG - 20);
			ph = 0;
			blc++;
			wid = blc * (col5 + altP + 2)
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
		$a(".top-bar").fadeOut('slow');
		// $a("div.loc .date").hide();
		// $a("div.loc").animate({ width : 440 });
	}, function(){
		$a(".top-bar").fadeIn('fast');
		// $a("div.loc").animate({ width : 680 });
		// $a("div.loc .date").fadeIn(1500);
	});
	
	var time = "";
	time = setTimeout(function(){
		$a(".create").width(widTotal + espL);
		$a("#site").mCustomScrollbar({horizontalScroll:true});
		$a(".mCSB_container").height(altTotal);
		$a(".photo").fadeIn('slow');
	},1000);
	
	$a(".inp-postar").focus(function(){
		if($a(this).val()=="aonde você vai hoje?"){
			$a(this).val("");
		}
		$a(this).animate({height : 60}, 'fast');
		$a(".topo-input").animate({paddingBottom : 8}, 'fast');
	});
	$a(".inp-postar").blur(function(){
		if($a(this).val()==""){
			$a(this).val("aonde você vai hoje?");
		}
		$a(this).animate({height : 30}, 'fast');
		$a(".topo-input").animate({paddingBottom : 0}, 'fast');
	});
	$a(".best").hover(function(){
		$a(this).find("ul").slideToggle('fast');
	});
	$a(".top-ico").hover(function(){
		$a(this).find(".hint").slideToggle('fast');
	});
	
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
		
		$a(pic[index]).click(function(){
			$a(shadown).fadeIn('slow');
			$a(".box .img img").attr("src",imagem);
			$a(".author img").attr("src",userpic);
			$a("strong.user").html(user);
			$a("span.data_pic").html(data_add);
			$a(box).fadeIn('slow');
			atual = index;
			return false;
		});
	});
	// $(".slide-eve-back").click(function(){
		// $(pic[atual-1]).click();
	// });
	// $(".slide-eve-next").click(function(){
		// $(pic[atual+1]).click();
	// });
	$a(document).delegate(shadown, "click", function(){
		$a(this).fadeOut('slow');
	});
	$a(document).delegate(box, "click", function(){
		return false;
	});
});