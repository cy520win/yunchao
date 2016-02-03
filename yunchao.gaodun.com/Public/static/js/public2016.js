/* 回到顶部相关 */
$(function(){
	$(".btn-top").hover(function() {
		$(".span-top").fadeIn(300);
	}, function() {
		$(".span-top").fadeOut(300);
	});

	$(".btn-feedback").hover(function() {
		$(".span-feedback").fadeIn(300);
	}, function() {
		$(".span-feedback").fadeOut(300);
	});

	$(".btn-top").click(function(event) {
		$("html,body").animate({scrollTop:"0px"},300);
	});

	$(window).scroll(function(event) {
		if($(document).scrollTop()>600){
			$(".btn-top").fadeIn(300);
		}else{
			$(".btn-top").fadeOut(300);
		}
	});	
});


/* 下拉 */
$(function(){
	var aSize=$('.slide >a:visible').length;
	var oH=$('.slide >a').height();
	$(".user").hover(function() {
		$(".slide").stop().animate({height: aSize*oH}, 300);
	}, function() {
		$(".slide").stop().animate({height: 0}, 300);
	});
});


/* 搜索下拉 */
$(function(){
	var curIndex=-1;
	var oSlide=$(".search-slide").get(0);
	var aA=oSlide.getElementsByTagName("a"); 
	var aAj=$(".search-slide >a");
	var aSize=$(".search-slide a").length;
	$("#search").keydown(function(event) {
		if($(this).val().length>1){
			$(".search-slide").show();
		}else{
			$(".search-slide").hide();
		}
		if(event.keyCode==38){
			curIndex--;
			if(curIndex<=-1){
				curIndex=aSize-1;
			}
			setBgc();
		}else if(event.keyCode==40){
			curIndex++;	
			if(curIndex>=aSize)	{
				curIndex=0;
			}
			setBgc();	
		}else if(event.keyCode==13){	
			//$(".search-slide a.active >p").text()	
			$(this).val($(".search-slide a.active >p").text());
		}
		
	});

	$("#search").click(function(event) {
		return false;
	});

	for(var i=0;i<aSize;i++){
		aA[i].index=i;
		aA[i].onmouseover=function(){
			curIndex=this.index;
			for(var i=0;i<aSize;i++){
				aA[i].className="";				
			}	
			aA[curIndex].className="active";	
		}
		
		aA[i].onmouseoout=function(){
			for(var i=0;i<aSize;i++){
				aA[i].className="";				
			}	
		}
	}

	function setBgc(){
		for(var i=0;i<aSize;i++){
			aA[i].className='';
		}
		aA[curIndex].className='active';
	}

	$(document).click(function(event) {
		$(".search-slide").hide();
	});

	/*$(document).keydown(function(event) {
		alert(event.keyCode);
	});*/
	
});


