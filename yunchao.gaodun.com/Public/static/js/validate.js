/**
 * Created by allen on 2015/6/1.
 */
jQuery(document).ready(function(){
    $('.validate-require').each(function(index,obj){
        var Tag = obj.tagName;
        //判断标签类型
        if(Tag == 'INPUT'){
            $(obj).blur(function(){
                if(ignoreSpaces($(obj).val()) == ''){
                    if($(obj).next('.error-area').length <= 0){
                        $(obj).after('<div class="error-area"><span class="col-md-5 error-info-span">' + $(obj).attr('data-placeholder') + '</span></div>');
                    }else{
                        $(obj).next('.error-area').children('span').addClass('error-info-span').text($(obj).attr('data-placeholder'));
                    }
                }else{
                    $(obj).next('.error-area').remove();
                }
            })
        }else if(Tag == 'SELECT'){
            $(obj).change(function(){
                if($(obj).find('option:selected').val() != ''){
                    $(obj).next('.error-area').remove();
                }else{
                    $(obj).after('<div class="error-area"><span class="col-md-5 error-info-span">' + $(obj).attr('data-placeholder') + '</span></div>');
                }
            })
        }else if(Tag == 'TEXTAREA'){
            $(obj).blur(function(){
                if(ignoreSpaces($(obj).val()) == ''){
                    if($(obj).next('.error-area').length <= 0){
                        $(obj).after('<div class="error-area"><span class="col-md-5 error-info-span">' + $(obj).attr('data-placeholder') + '</span></div>');
                    }else{
                        $(obj).next('.error-area').children('span').addClass('error-info-span').text($(obj).attr('data-placeholder'));
                    }
                }else{
                    $(obj).next('.error-area').remove();
                }
            })
        }else{

        }
    })

    $('.validate-onlyNumberSp').each(function(index,obj){
        $(obj).blur(function(){
            if($(obj).val() != ''){
                var num = /^[0-9\ ]+$/;
                if(!num.test($(obj).val())){
                    if($(obj).next('.error-area').length <= 0){
                        $(obj).after('<div class="error-area"><span class="col-md-5 error-info-span">请输入整数</span></div>');
                    }else{
                        $(obj).next('.error-area').children('span').addClass('error-info-span').text('请输入整数');
                    }
                }else{
                    $(obj).next('.error-area').remove();
                }
            }
        })
    })

    $('.validate-onlyNumberSp').bind('onpropertychange input',function(){
        if($(this).val() != ''){
            var num = /^[0-9\ ]+$/;
            if(!num.test($(this).val())){
                if($(this).next('.error-area').length <= 0){
                    $(this).after('<div class="error-area"><span class="col-md-5 error-info-span">请输入整数</span></div>');
                }else{
                    $(this).next('.error-area').children('span').addClass('error-info-span').text('请输入整数');
                }
            }else{
                $(this).next('.error-area').remove();
            }
        }else{
            $(this).next('.error-area').remove();
        }
    })
})


function ignoreSpaces(string) {
    var temp = "";
    string = '' + string;
    splitstring = string.split(" ");
    for(i = 0; i < splitstring.length; i++)
        temp += splitstring[i];
    return temp;
}

function check(){
    var bool = true;
    $('.validate-require').each(function(index,obj){
        var Tag = obj.tagName;
        //判断标签类型
        if(Tag == 'INPUT') {
            var value = $(obj).val();
        }else if(Tag == 'SELECT') {
            value = $(obj).find('option:selected').val();
        }else if(Tag == 'TEXTAREA'){
            value = $(obj).val();
        }

        if(ignoreSpaces(value) == ''){
            if($(obj).next('.error-area').length <= 0){
                $(obj).after('<div class="error-area"><span class="col-md-8 error-info-span">' + $(obj).attr('data-placeholder') + '</span></div>');
            }else{
                $(obj).next('.error-area').children('span').addClass('error-info-span').text($(obj).attr('data-placeholder'));
            }
            bool = false;
        }else{
            $(obj).next('.error-area').remove();
        }
    })

    $('.validate-onlyNumberSp').each(function(index,obj){
        var num = /^[0-9\ ]+$/;
        if($(obj).val() != ''){
            if(!num.test($(obj).val())){
                if($(obj).next('.error-area').length <= 0){
                    $(obj).after('<div class="error-area"><span class="col-md-5 error-info-span">请输入整数</span></div>');
                }else{
                    $(obj).next('.error-area').children('span').addClass('error-info-span').text('请输入整数');
                }
                bool = false;
            }else{
                $(obj).next('.error-area').remove();
            }
        }
    })

    return bool;
}
