// JavaScript Document


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

/*重新发送验证邮件*/
$(function(){
	$("#send-again-m").click(function(event) {
		$(".succes-box-m").fadeIn(600);
		setTimeout(function(){
			$(".succes-box-m").fadeOut(600);
		},3600);
	});
	
});









