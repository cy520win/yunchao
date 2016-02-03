// JavaScript Document

/* 可留用 */
$(function(){
	$(".title-l").click(function(event) {
		$(this).find('span').toggleClass('active');
	});
});

/* 更多 */
$(function(){
	$(".more-p").on('mousemove', function(event) {
		event.preventDefault();
		$(this).parent(".screen-list-wrap").addClass('active');
		$(this).next(".screen-more").show();
		$(this).find('span').addClass('active');
		$(".read-more").hide();
		$(".screen-list-wrap.active").on('mouseleave', function(event) {
			event.preventDefault();
			$(this).removeClass('active');
			$(".screen-more").hide();
			$('.more-p').find('span').removeClass('active');
			$(".read-more").show();

		});
	});

	$(".screen-more >a").click(function(event) {
		$(".last-a").addClass('active').text($(this).text());
	});

	var bTrue=true;
	$(".read-more >p").click(function(event) {
		if(bTrue){
			$(this).html("收起 <span></span>");
			$(this).find('span').addClass('active');
			$(".be-hide").show();
			bTrue=false;
		}else{
			$(this).html("更多 <span></span>");
			$(this).find('span').removeClass('active');
			$(".be-hide").hide();
			bTrue=true;
		}
	});


});

/* 职位企业切换 */

$(function(){
	var aLi=$(".search-result >ul >li");
	var aRe=$(".result");
	aLi.click(function(event) {
		var curIndex=$(this).index();
		aLi.eq(curIndex).addClass('active').siblings('li').removeClass('active');
		aRe.eq(curIndex).show().siblings('.result').hide();
	});
});

