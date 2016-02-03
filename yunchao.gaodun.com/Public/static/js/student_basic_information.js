/**
 * Created by wolf on 2015/10/28.
 */

$(function(){
    $(".disable").click(function(event) {
        $(".dis-box").fadeIn(300);
    });
// 简历进度条
function resume_progress(percents){
  var schedule=$(".schedule >span");
  var schedule_percent=$(".schedule .percent");
  var diag_complete=$(".diag-no-complete");

   schedule.css("width",percents);
   schedule_percent.text(percents);
  
  var percent=schedule_percent.text().trim();

//小于50%时
 if(parseInt(percent)<50){
 schedule.css("background","url('Public/Public/static/images/schedule-red.png') repeat-x");
     diag_complete.addClass("diag-complete-red");
}
 //大于50%时小于80%时
 if(parseInt(percent)>=50 && parseInt(percent)<80){
 schedule.css("background","url('Public/Public/static/images/schedule-orange.png') repeat-x");
     diag_complete.addClass("diag-complete-orange");
 }
 //大于80%时   
 if(parseInt(percent)>=80){
    schedule.css("background","url('Public/Public/static/images/schedule.png') repeat-x");
    diag_complete.addClass("diag-complete-blue");
   }
 }
  resume_progress("50%");
    
//提示框hover出现    
  $("#complete-circle").hover(function(){
    $(".diag-no-complete").fadeIn(300);
  },
    function(){
   $(".diag-no-complete").fadeOut(300);
  });
}); 




$(function(){
$(".apperance-diag").hover(
 function(){
 $(this).children("ul").fadeIn(300);
 },function(){
 $(this).children("ul").fadeOut(300);
 }
)
}); 

$(function(){
   var a=1;
   var b=1;

function clickResume(){
    $(".upload-file").on("click",function(event){
        event.stopPropagation();
     if(b){
       $(this).next().fadeIn(50);
       $(".file-resume span.pillow-icon").addClass("delivery-up");
       b=0;
     }else{
       $(this).next().fadeOut(50);
        $(".file-resume span.pillow-icon").removeClass("delivery-up");
         b=1;
       }
  });
}
    clickResume();
$(".choose-content span").on("click",function(){
   var textA=$(this).html()

   $(".file-resume").text(textA).append("<span class='pillow-icon'></span>");

});
$(document).on("click",function(event){
  event.stopPropagation();
     $(".choose-content").fadeOut(50);
        $(".file-resume span").removeClass("delivery-up");
      b=1;
});
 $(".login-mail-certicified").on("click",function(){
     $(".spinner").fadeIn(50);
     $("#login-wrap").fadeIn(50);
     $(".sendsuccess-form").fadeOut(100);
 
 })
//上传简历按钮后
$("#close-send-success , #close-send-failure").on("click",function(){
            $("#login-wrap").hide();
            $(this).parents("div").fadeOut(300);
});
});  
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
//附件名称上传
$(function(){
 var height=$(".upload-file-name span").height();
if(height<40){
  $(".upload-file-name span").css("margin-top","20px");
}
});

//简历安装插件弹框
$(function(){
    var c=1;
 $(".already-set-up").on("click",function(){
   if(c){
     $(this).css("background","url('Public/Public/static/images/account/qx1-box2.png') no-repeat left center");
         c=0;
     }else{
      $(this).css("background","url('Public/Public/static/images/account/qx1-box1.png') no-repeat left center");
         c=1;
     }
     });
 
});