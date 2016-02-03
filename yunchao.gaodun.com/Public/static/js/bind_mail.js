$(function(){
    $(".bind-btn-fn").find("span").on("click",function(){
        $(this).addClass("active").siblings().removeClass("active");
        var ro = $(this).attr('data-role');
        $('#bind-status').val(ro);
        $('.bind-input-fn').val('');
        $('.bind-mail-succ-fn').html('&nbsp;');
        $('.bind-mail-succ-fn').removeClass('error-info-icon');
        $('.bind-fn-icon').hide();
    });

    //第三方会员
    $('#bindThirdForm').submit(function(){
      $.ajax({
         type: "post",
         async:false,
         url: "/Account/studentBind",
         dataType:"json",
         data: {'login_email':$('#bind-mail-stu').val(),'login_pass':$('#bind-pass-stu').val(),'status':$('#bind-status').val()},
         success: function(data){
            if(data.code==2){
              if(data.email){
                  $('#bind-mail-succ').removeClass('login-success');
                  $('#bind-mail-succ').hide();
                  $('#bind-pass-stu-mail').text(data.email);
                  $('#bind-pass-stu-mail').addClass('error-info-icon');
              }
              if(data.pass){
                  $('#bind-pwd-succ').removeClass('login-success');
                  $('#bind-pwd-succ').hide();
                  $('#bind-pass-stu-pass').text(data.pass);
                  $('#bind-pass-stu-pass').addClass('error-info-icon');
              }
            }
            if(data.code==3 || data.code==103){
                  $('#bind-mail-succ').removeClass('login-success');
                  $('#bind-mail-succ').hide();
                  $('#bind-pass-stu-mail').text(data.msg);
                  $('#bind-pass-stu-mail').addClass('error-info-icon');
            }
            if(data.code==100 || data.code==101){
                  $('#bind-pwd-succ').removeClass('login-success');
                  $('#bind-pwd-succ').hide();
                  $('#bind-pass-stu-pass').text(data.msg);
                  $('#bind-pass-stu-pass').addClass('error-info-icon');
            }
            if(data.code==4 || data.code==1 || data.code==201){
              return alert(data.msg);
            }
            if(data.code==200){
              window.location.href="/";
            }
            if(data.code==5){
              window.location.href="/Account/loginBindMail";
            }
         }
      });
      return false;
    })

    $('#bindStuForm').submit(function(){
      $.ajax({
         type: "post",
         async:false,
         url: "/Account/studentBind",
         dataType:"json",
         data: {'login_email':$('#bind-mail-stu').val(),'login_pass':$('#bind-pass-stu').val()},
         success: function(data){
            if(data.code==2){
              $('.error-info').text('');
              $('#bind-pass-stu-mail').text(data.email);
              $('#bind-pass-stu-pass').text(data.pass);
              $('.error-info').css('display','block');
            }
            if(data.code==3){
              $('.error-info').text('');
              $('#bind-pass-stu-mail').text(data.msg);
              $('.error-info').css('display','block');
            }
            if(data.code==4 || data.code==1){
              return alert(data.msg);
            }
            if(data.code==5){
              window.location.href="/Account/loginBindMail/id/"+data.data;
            }
         },
         error:function(){}
      });
      return false;
    })

    // var msg='如果离开此页，不绑定邮箱将无法使用第三方账号登陆。';
    // window.onbeforeunload = function() {
    //     return msg;
    // };
})


function bindmail(obj){
  if(!$(obj).val()){return false;}
  $.post("/Account/regmail",{'login_email':$(obj).val()},function(data){
      if(data.status){
          $('#bind-mail-succ').addClass('login-success');
          $('#bind-mail-succ').show();
          $('#bind-pass-stu-mail').html("&nbsp;");
          $('#bind-pass-stu-mail').removeClass('error-info-icon');
      }
      if(!data.status){
          var ro = $('#bind-status').val();
          if(ro == 1){
            $('#bind-mail-succ').removeClass('login-success');
            $('#bind-mail-succ').hide();
            $('#bind-pass-stu-mail').text(data.msg);
            $('#bind-pass-stu-mail').addClass('error-info-icon');            
          }
          if(ro == 2){
            $('#bind-mail-succ').addClass('login-success');
            $('#bind-mail-succ').show();
            $('#bind-pass-stu-mail').html("&nbsp;");
            $('#bind-pass-stu-mail').removeClass('error-info-icon');
          }
      }
  },'json')
}
function bindpwd(obj){
  if(!$(obj).val()){return false;}
  var url = "/Account/ajaxRegPass";
  var data = {'email':$(obj).val()};
  $.post(url,data,function(msg){
      if(!msg.pass){
          $('#bind-pwd-succ').removeClass('login-success');
          $('#bind-pwd-succ').hide();
          $('#bind-pass-stu-pass').text(msg.pass_msg);
          $('#bind-pass-stu-pass').addClass('error-info-icon');
      }else{
          $('#bind-pwd-succ').addClass('login-success');
          $('#bind-pwd-succ').show();
          $('#bind-pass-stu-pass').html("&nbsp;");
          $('#bind-pass-stu-pass').removeClass('error-info-icon');
      }
  },'json');
}
