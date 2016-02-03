/*修改密码成功*/
function modifysuccess(){
    $.post("/Account/ajaxSetPassword",{'pwd':$('#pwd').val(),'setpwd':$('#setpwd').val(),'setpwd2':$('#setpwd2').val()},function(data){
        if(data.pwd){
            $('#pwd').next('.error-info').addClass('error-info-span').text(data.pwd.msg);
            $('#pwd').addClass('input-border-error');
        }else{
            $('#pwd').next('.error-info').removeClass('error-info-span').text('');
            $('#pwd').removeClass('input-border-error');
        }
        if(data.pwd2 || data.pwd3){
            $('#setpwd').next('.error-info').addClass('error-info-span').text(data.pwd2.msg);
            $('#setpwd').addClass('input-border-error');
        }else{
            $('#setpwd').next('.error-info').removeClass('error-info-span').text('');
            $('#setpwd').removeClass('input-border-error');
        }
        if(data.pwd4){
            $('#setpwd2').next('.error-info').addClass('error-info-span').text(data.pwd4.msg);
            $('#setpwd2').addClass('input-border-error');
        }else{
            $('#setpwd2').next('.error-info').removeClass('error-info-span').text('');
            $('#setpwd2').removeClass('input-border-error');
        }
        if(data.set.status){
            $('.error-info').removeClass('error-info-span').text('');
            if(data.set.code==6){
                $("#form-modify-password").hide();
                $(".modify-success").show();
            }else if(data.set.code==7){
                return alert(data.set.msg)
            }
        }
    },'json')
    return false;
  }

function checkpwd(obj){
    if($(obj).val()){
        $.post("/Account/checkPassword",{'pwd':$(obj).val()},function(data){
            if(!data.status){
                $(obj).addClass('input-border-error');
                $(obj).next('.error-info').addClass('error-info-span').text(data.msg);
            }else{
                $(obj).removeClass('input-border-error');
                $(obj).next('.error-info').removeClass('error-info-span').text('');
            }
        },'json')
    }else{
        $(obj).removeClass('input-border-error');
    }
}

function checkrepwd(obj){
    if($(obj).val()){
        $.post("/Account/checkSetPassword",{'pwd':$(obj).val()},function(data){
            if(!data.status){
                $(obj).addClass('input-border-error');
                $(obj).next('.error-info').addClass('error-info-span').text(data.msg);
            }else{
                $(obj).removeClass('input-border-error');
                $(obj).next('.error-info').removeClass('error-info-span').text('');
            }
        },'json')
    }else{
        $(obj).removeClass('input-border-error');
    }
}

function checkpwdsame(obj){
    var setpwd  = $('#setpwd').val();
    var setpwd2 = $(obj).val();
    if(setpwd !== '' && setpwd2 !== ''){
        if(setpwd != setpwd2){
            $(obj).next('.error-info').text('您两次输入的密码不一致');
        }else{
            $(obj).next('.error-info').text('');
        }
    }
}

function activemail(id){
    $('.identify-button').attr('onclick',"return false");
    $('#firstmail').css('display','none');
    $('#secondmail').css('display','block');
    $.post("/Account/activeMail","",function(data){
        if(data.status){
            if(id==2){
                $('.sendsuccess-form').show();
                $('#login-email').attr('href',data.data);
                $('#login-wrap').show();
            }
        }else{
            $('.identify-button').attr('onclick',"activemail()");
            return alert(data.msg)
        }
    },'json')
}

//获取手机验证码
function checkCode(obj){
    $.post('/Delivery/checkPhone',{'phone':$('#use-mobile').val()},function(rst){
        if(!rst.status){
            $('.right-phone-fn').text(rst.msg);
        }
        if(rst.status){
                $('.right-code-fn').text(rst.msg);
                $('.right-phone-fn').html('&nbsp;');
                var t=59;
                $('#check-mobile-btn').text('60s');
                $('#check-mobile-btn').addClass('countdown');
                $('#check-mobile-btn').removeAttr('onclick');
                var re=setInterval(function(){
                    $('#check-mobile-btn').text(t+'s');
                    if(t==0){
                        clearInterval(re);
                        $('#check-mobile-btn').text('重新发送验证码');
                        $('#check-mobile-btn').removeClass('countdown');
                        $('#check-mobile-btn').attr('onclick','checkCode(this)');
                        return;
                    }
                    t--;
                },1400)
        }
    },'json');
}

$(function(){
    $('#passwd').click(function(){
        if($('#v-home').hide()){
            $('#modify_passwd_success').hide();
            $('#form-modify-password')[0].reset();
            $('#v-home').show();
            $('#form-modify-password').show();
        }
    })
    $('#verify').click(function(){
        $('#v-home').hide();
    })
    $('#mail').click(function(){
        $('#v-home').hide();
    });
    $('#remind').click(function(){
        $('#v-home').hide();
    });
    $('#phone-btn').click(function(){
        $('#v-home').hide();
    });

    $('#submit-mobile-btn').click(function(){
        var mobile = $('#use-mobile').val();
        var code = $('#use-number').val();
        $.post('/Delivery/verifyPhone',{'mobile':mobile,'code':code},function(rst){
            if(!rst.status){
                if(rst.code>102){
                    $('.right-code-fn').text(rst.msg);   
                }
                if(rst.code<103){
                    $('.right-phone-fn').text(rst.msg);
                }
            }
            if(rst.status){
                $('.modify-success-phone').css('display','block');
                $('.phone-tab-fn').css('display','none');
            }
        },'json')
    })
})