/**
 * Created by allen on 2015/5/25.
 */
jQuery(document).ready(function(){
    var url = window.location.href;
    $("#send_type,#post_id,#degree_id,#details-1,#details-2,#recommend-post-sel,#recommend-post-sel2,#address-id,#student-year-id").select2({
        allowClear: false,
        minimumResultsForSearch: -1, // Hide the search bar
        formatResult: function(state)
        {
            return '<div style="display:inline-block;position:relative;width:20px;height:15px;margin-right: 0px;top:2px;"></div>'
                + state.text;
        }
    }).on('select2-open', function()
    {
        $(this).data('select2').results.perfectScrollbar();
    });

    //允许面试btn
    $('.allow').click(function(event){
        if(event.stopPropagation){
            event.stopPropagation();
        }else{
            event.cancelBubble = true;//ie8以下
        };
        $('.tab-box').stop().removeClass('is-visible');
        $(this).parents('.treat-resume-list-fn').find('.fn-allow').addClass('is-visible');
    })
    //待处理btn
    $('.waiting').click(function(event){
        if(event.stopPropagation){
            event.stopPropagation();
        }else{
            event.cancelBubble = true;//ie8以下
        };
        $('.tab-box').stop().removeClass('is-visible');
        $(this).parents('.treat-resume-list-fn').find('.fn-wait').addClass('is-visible');
    })
    //不适合btn
    $('.improper').click(function(event){
        if(event.stopPropagation){
            event.stopPropagation();
        }else{
            event.cancelBubble = true;//ie8以下
        };
        $('.tab-box').stop().removeClass('is-visible');
        $(this).parents('.treat-resume-list-fn').find('.fn-improper').addClass('is-visible');
    })
    //投递阅读
    $('.resume-view').click(function(event){
        if(event.stopPropagation){
            event.stopPropagation();
        }else{
            event.cancelBubble = true;//ie8以下
        };
        var check_id = $(this).attr('data-id');
        $.post('/ResumePost/updateView',{'pkid':check_id,'status':2},function(){},'json');
        $('#check-' + check_id).addClass('readed');
    })

    //邀请阅读
    $('.read-btn').bind('click',function(){
            var u = $(this).attr('data-id');
            var id = $(this).parents('.accept-list').attr('data-id');
            $(this).parents('.treat-resume-list').addClass('readed');
            $.post('/Invitation/resumeViewAccept',{'id':id},function(rst){},'json')  
            // window.open('/Student/resume/id/'+u,'_blank');         
    })
    //接受待处理职位筛选
    $('#recommend-post-sel').bind('change',function(){
        if($(this).val()){
           window.location.href = '/Invitation/accept/id/'+$(this).val();  
        }else{
            window.location.href = '/Invitation/accept';
        }
    })
    // --------------------

    $('.offline').click(function(event){
        if(event.stopPropagation){
            event.stopPropagation();
        }else{
            event.cancelBubble = true;//ie8以下
        };
        var offline_id = $(this).attr('data-id');
        $('.copy-box').stop().slideUp(400);
        $('.offline-box').stop().slideUp(400);
        $('.box' + offline_id).stop().slideDown(400);
        $('.yes').click(function(){
            var yes_id = $(this).attr('data-id');
            $.get('/ResumePost/postEdit/pkid/' + yes_id + '/status/2');
            $('.box' + yes_id).hide();
            $('#offline_' + yes_id).remove();
            if($('.effective-resume-list').length == 0){
                leave(0);
            }
        })
    })

    $('.online span').click(function(event){
        if(event.stopPropagation){
            event.stopPropagation();
        }else{
            event.cancelBubble = true;//ie8以下
        };
        $('.copy-box').stop().slideUp(400);
        $(this).next('.copy-box').stop().slideDown(400);
    })

    $('.close').click(function(){
        $(this).parent('div').parent('div').hide();
    })

    $('.copy-button').click(function(event){
        if(event.stopPropagation){
            event.stopPropagation();
        }else{
            event.cancelBubble = true;//ie8以下
        };
        $('.copy-box').stop().slideUp(400);
        $('.offline-box').stop().slideUp(400);
        $(this).next('.copy-box').stop().slideDown(400);
    })

    $('.copy-button-fn').hover(function(){
        $(this).next('.copy-box').stop().slideDown(400);
    },function(){
        $(this).next('.copy-box').stop().slideUp(400);
    })

    $(".refrash").click(function(event){
        if(event.stopPropagation){
            event.stopPropagation();
        }else{
            event.cancelBubble = true;//ie8以下
        }; 
        $(this).next().addClass("is-visible");
    });

    $(".refrash-close").click(function(){
        $(this).parent().removeClass("is-visible");
    });

    $(".no-pointer").hover(function(){
        $(this).next().css("display","block");
    },function(){
        $(this).next().css("display","none");
    });

    $('.disable').hover(function(){
        $(this).next('img').css("display","block");
    },function(){
        $(this).next('img').css("display","none");
    });

    $(document).click(function(){
        $('.copy-box').slideUp(400);
        $('.offline-box').slideUp(400);
        // $('.waiting-box').slideUp(400);
        $('.refrash-diag').removeClass('is-visible');
    })

    // ---------------------

    //check选项框
    $('.choosed-button-fn').click(function(event){
        var chk = $(this).attr('data-check');
        if(chk == 'false'){
            $(this).addClass('choosed');
            $(this).attr('data-check','true');
        }
        if(chk == 'true'){
            $(this).removeClass('choosed');
            $(this).attr('data-check','false');
        }
    })

    //全选允许面试、不适合、待定
    $('.permit-meeting-btn').click(function(event){
        var obj = $(this);
        if(event.stopPropagation){
            event.stopPropagation();
        }else{
            event.cancelBubble = true;//ie8以下
        }; 
        var check_len = $('.choosed-button-fn[data-check=true]').length;
        var ind = $(this).index();
        if(check_len<1){
            $('.choose-notice-fn').removeClass('dn');
            setTimeout(function(){
                $('.choose-notice-fn').addClass('dn');
            },3000)
        }else{
            if(obj.hasClass('waiting-decide') || obj.hasClass('dd')){
                allowCheck(obj,5);
                return false;
            }
            openPop(obj);
            $('.tab-box').stop().removeClass('is-visible');
            $('.permit-box-fn').eq(ind-1).addClass('is-visible');
        }
    })

    //全选check
    $('.all-check-btn').click(function(event){
        if(event.stopPropagation){
            event.stopPropagation();
        }else{
            event.cancelBubble = true;//ie8以下
        }; 
        var chk = $(this).attr('data-check');
        if(chk == 'false'){
            $(this).addClass('choosed');
            $(this).attr('data-check','true');
            $('.choosed-button-fn').addClass('choosed');
            $('.choosed-button-fn').attr('data-check','true');
        }
        if(chk == 'true'){
            $(this).removeClass('choosed');
            $(this).attr('data-check','false');
            $('.choosed-button-fn').removeClass('choosed');
            $('.choosed-button-fn').attr('data-check','false');           
        }
    });
    radioChange();
})

//不适合submit
function improper_post(obj){
    successPop(obj);
    if($('.q-radio .active').attr('data-value') < 3){
        var remark = $('.q-radio .active').next('p').text();
    }else{
        remark = $(obj).parent('.q-div').children('.q-tarea').val();
    }
    $.post('/ResumePost/updateView',{'id':$(obj).attr('data-id'),'status':4,remark:remark},function(){},'json');
    var number = parseInt($('#v-index a i').text()) - 1;
    $('#v-index a i').text(number);
    if(number == 0){
        leave(number);
        hideSpan();
    }
    if($('.treat-resume-list-fn').length==0){
        window.location.reload();
    }
}

//允许submit
function allow_post(obj){
    successPop(obj);
    var remark = $(obj).parent('.q-div').children('.q-tarea').val();
    $.post('/ResumePost/updateView',{'id':$(obj).attr('data-id'),'status':3,remark:remark},function(){},'json');
    // $('#interview-box').show();
    var number = parseInt($('#v-index a i').text()) - 1;
    $('#v-index a i').text(number);
    if(number == 0){
        leave(number);
        hideSpan();
    }
    if($('.treat-resume-list-fn').length==0){
        window.location.reload();
    }
    // setTimeout(function(){$('#interview-box').hide()},5000);
}

//邀请允许submit
function allow_post_invite(obj){
    $.post('/ResumePost/updateView',{'id':$(obj).attr('data-id'),'status':3},function(){
        window.location.reload();
    },'json');
}
//邀请不适合submit
function improper_post_invite(obj){
    $.post('/ResumePost/updateView',{'id':$(obj).attr('data-id'),'status':4},function(){
        window.location.reload();
    },'json');
}

//待定submit
function wait_post(obj){
    $.post('/ResumePost/updateView',{'id':$(obj).attr('data-id'),'status':5},function(){
        window.location.reload();
    },'json');
}

//全选submit 
function allowCheck(obj,status){
    var data = '';
    $('.choosed-button-fn[data-check=true]').each(function(){
        data = data+$(this).attr('data-id')+',';
    })
    $.post('/ResumePost/resumeHandle',{'data':data,'status':status},function(){
        window.location.reload();
    },'json');
}

//取消submit
function refuse_post(obj){
    $(obj).parents('.tab-box').removeClass('is-visible');
}

// --------------




function online_post(obj){
        var online_id = $(obj).attr('data-id');
        $.get('/ResumePost/postEdit/pkid/' + online_id + '/status/1');
        $(obj).parents('.offline-resume-list').remove();
        if($('.offline-resume-list').length == 0){
            leave(0);
        }
}

function offline_post(obj){
    $(obj).parents('.copy-box').slideUp(400);
}

function delCopyPost(obj,event){
    $('.copy-box').addClass('dn');
    $('.copy-box').stop().slideUp(400);
}

function load(){
    window.location.reload();
}

function leave(num){
    if(num == 0){
        $('.treat-resume-header').hide();
        $('#post_id').parent('div').hide();
        $('.no-one').show();
    }
}

function hideSpan(){
    $('#v-index a i').hide();
}

// 2015-08-06 10:01:33  星期四
function reflush(obj,id){
    $('.refrash-diag').removeClass('is-visible');
    $.post("/ResumePost/reflushPostTime",{'data':parseInt(id)},function(data){
        if(data.status){
            $(obj).parents('.list-right-effective').prev().find('.date>p').text(data.data);
            $(obj).next().addClass("is-visible");
            $(obj).addClass('no-pointer');
            $(obj).removeAttr('onclick');
        }
        if(!data.status){
            $(obj).next().find('.refrash-span').text(data.msg);
            $(obj).next().addClass("is-visible");
        }
    },'json')
}

/*2015-12-02 各类弹框方法*/
//关闭弹框
function closePop(obj){
    $('.'+$(obj).attr('data-action')).hide();
    $('.q-wrap').hide();
}
//打开弹框
function openPop(obj){
    var id = $(obj).attr('data-id');
    var type = $(obj).attr('data-type');    //判断弹窗类型 fail 不合适弹窗 allow 同意弹窗
    var ck = $(obj).attr('data-click');
    var action = $(obj).attr('data-action');
    if(id == '' || id == undefined){
        $('#'+type).removeAttr('data-id');
    }else{
        $('#'+type).attr('data-id',id);
    }
    $('#'+type).attr('onclick',ck);
    $('#'+type).attr('data-action',action);
    $('.'+action).show();
    $('.q-wrap').show();
    checkWord('.q-tarea');
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

//转发弹层
function relayPop(obj){
    $('.q-forward').removeAttr('onclick').css({top:'70px', opacity: '0'});
    //$(obj).parent('.q-bottom').children('.q-forward').attr('onclick','forwardResume(this)').css({zIndex: '999', top:'50px', opacity: '100',display : 'block'});
    $(obj).parent('.q-bottom').children('.q-forward').css({zIndex: '999', top:'50px', opacity: '100',display : 'block'});
}

//转发邮件
function forwardResume(obj){
    var id = $(obj).attr('data-id');
    var num = $(obj).attr('data-value');
    var type = $(obj).attr('data-type');
    var email = $(obj).parent('div').parent('.q-forward').find('input').val();
    var reg = /^([a-zA-Z0-9._-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
    if(!reg.test(email) || ignoreSpaces(email) == ''){
        $(obj).parent('div').parent('.q-forward').find('span').show();
        return false;
    }
    $.post('/ResumePost/forwardResume',{id:id,email:email});
    num = num*1+1
    $('.forward-'+id).text('('+num+')').show();
    $(obj).attr('data-value',num);
    successPop(obj);
}

//判断文本框内容
function checkInput(obj){
    var v = $(obj).val();
    if(v != ''){
        $(obj).next('span').hide();
        $(obj).next('span').next('div').children('.q-ok').attr('onclick','forwardResume(this)').removeClass('disable').text('转发');
    }else{
        $(obj).next('span').next('div').children('.q-ok').removeAttr('onclick').addClass('disable').text('转发');
    }
}

function checkChildrenInput(obj){
    var v = $(obj).children('.q-input').val();
    if(v != ''){
        $(obj).children('.q-input').next('span').hide();
        $(obj).children('.q-input').next('span').next('div').children('.q-ok').attr('onclick','forwardResume(this)').removeClass('disable').text('转发');
    }else{
        $(obj).children('.q-input').next('span').next('div').children('.q-ok').removeAttr('onclick').addClass('disable').text('转发');
    }
}

function successPop(obj){
    var id = $(obj).attr('data-id');
    var action = $(obj).attr('data-action');
    var type = $(obj).attr('data-type');
    if(type!='forward'){
        $('#check-'+id).remove();
        $('.q-wrap').hide();
    }
    $('.'+action).hide();
    $('#'+type+'-card').show();
    setTimeout(function(){
        $('#'+type+'-card').hide();
    },3000)
}

//忽略空格
function ignoreSpaces(string) {
    var temp = "";
    string = '' + string;
    splitstring = string.split(" ");
    for(i = 0; i < splitstring.length; i++)
        temp += splitstring[i];
    return temp;
}

function checkBrowser(obj){
    var url = $(obj).attr('data-action');
    $.post('/Resume/checkBrowser/',{url:url},function(data){
        if(data.status == 'success'){
            $('.col-md-9').append(data.html);
            $('#download-notice').show();
            $('#login-wrap').show();
        }else{
            window.open(url);
        }
    },'json')
}


function Q(s){return document.getElementById(s);}
function checkWord(c){
    var len= $(c).attr('data-value');      //最大长度
    var str = $(c).val();
        if(! $.trim(str)){
            return false;
        }
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

//var maxstrlen=100;
//function Q(s){return document.getElementById(s);}
//function checkWord(c){
//    len=maxstrlen;
//    var str = c.value;
//    myLen=getStrleng(str);
//    var wck=Q("wordCheck");
//    if(myLen>len*2){
//        c.value=str.substring(0,i+1);
//    }
//    else{
//        wck.innerHTML = Math.floor((len*2-myLen)/2);
//    }
//}
//function getStrleng(str){
//    myLen =0;
//    i=0;
//    for(;(i<str.length)&&(myLen<=maxstrlen*2);i++){
//        if(str.charCodeAt(i)>0&&str.charCodeAt(i)<128)
//            myLen++;
//        else
//            myLen+=2;
//    }
//    return myLen;
//}