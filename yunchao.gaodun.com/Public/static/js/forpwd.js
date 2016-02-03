   /*注册登录渐变转换*/
$(function(){
      $('.direct-login').click(function(){
        $(".login-page-sign").hide();
        $(".login-page-login").fadeIn("100");
      });  
      $('.direct-sign').click(function(){
          $(".login-page-login").hide();
          $(".login-page-sign").fadeIn("100");
        });

      $("#reset-send").click(function(){
      $.post("/Account/checkMailAgainPwd",{'email':$('.user-mail').text()},function(data){
        if(data.status){
            $(".login-wrap").fadeIn("1000");
            $(".sendsuccess-form").fadeIn("500");   
        }else{
          alert(data.msg);
          return;
        }
      },'json')
    });
    $("#close-send-success").click(function(){
        $(".login-wrap").fadeOut("300");
        $(".sendsuccess-form").fadeOut("300");
    });

    $('#email').on('keydown',function(e){
        if(e.keyCode == 13){
            getpwd($('.returnpassword-form').children('li'));
        }
    })

});

function getpwd(obj){
$('.user-mail').text($('#email').val());
$.post("/Account/forPwdMail",{'mail':$('#email').val()},function(data){
    if(!data.status){
      $('.error-info').text(data.msg);
      $('#for-pwd-succ').removeClass('login-success');
      $('#email').parent('.input-group-style').addClass('error-border');
      return false;
    }
    if(data.status){
      $('#email').parent('.input-group-style').removeClass('error-border');
      $('#for-pwd-succ').addClass('login-success');
      $('.user-mail').text(data.mail);
      if(data.type){
        $('.user-href').css('display','block');
        $('#get-li2').css('display','block');
        $('.user-href').attr('href',data.type);
        $('#get-li2').attr('data',data.type);
      }else{
        $('.user-href').css('display','none');
        $('#get-li2').css('display','none');
      }
      $('.mail-send').attr('mail',data.status);
      $('.tab-pane').removeClass('active');
      $('.tabs>li').removeClass('active');
      $('.tabs>li').eq(1).addClass('completed');
      $('.tab-pane').eq(1).addClass('active');
      $('.progress-indicator').css('width','66.8%');
      $('.step-three').addClass('active');
      $(obj).attr('onclick','return false');
    }
},'json');
}

function inmail(obj){
    var url = $(obj).attr('data');
    if(url){
      window.location.href = url
    }else{
      return false;
    }   
}
$(function(){
    $('.get-li').focusin(function(){
    $(this).css('background-color','#3ebcf8');
  })    
})

function forpwd(obj){
  $.post("/Account/logMail",{'mail':$(obj).val()},function(data){
      if(data.status){
          $('#for-pwd-succ').addClass('login-success');
          $('.error-info').text('');
          $('#email').parent('.input-group-style').removeClass('error-border');
      }
      if(!data.status){
          $('#for-pwd-succ').removeClass('login-success');
          $('.error-info').text(data.msg);
          $('#email').parent('.input-group-style').addClass('error-border');
      }
  },'json')
}