// JavaScript Document

/* 移动端密码眼睛*/
$(function(){
  var eye=$(".eye-m");
  var eyeT=true;
  eye.click(function(event) {
    if(eyeT){
      $(this).addClass("eye");
      eyeT=false;
    }else{
      $(this).removeClass("eye");
      eyeT=true;
    }
    event.stopPropagation();
  });
});


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

/* 重置成功 5秒返回首页*/

$(function(){
	$("#one-pas").click(function(event) {
		$("#password").hide();
		$(".three").hide();
		$(".main-top >img").fadeIn(600).css("display","block");
		$(".reset-success").fadeIn(600);
	});
});


/*重置成功 5秒返回首页 手机*/
$(function(){
	$(".get-pas-phone").click(function(event) {
		$(".succes-box-m").fadeIn(600);
		setTimeout(function(){
			$(".succes-box-m").fadeOut(600);
		},3600);
	});
	
});























