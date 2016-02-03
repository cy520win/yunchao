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





















































































































