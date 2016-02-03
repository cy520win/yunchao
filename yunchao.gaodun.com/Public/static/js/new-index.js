// JavaScript Document

addLoadEvent(feedback);
function feedback(){
	var oS=document.getElementById("feedback-send");
	var oT=document.getElementById("textare");
	var oV=oT.value;
	oT.onblur =function(){
		if(oT.value.length > 0 && oT.value != oV){
			oS.className="feedback-send send-hover";
		}
		else{
			oT.value=oV;
			oS.className="feedback-send";
		}
	}
	oT.onkeyup =function(){

		var maxL=200;

		if(oT.value.length > 0 && oT.value != oV){
			oS.className="feedback-send send-hover";
		}

		/*限制字数*/
		if(oT.value.length>maxL){
			oT.value=oT.value.substring(0,maxL);
		}
	}
}

function addLoadEvent(fn){
	var oldLoad=window.onload;
	if(typeof window.onload!='function'){
		window.onload=fn;
	}else{
		window.onload=function(){
			oldLoad();
			fn();
		};
	}
}

function logout(){
	var status = WB2.checkLogin();
	if(status){
			WB2.logout(function(){
				window.location.href = "/Account/logout";
			});
	}
	if(!status){
		$.get('/Account/out',function(data){
			window.location.reload();
		},'json')
	}
}

<!-- 搜索 -->
$(function(){
    var sT=true;
    var serchLeft=$(".serch-left");
    var oSpan=$(".serch-left >span");
    var oUl=$("#serch-slide");
    var aLi=$("#serch-slide >li");
    var oP=$(".serch-left >p")
    var tmp="";
    var tmpPlace="";
    var tmpSearchId="";
    serchLeft.click(function(event) {
        if(sT){
            oUl.height("90px");
            sT=false;
            oSpan.addClass('active');
        }else{
            oUl.height("0px");
            oSpan.removeClass('active');
            sT=true;
        }
        return false;
    });
    aLi.click(function(event) {
        oUl.height("0px");
        oSpan.removeClass('active');
        sT=true;
        tmp=oP.text();
        tmpPlace=oP.attr('data-item')
        tmpSearchId=oP.attr('data-value')
        oP.attr('data-item',$(this).attr('data-item'));
        oP.attr('data-value',$(this).attr('data-value'));
        oP.text($(this).text());
        $('#phone-keyword').attr('placeholder',$(this).attr('data-item'));
        $(this).text(tmp);
        $(this).attr('data-item',tmpPlace);
        $(this).attr('data-value',tmpSearchId);
        return false;
    });
    $(document).click(function(event) {
        oUl.css('height', '0');
        if(!sT){
            oSpan.removeClass('active');
        }
        sT=true;
    });
});

<!--按回车加载seacher键-->
function tab(){
	if(event.keyCode==13)
	{
		document.getElementById("searcher-button").click();    
		return false;
	}
}


/* 返回顶部 */
$(function(){
	$(window).scroll(function(){  //只要窗口滚动,就触发下面代码
		var scrollt = document.documentElement.scrollTop + document.body.scrollTop; //获取滚动后的高度
		if( scrollt >600){  //判断滚动后高度超过200px,就显示
			$("#return-up-area").fadeIn(100); //淡出
		}else{
			$("#return-up-area").stop().fadeOut(100); //如果返回或者没有超过,就淡入.必须加上stop()停止之前动画,否则会出闪动
		}
	});
	$("#return-up-area").click(function(){ //当点击标签的时候,使用animate在200毫秒的时间内,滚到部
		$("html,body").animate({scrollTop:"0px"},200);
	});
});


/* 用户名点击 下拉 */
$(function(){
	var userHeader=$(".user-header");
	var oUlh=$(".user-header >ul");
	var ulHh=$(".user-header >ul >li").length*40+40;

	$(".user-header").mouseover(function(){

		oUlh.css("height",ulHh);
	});
	$(".user-header").mouseout(function(){
		oUlh.css("height",0);
	});
});

/* ie8提示 */
$(function(){
    var user1=window.navigator.userAgent;
    var re1=/MSIE 8.0|MSIE 7.0|MSIE 6.0/;
    if(user1.search(re1)>0){
        $(".nav").addClass("ie8nav");
    }
});


<!--ie8 banner-->

addLoadEvent(ie8banner);
addLoadEvent(sb);
function addLoadEvent(fn){
	var oldLoad=window.onload;
	if(typeof window.onload!='function'){
		window.onload=fn;
	}else{
		window.onload=function(){
			oldLoad();
			fn();
		};
	}
}

<!--轮播图手柄渐变-->
function sb(){
	var buttonnext=document.getElementById("button-next");
	var buttonprev=document.getElementById("button-prev");
	var bB=document.getElementById("banner-box");
	bB.onmouseover=function(){
		move(buttonnext,{opacity:100});
		move(buttonprev,{opacity:100});
	}
	bB.onmouseout=function(){
		move(buttonnext,{opacity:0});
		move(buttonprev,{opacity:0});
	}
}


function ie8banner() {
        var bannerBox=document.getElementById("banner-box");
        var wrap=document.getElementById("wrap");
        var ie8Prompt=document.getElementById("ie8-prompt");
        var user=window.navigator.userAgent;
	var re=/MSIE 9.0|MSIE 8.0|MSIE 7.0|MSIE 6.0/;
	
	var browser = {
            versions: function () {
                var u = navigator.userAgent, app = navigator.appVersion;
                return {
                    trident: u.indexOf('Trident') > -1,
                    presto: u.indexOf('Presto') > -1,
                    webKit: u.indexOf('AppleWebKit') > -1,
                    gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,
                    mobile: !!u.match(/AppleWebKit.*Mobile.*/) || !!u.match(/AppleWebKit/),
                    ios: !!u.match(/i[^;]+;( U;)? CPU.+Mac OS X/),
                    android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1,
                    iPhone: u.indexOf('iPhone') > -1 || (u.indexOf('Mac') > -1 && u.indexOf('Macintosh') < 0), 
                    iPad: u.indexOf('iPad') > -1,
                    webApp: u.indexOf('Safari') == -1
                };
            } (),
            language: (navigator.browserLanguage || navigator.language).toLowerCase()
        }
		//如果移动端
        if (browser.versions.ios || browser.versions.android || browser.versions.iPhone || browser.versions.iPad) {
            wrap.style.display="none";
			bannerBox.style.display="block";
        }else{ //PC端
			wrap.style.display="block";
			bannerBox.style.display="none";
			
			//如果ie9以下
			if(user.search(re)>0){
				ie8Prompt.style.display="block";
			}else{
				ie8Prompt.style.display="none";
			}
        }

        var wrap=document.getElementById("wrap");
        var wrapImg=document.getElementById("wrap-img");
        var aA=wrapImg.getElementsByTagName("a");
        wrapImg.innerHTML+=wrapImg.innerHTML//设置2层img
        wrapImg.style.width=aA.length*640+"px";//设置外层宽度
        var oWidth=document.documentElement.clientWidth;//显示器宽度screen
        var homeButton=document.getElementById("home-button");
        var preHome=homeButton.getElementsByTagName("span")[0];
        var nextHome=homeButton.getElementsByTagName("span")[1];
        var now=0;
        var now2=0;
        var oW=0;//左边偏移的宽度
        var timer=null;

        oW=Math.abs((1920-oWidth)/2);
        wrapImg.style.left=-oW+"px";


        //创建span
        function creatSpan(){
            var homePoint=document.getElementById("home-point");
            for(var i=0;i<aA.length/2;i++){
                var oSpan=document.createElement('span');
                homePoint.appendChild(oSpan);
            }
        }
        creatSpan();

        //获取点点点数量
	var hp=document.getElementById("home-point");
	var hpw=document.getElementById("home-point-wrap");
	var pointSpan=hp.getElementsByTagName("span");
	hp.style.width=pointSpan.length*25+"px";
	hpw.style.width=pointSpan.length*25+"px";

        //点点点样式
        pointSpan[0].className="point-active";

        //图片蒙版

        //aA[1].className="opat";


        for(var i=0;i<pointSpan.length;i++){
         pointSpan[i].index=i;
            pointSpan[i].onclick=function(){
                now2=this.index;
                now=this.index;
                tab1();
                return false;
            }
         }

        function tab1(){

            for(var i=0;i<pointSpan.length;i++){
                pointSpan[i].className="";
            }
            pointSpan[now2].className="point-active";

            for(var i=0;i<aA.length;i++){
            	aA[i].className="";
            }
            aA[now+1].className="opat";
            oW=Math.floor(oW);
            move(wrapImg,{left:-now*640-oW});
        };


        function pre(){
            if(now == aA.length/2){
                wrapImg.style.left=-oW+"px";
                now=0;
            }
            now++;
            now2++;
            if( now2 >= pointSpan.length){ now2=0;}
            tab1();
        };

        function next(){
            if(now == 0){
                //aA.length/2
                wrapImg.style.left=-aA.length/2*640-oW+"px";
                now=aA.length/2;
            }
            now--;
            now2--;
            if(now2<=-1){ now2=pointSpan.length-1;}
            tab1();
        };

        preHome.onclick=function(){
            pre();
        };
        nextHome.onclick=function(){
            next();
        };
        wrap.onmouseover=function(){
            clearInterval(timer);
            homeButton.style.zIndex=999;
            move(homeButton,{opacity:100});
        }

        wrap.onmouseout=function(){
            timer=setInterval(next,5000);
            move(homeButton,{opacity:0},function(){
                homeButton.style.zIndex=-1;
            });
        }
        timer=setInterval(next,5000);
    }


/* 信息反馈 */
$(function(){
	$(".message-back").on("click",function(){
		$(this).css("display","none");
		$(".feedback-box").fadeIn("500");
	});
	$(".feedback-close").on("click",function(){
		$(".feedback-box").fadeOut("500");
		$(".message-back").fadeIn("500");
	});
});


$(function(){
	var a=true;
	$("#login-header").on("click",function(){
		if(a){
			$(".slide-menu").slideDown("fast");
			$(".panel-slide-logo").attr("src","Public/static/images/index/slide-bottom.png");
			a=false;
		}else{
			$(".slide-menu").slideUp("fast");
			$(".panel-slide-logo").attr("src","Public/static/images/index/slide-up.png");
			a=true;
		}

	});
});



/* seach */
function searchbtn(obj){
	var i=$(obj).attr('data-value');
	var t=$(obj).text();
	var p=$(obj).attr('data-item');
	var m=$('#search-model').attr('data-value');
	var v=$('#search-model>span').eq(0).text();
	var x=$('#search-model').attr('data-item');
	$('#search-model').attr('data-value',i);
	$('#search-model>span').eq(0).text(t);
	$('#search-model').attr('data-item',p);
	$('#keyword').attr('placeholder',p);
	$(obj).attr('data-value',m);
	$(obj).attr('data-item',x);
	$(obj).text(v);
}

function searchform(){
    var keyword = screenType() == 'phone' ? $('#phone-keyword').val() : $('#keyword').val();
    var key = screenType() == 'phone' ? $('#phone-search-id').attr('data-value') : $('#search-model').attr('data-value');

    if(ignoreSpaces(keyword)){
        window.location.href = '/Post/index/key/'+parseInt(key)+'/keyword/'+ignoreSpaces(keyword);
    }else{
        window.location.href = '/Post/index'
    }
}

function ignoreSpaces(string) {
	var temp = "";
	string = '' + string;
	splitstring = string.split(" ");
	for(i = 0; i < splitstring.length; i++)
		temp += splitstring[i];
	return temp;
}



addLoadEvent(feedback);
function feedback(){
	var oS=document.getElementById("feedback-send");
	var oT=document.getElementById("textare");
	var oV=oT.value;
	oT.onblur =function(){
		if(oT.value.length > 0 && oT.value != oV){
			oS.className="feedback-send send-hover";
		}
		else{
			oT.value=oV;
			oS.className="feedback-send";
		}
	}
	oT.onkeyup =function(){
		var maxL=200;
		if(oT.value.length > 0 && oT.value != oV){
			oS.className="feedback-send send-hover";
		}
		/*限制字数*/
		if(oT.value.length>maxL){
			oT.value=oT.value.substring(0,maxL);
		}
	}
}

function delAdImg(obj){
	$(obj).parent().remove();
	$.post('/Upload/deleteImg',{'img' : $(obj).next('input[type=hidden]').val()});
	$('#upload-advice').removeAttr('disabled');
	$('.upload-right').css({'color':'#a5a5a5'});
	if($('.add-img>p').length<1){
		$('#feedback-send').removeClass('send-hover');
	}
}

function uploadAd(obj){
	if($('.add-img>p').length>=3){
		$('#feed-error').html('最多三个文件');
		$('#upload-advice').attr('disabled','disabled');
		return false;
	}else{
		$('#upload-advice').removeAttr('disabled');
	}
	$.ajaxFileUpload({
		url:"/Upload/uploadAdvice",
		secureuri :false,
		type: 'post',
		fileElementId :'upload-advice',
		dataType : 'json',
		success : function (data,status){
			if(data.status=='success'){
				$('.add-img').append('<p>'+$(obj).val()+'<span onclick=\"delAdImg(this)\">删除</span><input type=\"hidden\" name=\"img[]\" value=\"'+data.path+'\" class=\"advimg \"></p>');
				$('#feedback-send').addClass('send-hover');
				if($('.add-img>p').length>=3){
					$('#upload-advice').attr('disabled','disabled');
					$('.upload-right').css({'color':'#ff5d38'});
				}
				$('#feed-error').html('');
			}else{
				$('#feed-error').html(data.msg);
			}
		},
		error: function(data, status, e){}
	})
	return false;
}

function addAdvice(){
	var text = $('.textarea-feedback').val();
	var image='';
	$('.advimg').each(function(){
		image += $(this).val()+'&';
	});
	var email = $('input[name=email]').val();
	$.post('/Help/addAdvice',{'text':text,'image':image,'email':email},function(data){
		if(data.status){
			$('#feedback-box').css('display','none');
			$('.feed-span2').slideDown();
			$('.message-back').show();
			setTimeout('delFD2()',4000);
		}else{
			$('#feed-error').html(data.msg);
			// $('.feedback-input').addClass('input-border-error');
		}
	},'json')
	return false;
}

function feedclose(){
	$('#feedback-box').fadeOut(500);
	$('.feedback-box').fadeOut(500);
}

function delFD2(){
	$('.feed-span2').slideUp();
}

function feedshow(){
	$('#feedback-box').fadeIn(500);
	$('.feedback-box').fadeIn(500);
	$('#textare').val('');
	$('.add-img').html('');
	$('.feedback-input').val('');
	$('#feed-error').html('');
}

$(function(){
	$('#keyword').focus(function(){
		$(this).attr('placeholder','');
	})

	$('#keyword').blur(function(){
		if($(this).val() == ''){
			$(this).attr('placeholder',$('#search-model').attr('data-item'));
		}
	})
})

window.onresize=function(){
	$(function(){
		var mc=document.body.offsetWidth;
		if(mc<748){
			$("#return-up-area").children("img").attr("src","Public/static/images/up-medium.png");
			$('.message-back').children("img").attr("src","Public/static/images/deliver-medium.png");
		}else{
			$("#return-up-area").children("img").attr("src","Public/static/images/index/bottom-up.png");
			$('.message-back').children("img").attr("src","Public/static/images/feed-advice.png");
		}
	});
}

