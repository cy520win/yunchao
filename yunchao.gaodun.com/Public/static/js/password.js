// JavaScript Document


/* 输入框获取焦点*/
$(function(){
	var inputF=$(".input-f");
	inputF.focus(function(){
		$(this).parent().addClass("focused");
	});
	inputF.blur(function(){
		$(this).parent().removeClass("focused");
	});
});


/* 第一步到第二步*/
$(function(){
	$("#one-pas").click(function(event) {
		$(".one").hide();
		$(".two").fadeIn(500);
		$(".move").addClass('moveo-t');
		$(".two li").eq(1).addClass('active');
		$(".move2").addClass('move3');
	});
});

/* 第一步到第二步  手机*/
$(function(){
	$(".get-pas-phone").click(function(event) {
		$(".one-phone").hide();
		$(".two-phone").fadeIn(500);		
	});	
});

/*重新发送验证邮件*/
$(function(){
	$("#send-again").click(function(event) {
		$(".succes-wrap").fadeIn(500);
		$(".succes-box").fadeIn(500);		
	});
	$("#close").click(function(event) {
		$(".succes-wrap").fadeOut(500);
		$(".succes-box").fadeOut(500);		
	});
});



/*重新发送验证邮件 手机*/
$(function(){
	$("#send-again-m").click(function(event) {
		$(".succes-box-m").fadeIn(600);
		setTimeout(function(){
			$(".succes-box-m").fadeOut(600);
		},3600);
	});
	
});



























