$(function(){
      $('.direct-login').click(function(){
          $(".login-page-sign").hide();
          $(".login-page-login").fadeIn("100");
          $('.input-group-style').removeClass('error-border');
          $('.error-info').text('');
      });
        
      $('.direct-sign').click(function(){
          $(".login-page-login").hide();
          $(".login-page-sign").fadeIn("100");
          $('.input-group-style').removeClass('error-border');
          $('.error-info').text('');
      });

      $(".practise-agreement").on("click",function(){
          $(".md-overlay").fadeIn("500");
          $(".User-protocol").fadeIn("500");
      });
      $(".protocol-close").on("click",function(){
          $(".md-overlay").fadeOut("500");
          $(".User-protocol").fadeOut("500");
      });
});

  $(function(){
    $('#sturegForm').submit(function(){

      $('#stu-reg-btn').prop('disabled','disabled');

      $.ajax({
         type: "post",
         async:false,
         url: "/Account/studentRegist",
         dataType:"json",
         data: {'login_email':$('#stureg-mail').val(),
                'login_pass':$('#stureg-pass').val(),
                'login_repass':$('#stureg-repass').val(),
                'check':$('#stureg-check').val()},
         success: function(data){
            if(data.code==2){
              $('.error-info').text('');
              $('#error-info-mail-stu').text(data.email);
              $('.error-info-pass-stu').text(data.pass);
              $('.error-info').css('display','block');
              if(data.email){
                $('#stureg-mail').parent('.input-group-style').addClass('error-border');
              }
              if(data.pass){
                $('#stureg-pass').parent('.input-group-style').addClass('error-border');
                $('#stureg-repass').parent('.input-group-style').addClass('error-border');
              }
              if(data.agree){
                $('#agree-stu-reg').css('display','block');
                $('#agree-stu-reg').text(data.agree);
              }
              $('#stu-reg-btn').removeAttr('disabled');
            }
            if(data.code==1){
              $('#agree-stu-reg').css('display','block');
              $('#agree-stu-reg').text(data.msg);
              $('#stu-reg-btn').removeAttr('disabled');
            }
            if(data.code==3 || data.code==4){
              $('.error-info').text('');
              $('#error-info-mail-stu').text(data.msg);
              $('.error-info').css('display','block');
              $('#stu-reg-btn').removeAttr('disabled');
            }
            if(data.code==5){
              window.location.href="/Account/checkMail/id/"+data.data;
            }
         },
         error:function(){}
      });
      return false;
    })

    $('#comregForm').submit(function(){
      $('#com-reg-btn').prop('disabled','disabled');
      $.ajax({
         type: "post",
         async:false,
         url: "/Account/enterpriseRegist",
         dataType:"json",
         data: {'login_email':$('#comreg-mail').val(),
                  'login_pass':$('#comreg-pass').val(),
                  'login_repass':$('#comreg-repass').val(),
                  'check':$('#comreg-check').val()},
         success: function(data){
            if(data.code==2){
              $('.error-info').text('');
              $('#error-info-mail-com').text(data.email);
              $('.error-info-pass-com').text(data.pass);
              $('.error-info').css('display','block');
              if(data.email){
                $('#comreg-mail').parent('.input-group-style').addClass('error-border');
              }
              if(data.pass){
                $('#comreg-pass').parent('.input-group-style').addClass('error-border');
                $('#comreg-repass').parent('.input-group-style').addClass('error-border');
              }
              if(data.agree){
                $('#agree-com-reg').css('display','block');
                $('#agree-com-reg').text(data.agree);
              }
              $('#com-reg-btn').removeAttr('disabled');
            }
            if(data.code==1){
              $('#agree-com-reg').css('display','block');
              $('#agree-com-reg').text(data.msg);
              $('#com-reg-btn').removeAttr('disabled');
            }
            if(data.code==3 || data.code==4){
              $('.error-info').text('');
              $('#error-info-mail-com').text(data.msg);
              $('.error-info').css('display','block');
              $('#com-reg-btn').removeAttr('disabled');
            }
            if(data.code==5){
              window.location.href="/Account/checkMail/id/"+data.data;
            }
         },
         error:function(){}
      });
      return false;
    })

    $('#loginForm').submit(function(){
      $.ajax({
         type: "post",
         async:false,
         url: "/Account/checkLogin",
         dataType:"json",
         data: {'login_email':$('#login-mail').val(),
                  'login_pass':$('#login-pass').val(),
                  'check':$('#login-check').val()},
         success: function(data){
            if(data.code==1){
              $('.error-info').text('');
              $('#error-info-mail-log').text(data.email);
              $('.error-info-pass-log').text(data.pass);
              $('.error-info').css('display','block');
              if(data.email){
                $('#login-mail').parent('.input-group-style').addClass('error-border');
              }
              if(data.pass){
                $('#login-pass').parent('.input-group-style').addClass('error-border');
              }
            }
            if(data.code==2){
              $('.error-info').text('');
              $('#error-info-mail-log').text(data.msg);
              $('.error-info').css('display','block');
            }
            if(data.code==3){
              $('.error-info').text('');
              $('.error-info-pass-log').text(data.msg);
              $('.error-info').css('display','block');
            }
            if(data.code==4){
	           if(''==$('#loginForm').attr('data')){
	           	   window.location.href='/Account/index';
	       	   }else{
	       	     window.location.href='/Account/backUrl/func/'+$('#loginForm').attr('data')+'/tag/'+$('#loginForm').attr('data-tag');
	       	     }   
            }
         },
         error:function(){}
      });
      return false;
    })
  })

//是否同意注册协议
function checkagree(obj){
    if(obj.checked){
        $(obj).val(1);
        obj.checked=true;
        $(obj).parents('.cbr-replaced').addClass('cbr-checked');
        $(obj).parents('.checkbox-agree').next('.agree-info').css('display','none');
        $(obj).parents('.checkbox-agree').next('.agree-info').html('');
    }else{
      $(obj).val(0);
      obj.checked=false;
      $(obj).parents('.cbr-replaced').removeClass('cbr-checked')
      $(obj).parents('.checkbox-agree').next('.agree-info').css('display','block');
      $(obj).parents('.checkbox-agree').next('.agree-info').html('请接受高顿实习用户协议');
    }
}

//注册邮箱验证
function regemail(id,obj){
  if(!$(obj).val()){
    $(obj).parent('.input-group-style').removeClass('error-border');
    return false;
  }
  $.post("/Account/regmail",{'id':id,'login_email':$(obj).val()},function(data){
      if(data.data==2){
          if(data.status==false){
            $('.error-info').text('');
            $('#stureg-mail-succ').removeClass('login-success');
            $('#error-info-mail-stu').text(data.msg);
            $('#stureg-mail').parent('.input-group-style').addClass('error-border');
          }
      }
      if(data.data==1){
          if(data.status==false){
            $('.error-info').text('');
            $('#comreg-mail-succ').removeClass('login-success');
            $('#error-info-mail-com').text(data.msg);
            $('#comreg-mail').parent('.input-group-style').addClass('error-border');
          }
      }
      if(data.status==true){
        $('.error-info').text('');
        if(id==2){
          $('#stureg-mail-succ').addClass('login-success');
          $('#stureg-mail').parent('.input-group-style').removeClass('error-border');
        }
        if(id==1){
          $('#comreg-mail-succ').addClass('login-success');
          $('#comreg-mail').parent('.input-group-style').removeClass('error-border');
        }
      }
  },'json')
}
function regemailIn(obj){
  var s=$(obj).parents('.input-group-style').next('span');
   $(obj).parents('.form-group').next('.error-info').text('');
  if($(s).css('display')=='block'){
    $(s).removeClass('login-success');
  }
}

function hideqq(){
      $('.third-login-main').css('display','none');
      $('.input-group-style').removeClass('error-border');
      $('.error-info').text('');
}
function showqq(){
      $('.third-login-main').css('display','block');
      $('.input-group-style').removeClass('error-border');
      $('.error-info').text('');
}
function qqlogin()
{
  // window.("http://shixi.gaodun.com/Account/qq","TencentLogin", "width=450,height=320,menubar=0,scrollbars=1,resizable=1,status=1,titlebar=0,toolbar=0,location=1");
}

function regpass(type,obj){
  if(!$(obj).val()){
    $(obj).parent('.input-group-style').removeClass('error-border');
    return false;
  }
  var type = parseInt(type);
  if(type==2){
    var url = "/Account/ajaxRegPassOver2";
    var data2 = {'email':$('#stureg-pass').val(),'email2':$('#stureg-repass').val()}
    $.post(url,data2,function(data){
        if(data.status){
            $('.error-info-pass-stu').eq(1).text('');
            $('#stureg-repass-succ').addClass('login-success');
            $('#stureg-repass').parent('.input-group-style').removeClass('error-border'); 
        }
        if(!data.status){
            $('.error-info-pass-stu').eq(1).text(data.msg);
            $('#stureg-repass-succ').removeClass('login-success');
            $('#stureg-repass').parent('.input-group-style').addClass('error-border');
        }
    },'json');
  }
  if(type==1){
    var url = "/Account/ajaxRegPassOver";
    var data2 = {'email':$('#stureg-pass').val()}
    $.post(url,data2,function(data){
      if(data.status){
          $('.error-info-pass-stu').eq(0).text(''); 
          $('#stureg-pass-succ').addClass('login-success');
          $('#stureg-pass').parent('.input-group-style').removeClass('error-border');
      }
      if(!data.status){
          $('.error-info-pass-stu').eq(0).text(data.msg);
          $('#stureg-pass-succ').removeClass('login-success');
          $('#stureg-pass').parent('.input-group-style').addClass('error-border');
      }
    },'json');    
  }

}

function regpass2(type,obj){
  if(!$(obj).val()){
    $(obj).parent('.input-group-style').removeClass('error-border');
    return false;
  }
  var type = parseInt(type);
  if(type==1){
    var url = "/Account/ajaxRegPassOver";
    var data2 = {'email':$('#comreg-pass').val()}
    $.post(url,data2,function(data){
        if(data.status){
            $('#comreg-pass-succ').addClass('login-success');
            $('.error-info-pass-com').eq(0).text('');
            $('#comreg-pass').parent('.input-group-style').removeClass('error-border');
        }
        if(!data.status){
            $('.error-info-pass-com').eq(0).text(data.msg);
            $('#comreg-pass-succ').removeClass('login-success');
            $('#comreg-pass').parent('.input-group-style').addClass('error-border');         
        }
    },'json'); 
  }
  if(type==2){
    var url = "/Account/ajaxRegPassOver2";
    var data2 = {'email':$('#comreg-pass').val(),'email2':$('#comreg-repass').val()}
    $.post(url,data2,function(data){
        if(data.status){
            $('.error-info-pass-com').eq(1).text('');
            $('#comreg-repass-succ').addClass('login-success');
            $('#comreg-repass').parent('.input-group-style').removeClass('error-border');          
        }
        if(!data.status){
            $('.error-info-pass-com').eq(1).text(data.msg);
            $('#comreg-repass-succ').removeClass('login-success');
            $('#comreg-repass').parent('.input-group-style').addClass('error-border');          
        }
    },'json');    
  }
}


function logmail2(obj){
  if(!$(obj).val()){
    $(obj).parent('.input-group-style').removeClass('error-border');
    return false;
  }
    $.post("/Account/logMail",{'mail':$(obj).val()},function(data){
        if(data.status){
            $('#login-mail-succ').addClass('login-success');
            $('#error-info-mail-log').text('');
            $('#login-mail').parent('.input-group-style').removeClass('error-border');
        }
        if(!data.status){
            $('#login-mail-succ').removeClass('login-success');
            $('#error-info-mail-log').text(data.msg);
            $('#login-mail').parent('.input-group-style').addClass('error-border');
        }
    },'json')
}

function logpwd2(obj){
  if(!$(obj).val()){
    $(obj).parent('.input-group-style').removeClass('error-border');
    return false;
  }
  var data = {'email':$('#login-pass').val()};
  $.post("/Account/ajaxRegPassOver",data,function(msg){
      if(msg.status){
        $('#login-pass-succ').addClass('login-success');
        $('.error-info-pass-log').text('');
        $('#login-pass').parent('.input-group-style').removeClass('error-border');
      }
      if(!msg.status){
        $('#login-pass-succ').removeClass('login-success');
        $('.error-info-pass-log').text(msg.msg);
        $('#login-pass').parent('.input-group-style').addClass('error-border');     
      }
  },'json')
}


function setpwd2(type,obj){
        var url = "/Account/ajaxRegPass";
        var data = {'email':$('#set-pass').val(),'email2':$('#set-repass').val()}
        $.post(url,data,function(msg){
            if(msg.pass){
              $('#set-pass-succ').addClass('login-success');
              $('.error-info').eq(0).text('');
            }
            if(msg.repass){
              $('#set-repass-succ').addClass('login-success');
              $('.error-info').eq(1).text('');
            }
            if(!msg.pass){
              $('.error-info').eq(0).text(msg.pass_msg);
              $('#set-pass-succ').removeClass('login-success');
            }
            if(!msg.repass){
              $('.error-info').eq(1).text(msg.repass_msg);
              $('#set-repass-succ').removeClass('login-success');
            }
            if(!msg.equal){
              $('.error-info').eq(1).text(msg.equal_msg);
              $('#set-repass-succ').removeClass('login-success');
            }
        },'json');
}

   $(function(){
      $("#reset-send").click(function(){
          $(".backpassword_content").hide();
          $(".reset-success").fadeIn("500");
      });

      $('#pwdform').submit(function(){
            $.ajax({
               type: "post",
               async:false,
               url: "/Account/setpwd",
               dataType:"json",
               data: {'login_pass':$('#set-pass').val(),'login_repass':$('#set-repass').val()},
               success: function(data){
                    if(!data.status){
                        $('.error-info').text(data.msg);
                        $('.error-info').css('display','block');
                        return false;
                    }
                    if(data.status){
                          $(".backpassword_content").hide();
                          $(".reset-success").fadeIn("500");
                          jumptime(3);
                    }
               },
               error:function(){}
            });
        return false;
      })
    });
    function jumptime(i){
      i = i-1;
      $('#jumptime').text(i);
      if(i==0){
        window.location.href = '/';
      }else{
        setInterval("jumptime("+i+")",1000);
      }
    }