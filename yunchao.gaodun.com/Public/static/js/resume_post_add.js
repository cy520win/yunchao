var maxstrlen=1000;
function Q(s){return document.getElementById(s);}
function checkWord(c){
    len=maxstrlen;
    var str = c.value;
    myLen=getStrleng(str);
    var wck=Q("wordCheck");
    if(myLen>len*2){
        c.value=str.substring(0,i+1);
    }
    else{
        wck.innerHTML = Math.floor((len*2-myLen)/2);
    }
}
function getStrleng(str){
    myLen =0;
    i=0;
    for(;(i<str.length)&&(myLen<=maxstrlen*2);i++){
        if(str.charCodeAt(i)>0&&str.charCodeAt(i)<128)
            myLen++;
        else
            myLen+=2;
    }
    return myLen;
}

$(function(){
    var aLiw=$("#graduate >li");
    aLiw.click(function(event) {
        var chk = $(this).attr('data-value');
        if($(this).attr('data-type')=='true'){
            $(this).addClass('active-w');
            $(this).attr('data-type','false');
            if($(this).next('input').length <= 0){
                $(this).after('<input type="hidden" class="graduate" name="graduate[]" value="' + chk + '">');
            }
        }else{
            $(this).removeClass('active-w');
            $(this).attr('data-type','true');
            $(this).next('input').remove();
        }
    });

    //set date
    var aP=$(".q-time2 >p");
    aP.click(function(event) {
        var id = parseInt($(this).attr('data-id'));
        $(this).parent('.q-time2').find('.attend-type-date').val(id);

        $(this).siblings('p').find('span').removeClass('active');
        $(this).find('span').addClass('active');
        if($(this).hasClass('time-two')){
            $(this).siblings('.time-right').hide();
            $(this).siblings('.time-right').find('input').prop('disabled','disabled');
        }else{
            $(this).siblings('.time-right').show();
            $(this).siblings('.time-right').find('input').removeAttr('disabled');
        }
    });
});

jQuery(document).ready(function(){
    $('input[name=title]').blur(function(){
        if($('input[name=title]').val().lengthB() < 40){
            $('input[name=title]').next('.error-area').remove();
        }
    })

    $('#v-add').addClass('resume-active');

    $('#inputTest20').click(function(){
        $('#inputTest20').next('.error-area').remove();
    })

    $('#pt_save').click(function(){
        var bool = true;
        if(check() == false){
            bool = false;
            location.href="#details-1"
        }

        if($('#inputTest20').val() == ''){
            if($('#inputTest20').next('.error-area').length <= 0){
                $('#inputTest20').after('<div class="error-area"><span class="col-md-7 error-info-span">'+
                $('#inputTest20').attr('data-placeholder') +'</span></div>');
                bool = false;
            }
        }


        if($('#graduate li').next('input[type=hidden]').length <= 0){
            if($('#graduate').next('.error-area').length <= 0){
                $('#graduate').after('<div class="error-area ml40"><span class="col-md-7 error-info-span">'+
                $('#graduate').attr('data-placeholder') +'</span></div>');
                bool = false;
            }
        }

        if($('input[name=title]').val().lengthB() > 40){
            if($('input[name=title]').next('.error-area').length <= 0){
                $('input[name=title]').after('<div class="error-area"><span class="col-md-7 error-info-span">不可超过20个汉字</span></div>');
                bool = false;
            }
        }

        if($('.major-ul>li').length<1){
            $('#div').next('.error-area').removeClass('dn');
            bool=false
        }else{
            $('#div').next('.error-area').addClass('dn');
        }
        if($('.graduate').length<1){
            bool=false
        }

        if($('#receive-input').val() == 2){
            var s = ignoreSpaces($('#accept-start').val());
            var e = ignoreSpaces($('#accept-end').val());
            if(!s && !e){
              bool = false;
              $('.date-tip-accept').addClass('date-tip-error');
            }
        }

        if($('#attend-input').val() == 2){
            var s = ignoreSpaces($('#post-start').val());
            var e = ignoreSpaces($('#post-end').val());
            if(!s && !e){
              bool = false;
              $('.date-tip-post').addClass('date-tip-error');
            }
        }

        var email_reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
        var email = ignoreSpaces($('#receive_email').val());  
        if(!email_reg.test(email) && email){
            $('.date-tip-email').html('输入正确的邮箱');
            bool = false;    
        } 

        if(bool == false){
            return false;
            location.href="#inputTest20"
        }


        $.post('/ResumePost/save',$('#postForm').serialize(),function(data){
            if(data.status == 'success'){
                if(data.type == 'add'){
                    $('.release-wrap').show();
                    $('.release-box').fadeIn(500);
                    $('.yes').click(function(){
                        var type = '{$type}';
                        if(type == 'add'){
                            $('#postForm')[0].reset();
                        }
                        $(".release-box").fadeOut(500);
                        window.location.reload()
                    })
                    $('.no').click(function(){
                        $(".release-box").fadeOut(500);
                        location.href = "/ResumePost/effective";
                    })
                }else{
                    window.location.href = "/ResumePost/effective";
                }
            }else if(data.status == 'fail'){
                $('.date-tip-'+data.data).html(data.msg);
                $('.date-tip-'+data.data).addClass('date-tip-error')
            }else{
                // alert(data.message);
            }
        },'json')
    })
});

String.prototype.lengthB = function( ){
var b = 0, l = this.length;
if( l ){
    for( var i = 0; i < l; i ++ ){
        if(this.charCodeAt( i ) > 255 ){
            b += 2;
        }else{
            b ++ ;
        }
    }
    return b;
}else{
    return 0;
}
}
// -----------------------2015-07-15 15:20:16  星期三
$(function(){
  var a=true;
  $("#div").click(function(event) {
      event.stopPropagation();
      if(a){
          $("#div").addClass("div1");
          $(".list").show();
          
          a=false;
      }
      else{
          $(".list").hide();
          $("#div").removeClass("div1");
          a=true;
      }
    
  });

  $(".list").on("click", "li", function (event) {
       event.stopPropagation();
  
        //如果选择值大于5个就不准选择了
        var liSize=$("#div").find("ul").find("li").length;
        if(liSize>4){
         $(".list").hide();
          $("#div").removeClass("div1");
          a=true;
         return false;
        };
        
        if((liSize+1)>0){
          $("#div >p").hide();
        }else{
          $("#div >p").show();
        }


        //禁止重复值
        var aVal=ignoreSpaces($("#div").find("li").text()).split("×");
        var val=ignoreSpaces($(this).html());
        var val_id=$(this).attr('data-id');
        if(val_id==0){
          $("#div").find("ul").html('<li>'+ val + ' <span class=\"span\">×</span><input type=\"hidden\" name=\"major_wish[]\" value='+ val_id +'></li>');
        }else{
          for(var i=0;i<aVal.length;i++){
            if(ignoreSpaces(aVal[i])==$(this).html()){
              return false;
            }
          }          
        }

        //向输入框填入值
        $('#div').next('.error-area').addClass('dn');        
        if(val_id==0){
          $("#div").find("ul").html('<li>'+ val + ' <span class=\"span\">×</span><input type=\"hidden\" name=\"major_wish[]\" value='+ val_id +'></li>');
        }else{
          if($("#div").find("ul>li>input[name='major_wish[]']").eq(0).val()==0){
            return false;
          }else{
            $("#div").find("ul").append('<li>'+ val + ' <span class=\"span\">×</span><input type=\"hidden\" name=\"major_wish[]\" value='+ val_id +'></li>');
          }
        }
      });


  //点击×删除
  $("#div").on("click", ".span", function (event) {
       var liSize=$("#div").find("ul").find("li").length;
       event.stopPropagation();
       $(this).parent("li").remove(); 

       if(liSize<=1){
          $("#div >p").show();
        }
  });

  $(document).click(function(event) {
      a=true;
      $(".list").hide();
      $("#div").removeClass("div1");
       
  });

  //接受时间，到岗时间
  $('#accept-start,#accept-end,#post-start,#post-end').click(function(){
      var id = $(this).attr('id');
      laydate({
                elem:'#'+id,
                format:'YYYY-MM-DD',
                choose:function(dates){
                  if(id == 'accept-start'){
                      if($('#accept-end').val() == ''){
                          $('.date-tip-accept').text('允许学生在"开始日期"后投递该职位');
                      }else{
                          $('.date-tip-accept').text('允许学生在设置的时段中投递该职位');
                      }
                      $('.date-tip-accept').removeClass('date-tip-error');
                  }
                  if(id == 'accept-end'){
                      if($('#accept-start').val() == ''){
                          $('.date-tip-accept').text('允许学生在"截止日期"前投递该职位');
                      }else{
                          $('.date-tip-accept').text('允许学生在设置的时段中投递该职位');
                      }
                      $('.date-tip-accept').removeClass('date-tip-error');
                  }
                  if(id == 'post-start'){
                      if($('#post-end').val() == ''){
                          $('.date-tip-post').text('希望学生"开始日期"后到岗');
                      }else{
                          $('.date-tip-post').text('期望学生在设置的时段中到岗');
                      }
                      $('.date-tip-post').removeClass('date-tip-error');
                  }
                  if(id == 'post-end'){
                      if($('#post-start').val() == ''){
                          $('.date-tip-post').text('希望学生"截止日期"前到岗');
                      }else{
                          $('.date-tip-post').text('期望学生在设置的时段中到岗');
                      }
                      $('.date-tip-post').removeClass('date-tip-error');
                  }

                }
            });
  })

});

function ignoreSpaces(string) {
    var temp = "";
    string = '' + string;
    splitstring = string.split(" ");
    for(i = 0; i < splitstring.length; i++)
        temp += splitstring[i];
    return temp;
}

//日历挂件
function getdate(id,e){
    var e = e || event
  if(e.stopPropagation){
    e.stopPropagation();
  }else{
    e.cancelBubble = true;//ie8以下
  }
    laydate({elem:'#'+id,format:'YYYY-MM-DD'});
}