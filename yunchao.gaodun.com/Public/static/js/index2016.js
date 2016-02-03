// JavaScript Document

/*  banner */
$(function(){
	
	var oBanner=$("#banner");
	var oWrap=$("#banner >.banner");	
	oWrap.html(oWrap.html()+oWrap.html());	
	var aA=$("#banner >.banner >a");
	var aAsize=aA.length;
	var aW=aA.outerWidth(true);
	oWrap.width( aW * aAsize );
	var bwW=$(window).width();
	var w=aW-(bwW-aW)/2;  /* 偏移量 */
	oWrap.css('left', -w);
	var btnL=$(".btn-l");
	var btnR=$(".btn-r");
	var now=0;
	var now2=0;
	var timer=null;

	$(window).resize(function(event) {
		bwW=$(window).width();
		w=aW-(bwW-aW)/2;  /* 偏移量 */
		oWrap.css('left', -w);
	});

	function creatSpan(){
		for(var i=0;i<aAsize/2;i++){
			$(".point-wrap").append("<span></span>");
		}	
	}

	creatSpan();
	var aSpan=$(".point-wrap >span");
	aSpan.eq(0).addClass('active');

	aSpan.click(function(event) {
		now=now2=$(this).index();
		tab();
	});

	btnL.click(function(event) {
		pre();		
	});

	btnR.click(function(event) {
		next();		
	});
	

	function pre(){
		now--;	
		now2--;	
		if(now<0){
			oWrap.css('left', -w-aAsize/2*aW);
			now=aAsize/2-1;
		}
		if(now2<0){
			now2=2;
		}
		tab();
	}

	function next(){
		now++;
		now2++;
		if(now>aAsize/2){
			oWrap.css('left', -w);
			now=1;
		}	
		if(now2>=aAsize/2){
			now2=0;
		}
		tab();
	}

	function tab(){
		oWrap.stop().animate({left: -w-now*aW}, 500);
		aSpan.removeClass('active');
		aSpan.eq(now2).addClass('active');
	}

	timer=setInterval(next,3000);
	oBanner.hover(function(){
		clearInterval(timer);
		btnL.animate({
			opacity: '100',
			zIndex: '99'
		},300);
		btnR.animate({
			opacity: '100',
			zIndex: '99'
		});
	},function(){
		timer=setInterval(next,3000);
		btnL.animate({
			opacity: '0',
			zIndex: '-1'
		});
		btnR.animate({
			opacity: '0',
			zIndex: '-1'
		},300);
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

/* 企业的第四个*/
$(function(){
	$(".list").each(function() {
		$(this).find('.div-wrap').eq(3).css('margin-right', '0');
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

/* 导航fiexd */
$(function(){
	$(window).scroll(function(event) {
		if($(document).scrollTop()>450){
			$("#menu").css({
				position: 'fixed',
				top: '0',
				zIndex:'999'
			});
		}else{
			$("#menu").css({
				position: 'static',
				zIndex:'999'
			});
		}
	});
});








