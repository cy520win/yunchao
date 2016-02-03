// JavaScript Document

function bind(obj, ev, fn) {
    if (obj.addEventListener) {
        obj.addEventListener(ev, fn, false);
    } else {
        obj.attachEvent('on' + ev, function() {
            fn.call(obj);
        });
    }
}

/* 阻止默认事件 */
function allFail(ev){
	ev.preventDefault();
}

/* 滚动  */
$(function(){
	var wrapH=document.getElementById('h-menu');
	var hWrap=$(".h-wrap")[0];
	function fnS(ev){
		ev.preventDefault();
	}
	bind(wrapH,"touchmove",fnS);
	bind(hWrap,"touchmove",fnS);
});






/*  公用头部  */
$(function(){
	var hT=true;
	$("#h-right").click(function(event) {
		if(hT){
			$("#h-menu").addClass('to-left');
			$(".h-wrap").css("zIndex","9999").css('opacity', '0.5');
			$("#bar").addClass('active');
			hT=false;
		}else{
			$("#h-menu").removeClass('to-left');
			$(".h-wrap").css('opacity', '0').css("zIndex","-9999");
			$("#bar").removeClass('active');
			hT=true;
		}
		
	});
	var slideH=$(".nav-slide >a").length*$(".nav-slide >a").height()+20;
	/*if(企业){
		slideH=slideH+20;
	}*/
	//var slideH=$(".nav-slide").height();
	var sT=true;
	//$(".nav-slide").height(slideH);
	//
	$("#h-slide-button").click(function(event) {
		if(sT){
			$(".nav-slide").height(slideH);
			$(this).find("i").addClass('active');
			//$(this).css('marginBottom', '15px');
			
			sT=false;
		}else{
			$(".nav-slide").height("0");
			$(this).find("i").removeClass('active');
			//$(this).css('marginBottom', '0');
			sT=true;
		};
	});
});



/* 只显示一个下拉  */
$(function(){
	$('.slide').attr("data-type","true");
});
function showSlide(obj){
	if($('.'+obj+'-slide').attr('data-type')=='true'){
		$('.slide').removeClass('new-show').css({visibility: 'hidden',zIndex: '-999'});/* 所有收起 */
		$('.slide').prev('div').removeClass('focused');/* 所有focused */ 
		$('.slide').prev('div').find('.ipad-triangle').removeClass('active');/* 所有三角 */ 
		$('.slide').attr("data-type","true");

		$('.'+obj).addClass('focused');
		$('.'+obj).find('.ipad-triangle').addClass('active');
		$('.'+obj+'-slide').css({visibility: 'visible',zIndex: '999'}).addClass('new-show');
		$('.'+obj+'-slide').attr('data-type','false');
	}else{
		$('.'+obj).removeClass('focused');
		$('.'+obj).find('.ipad-triangle').removeClass('active');
		$('.'+obj+'-slide').removeClass('new-show').css({visibility: 'hidden',zIndex: '-999'});
		$('.'+obj+'-slide').attr('data-type','true');
	}
	/*$('.slide').each(function(index,obj){
		$(obj).removeClass('new-show');
	});

	$('.'+obj).css({visibility: 'visible',zIndex: '999'}).addClass('new-show');*/
}

/* 下拉显示影藏 */
$(function(){
    var allWrap=$(".all-wrap");
    allWrap.attr('data-type', 'true');
    var allSlide=$(".all-slide");
    allWrap.click(function(event) {
        $(".ipad-triangle").removeClass('active');
        if($(this).attr('data-type')=="true"){

            $(this).find('.ipad-triangle').addClass('active');
            allSlide.css({
                opacity: '0',
                zIndex: '-10'
            });
            $(this).find('.all-slide').css({
                opacity: '1',
                zIndex: '10'
            });
            allWrap.attr('data-type', 'true');
            $(this).attr('data-type', 'false');
        }else{
            $(this).find('.all-slide').css({
                opacity: '0',
                zIndex: '-10'
            });
            $(this).attr('data-type', 'true');
            $(this).find('.ipad-triangle').removeClass('active');
        }
    });


    /* li点击 */
    allSlide.on('click', 'li', function(event) {
        $(this).parent().parent().find('.title-p').text($(this).text());
    });

    $(".time-slide").click(function(event) {
        return false;
    });
});


/* 手机端模块高度切换 */
function setH(){
    var wrapH=$(".phone-tab-wrap").not(':hidden').find(".wrap-height").find('.iphone-div-wrap').not(':hidden').length*40+2;
    $(".wrap-height").height(wrapH);
    var publicWrap=$(".phone-tab-wrap").not(':hidden').find('.iphone-wrap-public');
    publicWrap.click(function(event) {
        publicWrap.removeClass('wrap-height').css('height', '40px');
        $(this).addClass('wrap-height').css('height', wrapH+2);
        setCer();
    });
}



/*证书状态 在读 已读*/
function setCer(){
    var aC=$(".wrap-height").find(".iphone-certificate");
    var aI=$(".wrap-height").find(".iphone-certificate i");
    aC.click(function(event) {
        var curIndex=$(this).index();
        aI.removeClass('active');
        aI.eq(curIndex).addClass('active');
    });
}

$(function(){
    setH();
});




























