$(function(){
	$(window).scroll(function(event) {
		console.log($(document).scrollTop());
		if($(document).scrollTop() >= 100 ){
			$(".message-r-t").css({
				position: 'fixed',
				top: '90px'
			});
		}else{
			$(".message-r-t").css({
				position: 'relative',
				top: '0'
			});
		};		
	});


	//li hover
	(function(){
		var ali=$(".message-r-m >ul >li");
		$('.message-r-m').on("mouseover","li",function(event){
			$(this).addClass('active');
		})
		$('.message-r-m').on("mouseout","li",function(event){
			$(this).removeClass('active');
		})
	})();

	//全选 单选
	(function(){
		var aDx=$(".dx-box");
		aDx.attr('data-type', 'true');
		$('.qx-box').click(function(event) {
			var qxTrue=$('.check-all').attr('data-type');
			if(qxTrue=='true'){
				$(this).addClass('active');
				$(".dx-box").addClass('active');
				$(this).attr('data-type','false');
				$(".dx-box").attr('data-type', 'false');
			}
			if(qxTrue=='false'){
				$(this).removeClass('active');
				$(".dx-box").removeClass('active');
				$(this).attr('data-type','true');
				$(".dx-box").attr('data-type', 'true');
			}
		});

		$(".message-r-m").on("click",".dx-box",function(event) {
			if($(this).attr('data-type')=='true'){
				$(this).addClass('active');
				$(this).attr('data-type', 'false');
			}else{
				$(this).removeClass('active');
				$(this).attr('data-type', 'true');
			}
		});
	})();

	//删除
	(function(){
		$('.del-box').click(function(event) {		
			if($("span[data-type= false]").length){// false代表选中			
				$('.mes-have').fadeIn(300);
				$('.mes-wrap').show();
			}else{
				$('.mes-no').fadeIn(300);
				$('.mes-wrap').show();
				setTimeout(function(){
					$('.mes-no').fadeOut(300);
					$('.mes-wrap').hide();
				},2300);
			}
		});

		$('.mes-h-c').click(function(event) {
			$('.mes-have').fadeOut(300);
			$('.mes-wrap').hide();
		});
	})();

	//单条删除
	(function(){
		var ali=$(".del-li");
		
		$(".message-r-m").on("click",".del-li",function(event) {
			$(this).parents('li').find('.mes-one').fadeIn(300);
		});
		$('.message-r-m').on('click',".mes-h-c",function(event){
			$('.mes-one').fadeOut(300);
		})
	})();


	//设置左侧最小高度
	(function(){
		var mH=$(document).height()-220;
		$('.message-r').css('min-height',mH);
	})();


	//详情
	(function(){
		$('.del-box').click(function(event) {	
			$('.mes-detail').fadeIn(300);
		});

		$(".mes-h-c").click(function(event) {
			$('.mes-detail').fadeOut(300);
		});
	})();
	


});