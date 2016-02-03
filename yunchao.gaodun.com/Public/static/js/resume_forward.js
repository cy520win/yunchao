/**
 * Created by gaodun on 2015/12/9.
 */
$(function(){
    $(".disable").hover(function(event) {
        $(".dis-box").fadeIn(100);
    },function(){

        $(".dis-box").fadeOut(100);
    });
    $(".e-resume").hover(function(event) {
        $(".no-upload-resume").fadeIn(100);
    },function(){

        $(".no-upload-resume").fadeOut(100);
    });
    $("#down-area").hover(
        function(){
            $(this).children("ul").css("display","block");
        },function(){
            $(this).children("ul").css("display","none");
        }
    )
});

$(function(){
    $(window).scroll(function(){  //只要窗口滚动,就触发下面代码
        var scrollt = document.documentElement.scrollTop + document.body.scrollTop; //获取滚动后的高度

        if( scrollt >600){  //判断滚动后高度超过200px,就显示
            $("#return-up-area").fadeIn(100); //淡出
        }else{
            $("#return-up-area").stop().fadeOut(100); //如果返回或者没有超过,就淡入.必须加上stop()停止之前动画,否则会出闪动
        }
        if(scrollt>0){

            $(".header-faction").css("background-color","rgba(0,0,0,0.7)"); //淡出
        }else{
            $(".header-faction").css("background-color","transparent");
        }
    });
    $("#return-up-area").click(function(){ //当点击标签的时候,使用animate在200毫秒的时间内,滚到部
        $("html,body").animate({scrollTop:"0px"},200);
    });
    $('#checkVerify').click(function(){
        $.post('/ResumeForward/checkVerifyCode',{forward_id:$('#forward_id').val(),code:$('#verify_code').val()},function(data){
            if(data.status == 'success'){
                window.location.reload();
            }else{
                $('#verify_code').next('span').show();
            }
        },'json')
    })
    radioChange();
});

function tabChange(obj){
    var id = $(obj).attr('data-action');
    $('.c-e').children('li').removeClass('active').addClass('disable');
    $(obj).removeClass('disable').addClass('active');
    $('.center-div').hide();
    $('#'+id).show();
    $('.resume-down').hide();
    $('.'+id+'-resume').css('display','block');
}

function openPop(obj){
    var action = $(obj).attr('data-action');
    $('.q-wrap').show();
    $('.'+action).show();
    checkWord('.q-tarea');
}

function closePop(obj){
    var action = $(obj).attr('data-action');
    $('.q-wrap').hide();
    $('.'+action).hide();
}

//按钮切换
function radioChange(){
    var dxBtn=$(".q-radio > span");
    dxBtn.click(function(event) {
        $(this).parents(".q-div").find('.q-radio').find('span').removeClass('active');
        $(this).addClass('active');
        if($(this).attr('data-value') == 3){
            $(this).parent('.q-radio').parent(".q-div").find('.q-tarea').removeAttr('readonly');
        }else{
            $(this).parent('.q-radio').parent(".q-div").find('.q-tarea').attr('readonly',true);
        }
    });
}

//不适合submit
function improperForward(obj){
    if($('.q-radio .active').attr('data-value') < 3){
        var remark = $('.q-radio .active').next('p').text();
    }else{
        remark = $(obj).parent('.q-div').children('.q-tarea').val();
    }
    $.post('/ResumeForward/treatForward',{'resume_post_id':$(obj).attr('data-id'),'forward_id':$('#forward_id').val(),'deal':2,remark:remark},function(data){
        if(data.msg){
            repeatPop(obj);
            return false;
        }
    },'json');
    successPop(obj);
}

//允许submit
function allowForward(obj){
    var remark = $(obj).parent('.q-div').children('.q-tarea').val();
    $.post('/ResumeForward/treatForward',{'resume_post_id':$(obj).attr('data-id'),'forward_id':$('#forward_id').val(),'deal':1,remark:remark},function(data){
        if(data.msg){
            repeatPop(obj);
            return false;
        }
    },'json');
    successPop(obj);
}

//待定
function forwardWait(obj){
    $.post('/ResumeForward/treatForward',{'resume_post_id':$(obj).attr('data-id'),'forward_id':$('#forward_id').val(),'deal':3},function(){},'json');
    openPop(obj);
    $(obj).addClass('qyh-active').removeAttr('onclick');
    setTimeout(function(){
        $('.h350').hide();
        $('.q-wrap').hide();
    },2000);
}

function successPop(obj){
    $('.button-area').children('button').hide();
    $('#'+$(obj).attr('data-button')).show().addClass('qyh-active').removeAttr('onclick');
    $(obj).parent('.q-div').hide();
    $(obj).parent('.q-div').next('.qq-suss').show();
    setTimeout(function(){
        $(obj).parent('.q-div').parent('.q-box').hide();
        $('.q-wrap').hide();
    },2000);
}

function repeatPop(obj){
    $(obj).parent('.q-div').parent('.q-box').hide();
    $('.q-repeat').show();
    setTimeout(function(){window.location.reload()},2000);
}

function Q(s){return document.getElementById(s);}
function checkWord(c){
    var len= $(c).attr('data-value');      //最大长度
    var str = $(c).val();
    var myLen=getStrleng(str,len);
    var wck=Q($(c).attr('data-action'));   //指向位置
    if(myLen>len*2){
        $(c).val(str.substring(0,Math.floor((myLen)/2)));
    }
    else{
        wck.innerHTML = Math.floor((myLen)/2);
    }
}
function getStrleng(str,len){
    var myLen =0;
    for(var i=0;(i<str.length)&&(myLen<=len*2);i++){
        if(str.charCodeAt(i)>0&&str.charCodeAt(i)<128)
            myLen++;
        else
            myLen+=2;
    }
    return myLen;
}