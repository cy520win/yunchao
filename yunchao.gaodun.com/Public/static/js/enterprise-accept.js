 $(function(){
    var oUlh=$(".user-header >ul");
    var ulHh=$(".user-header >ul >li").length*40+40;

    $(".user-header").mouseover(function(){

        oUlh.css("height",ulHh);
    });
    $(".user-header").mouseout(function(){
        oUlh.css("height",0);
    });
        });

//淡入淡出弹出框
$(function(){
$(".waiting").on("click",function(event){
 event.stopPropagation();
    $(this).parent(".list-right").next().next().addClass("is-visible");
});    
$(".unsuitable").on("click",function(event){
 event.stopPropagation();
    $(this).parent(".list-right").next().next().next().next().addClass("is-visible");
});
$(".first-ms").on("click",function(event){
 event.stopPropagation();
    $(this).parent(".list-right").next().next().next().addClass("is-visible");
});
    
$(".permit-interview-box .no,.refuse-interview-box .no").on("click",function(event){
    event.stopPropagation();
    $(this).parent().removeClass("is-visible");
});
    
 $(document).on("click",function(){
 event.stopPropagation();
     $(".permit-interview-box,.refuse-interview-box").removeClass("is-visible");
});

});

$(function(){
$(".permit-meeting").on("click",function(event){
 event.stopPropagation();
    $(".fadein-content").children().eq(0).addClass("is-visible");
});
$(".unsuitables").on("click",function(event){
 event.stopPropagation();
    $(".fadein-content").children().eq(1).addClass("is-visible");
});
    
$(".permit-interview-box .no,.refuse-interview-box .no").on("click",function(event){
    event.stopPropagation();
    $(this).parent().removeClass("is-visible");
});
    
 $(document).on("click",function(){
 event.stopPropagation();
     $(".permit-interview-box,.refuse-interview-box").removeClass("is-visible");
});

}); 

//全选
$(function(){
    var a=true;

  $(".all-choose-button").on("click",function(){
     if(a){
      $(this).addClass("choosed");
      $(".list-left .choosed-button").addClass("choosed");
         a=false;
     }else{
      $(this).removeClass("choosed"); 
     $(".list-left .choosed-button").removeClass("choosed");
         a=true;
       
     }
  });
  $(".list-left .choosed-button").on("click",function(){
   
      $(this).toggleClass("choosed");
       
});
});


/* 彈框 */

$(function(){

 //允许面试 qx
  $(".permit-meeting").click(function(event) {
    if($(".choosed-button").hasClass('choosed')){
      $(".q-wrap").show();
      $("#box1").show();
    }else{
      gono();
    }
  });

  //不合适 qx
  $("#uns1").click(function(event) {
    if($(".choosed-button").hasClass('choosed')){
      $(".q-wrap").show();
      $("#box2").show();
    }else{
      gono();
    }
  });

  //待定 qx
  $("#uns2 , .dd").click(function(event) {    
    if($(".choosed-button").hasClass('choosed')){
      event.preventDefault();
      $("#card3").show();
      t=setTimeout(function(){
        $("#card3").hide();
      },3000);
      return false;
    }else{
      gono();
    }
  });

  $(".allow-ok").click(function(event) {
      event.preventDefault();
      $(this).parents(".q-box").hide()
      $(".q-wrap").hide();
      $("#card2").show();
      setTimeout(function(){
        $("#card2").hide();
      },3000);
      return false;
  });

  $(".fail-ok").click(function(event) {
        event.preventDefault();
        $(this).parents(".q-box").hide()
        $(".q-wrap").hide();
        $("#card1").show();
        setTimeout(function(){
          $("#card1").hide();
        },3000);
        return false;
    });

  // 关闭
  $(".q-close , .q-cancel").click(function(event) {
      $(".q-wrap").hide();
      $(".q-box").hide();
  });
  
  //不合适单独
  $(".q-fail-d").click(function(event) {
      $(".q-wrap").show();
      $("#box4").show();
  });

   //允许面试单独
  $(".q-allow-d").click(function(event) {
      $(".q-wrap").show();
      $("#box3").show();
  });

  /* 转发 */
  $(".forward-btn").click(function(event) {
   $(".q-forward").css({
     zIndex: '999',
     top:'50px',
     opacity: '100'
   });
  });
  $(".q-cancel1").click(function(event) {
    $(".q-forward").css({
     top:'70px',
     opacity: '0'
   });
    setTimeout(function(){
      $(".q-forward").css('zIndex', '-1');
    },300);
  });



  //没有选中
  function gono(){
    $(".choose-notice").show();
      setTimeout(function(){
        $(".choose-notice").hide();
      },3000);
  }

  $(".q-input").on('keyup', function(event) {
    event.preventDefault();
    if($(this).val().length){
      $(".q-ok").removeClass('disable');
    }else{
      $(".q-ok").addClass('disable');
    }
  });


   $(".q-ok").click(function(event) {
     if($(this).hasClass('disable')){
      return  false;
     }else{
        $(".q-forward").css({
       top:'70px',
       opacity: '0'
     });
      setTimeout(function(){
        $(".q-forward").css('zIndex', '-1');
         $("#card4").show();
        setTimeout(function(){
          $("#card4").hide();
        },3000);
        return false;

      },300);
     }
   });


   /* 单选 */

   var dxBtn=$(".q-radio > span");
   dxBtn.click(function(event) {
     $(this).parents(".q-div").find('.q-radio').find('span').removeClass('active');
     $(this).addClass('active');
   });

});


$(window).scroll(function(event) {
 if($(window).scrollTop()>345){
    $(".small-card").css('top', $(window).scrollTop()-50);
 }else{
   $(".small-card").css('top','206px');
 }
});


