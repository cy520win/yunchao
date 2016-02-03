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
                    $(".feed-span").fadeIn("500");
					$('.feed-span2').slideDown();
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
// 返回顶部开始
$(function(){
$(window).scroll(function(){  //只要窗口滚动,就触发下面代码 
var scrollt = document.documentElement.scrollTop + document.body.scrollTop; //获取滚动后的高度 
if( scrollt >600){  //判断滚动后高度超过200px,就显示  
$("#return-up-area").fadeIn(100); //淡出     
}else{      
$("#return-up-area").stop().fadeOut(100); //如果返回或者没有超过,就淡入.必须加上stop()停止之前动画,否则会出现闪动   
}
});
	$("#return-up-area").click(function(){ //当点击标签的时候,使用animate在200毫秒的时间内,滚到顶部
		$("#module-menu").removeClass("fided");
		$("html,body").animate({scrollTop:"0px"},200);
	});
});

$(function(){
    $(".feed-span").on("click",function(){
        $(this).css("display","none");
        $(".feedback-box").fadeIn("500");
    });
    $(".feedback-close").on("click",function(){
        $(".feedback-box").fadeOut("500");
        $(".feed-span").fadeIn("500");
    });
});