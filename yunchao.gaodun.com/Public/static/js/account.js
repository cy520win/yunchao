// JavaScript Document

/*找实习 招人切换 大于992*/
$(function(){
	var aLi=$("#ul >li");
	var aForm=$(".form");
	var otherDiv=$(".other-div");
	aLi.click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		var curIndex=$(this).index();
		aForm.eq(curIndex).fadeIn(300).siblings("div").hide();
		if(aForm.eq(0).css("display")=="block"){
			otherDiv.fadeIn(300);
		}
		else{
			otherDiv.hide();
		}
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

/*用户协议单选*/
$(function(){
	var checked=$(".user-protocol >span");
	var checkedT=true;
	checked.click(function(){
		if(checkedT){
			$(this).removeClass("checked");
			$(this).siblings(".input-error").show();
			checkedT=false;
		}else{
			$(this).addClass("checked");
			$(this).siblings(".input-error").hide();
			checkedT=true;
		}
	});
});

/*记住密码单选*/
$(function(){
	var checked=$(".auto-login >span");
	var checkedT=true;
	checked.click(function(){
		if(checkedT){
			$(this).removeClass("checked");
			checkedT=false;
		}else{
			$(this).addClass("checked");
			checkedT=true;
		}
	});
});


/*注册登录切换 大于992*/
$(function(){
	var directLogin=$(".direct-login");
	var directRegistration=$(".direct-registration");
	var registWrap=$(".regist-wrap");
	var loginWrap=$(".login-wrap");
	directLogin.click(function(){
		registWrap.hide();
		loginWrap.fadeIn(300);

	});
	directRegistration.click(function(){
		loginWrap.hide();
		registWrap.fadeIn(300);

	});

});

/*注册登录切换 992--768*/
$(function(){
	var directLoginIpad=$(".direct-login-ipad");
	var directRegistrationIpad=$(".direct-registration-ipad");
	var registWrapIpad=$(".regist-wrap-ipad");
	var loginWrapIpad=$(".login-wrap-ipad");
	directLoginIpad.click(function(){
		registWrapIpad.hide();
		loginWrapIpad.fadeIn(300);

	});
	directRegistrationIpad.click(function(){
		loginWrapIpad.hide();
		registWrapIpad.fadeIn(300);

	});
});


/*找实习 招人切换 992--768*/
$(function(){
	var aLi=$("#ul >li");
	var aForm=$(".form-ipad");
	var otherDiv=$(".other-div");
	aLi.click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		var curIndex=$(this).index();
		aForm.eq(curIndex).fadeIn(300).siblings("div").hide();
		if(aForm.eq(0).css("display")=="block"){
			otherDiv.fadeIn(300);
		}
		else{
			otherDiv.hide();
		}
	});	
});



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




/*注册登录切换 小于768*/
$(function(){
	var directLoginPhone=$(".direct-login-phone");
	var directRegistrationPhone=$(".direct-registration-phone");
	var registWrapPhone=$(".phone-wrap-regist");
	var loginWrapPhone=$(".phone-wrap-login");
	directLoginPhone.click(function(){
		registWrapPhone.hide();
		loginWrapPhone.fadeIn(300);

	});
	directRegistrationPhone.click(function(){
		loginWrapPhone.hide();
		registWrapPhone.fadeIn(300);

	});
});


//事件绑定
function myAddEvent(obj, sEvent, fn)
{
	if(obj.attachEvent)
	{
		obj.attachEvent('on'+sEvent, fn);
	}
	else
	{
		obj.addEventListener(sEvent, fn, false);
	}
}

/* 用户协议显示影藏 */
$(function(){
	var upt=$(".user-protocol-t");
	var closeX=$(".close-x");
	upt.click(function(event) {
		$(".user-protocol-mask").fadeIn(600);
		$(".user-protocol-box").fadeIn(600);
	});
	closeX.click(function(event) {
		$(".user-protocol-mask").fadeOut(600);
		$(".user-protocol-box").fadeOut(600);
	});
});


/* 手机 学生企业注册切换 */

$(function(){
	var aLiR=$("#regist-tab >li");
	var aDivR=$(".form-phone-div");
	aLiR.click(function(event) {
		$(this).addClass('active').siblings('li').removeClass('active');
		var curIndexR=$(this).index();
		aDivR.hide();
		aDivR.eq(curIndexR).fadeIn(300);

	});
});
































