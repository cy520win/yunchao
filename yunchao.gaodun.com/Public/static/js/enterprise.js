/**
 * Created by allen on 2015/5/19.
 */
jQuery(document).ready(function(){
    /*
    新增
     */
    $('#en_save').click(function(){
        var bool = check();
        if(bool == false){
            location.href="#upload";
            return false;
        }
        $.post('/Enterprise/save',$('#enterpriseForm').serialize(),function(data){
            if(data.status == 'success'){
                location.href = "/Enterprise/index";
            }else{
                alert(data.message);
            }
        },'json')

    })
    var url = window.location.href;
    $('#city dd span').click(function(){
        var city = $(this).text();
        if(city != '更多'){
            $('#param').show();
            $('#city_name').removeClass('dn');
            url = checkP();
            $.post('/Upload/getCityId',{'city':city},function(data){
                var cityId = data.cityId;
                if(url.indexOf('ct') > 0){
                    var term = $('input[name=ct]').val();
                    $.post('/Upload/getCityId',{'city':term},function(data){
                        term = data.cityId;
                        window.location.href = url.replace('/ct/' + term,'/ct/' + cityId);
                    },'json')
                }else{
                    window.location.href = url + '/ct/' + cityId;
                }
                $('input[name=ct]').val(city);
            },'json')
        }

    })

    $('#industry dd span').click(function(){
        var industry = $(this).attr('data-value');
        var industry_name = $(this).attr('class');
        if(industry_name != 'more' && industry_name != 'retract'){
            $('#param').show();
            $('#industry_name').removeClass('dn');
            url = checkP();
            if(url.indexOf('in') > 0){
                window.location.href = url.replace($('input[name=in]').val(),industry);
            }else{
                window.location.href = url + '/in/' + industry;
            }
            $('input[name=in]').val(industry);
        }
    })

    $('#scale dd span').click(function(){
        var scale = $(this).attr('data-value');
        var scale_name = $(this).attr('class');
        if(scale_name != 'more' && scale_name != 'retract'){
            $('#param').show();
            $('#scale_name').removeClass('dn');
            url = checkP();
            if(url.indexOf('si') > 0){
                window.location.href = url.replace($('input[name=si]').val(),scale);
            }else{
                window.location.href = url + '/si/' + scale;
            }
            $('input[name=si]').val(scale);
        }
    })

    $('input').each(function(index,obj){
        $(obj).blur(function(){
            if($(obj).hasClass('require')){
                if(ignoreSpaces($(obj).val()) != ''){
                    $(obj).next('.error-area').children('span').removeClass('error-info-span').html('&nbsp;');
                    //$(obj).next('.error-area').children('span').addClass('error-info-span').text($(obj).attr('placeholder'));
                    //return false;
                }
                //else{
                //    $(obj).next('.error-area').children('span').removeClass('error-info-span').html('&nbsp;');
                //}
            }
        })
    });

    $('select').each(function(index,obj){
        $(obj).change(function(){
            if($(obj).find('option:selected').val() != ''){
                $(obj).next('.error-area').children('span').removeClass('error-info-span').html('&nbsp;');
            }
            //else{
            //    $(obj).next('.error-area').children('span').addClass('error-info-span').text($(obj).attr('placeholder'));
            //}
        })
    })

    $('input[name=website]').blur(function(){
        var website = $(this);
        var reg = /(http\:[\w\.\/]+)/gim;
        var regex = /^(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i
        if(ignoreSpaces(website.val()) != ''){
            if(IsURL(website.val())){
                website.next('.error-area').children('span').removeClass('error-info-span').html('&nbsp;');
            }else{
                if(regex.test(website.val())){
                    if(!reg.test(website.val())){
                        website.val('http://' + website.val());
                    }
                }else{
                    website.next('.error-area').children('span').addClass('error-info-span').text(website.attr('placeholder'));
                }
            }
        }
    })

    $('input[name=full_name]').blur(function(){
        if($('input[name=full_name]').val().lengthB() <= 40) {
            $('input[name=full_name]').next('.error-area').children('span').removeClass('error-info-span').html('&nbsp;');
        }
    })

    $('.contact_name').blur(function(){
        var val=ignoreSpaces($(this).val());
        if(val){
            $(this).parent().next('.error-area').children('span').text('联系人不可为空');
            $(this).parent().next('.error-area').children('span').addClass('dn');    
        }
    })
    $('.mobile').blur(function(){
        var val=ignoreSpaces($(this).val());
        if(val){
            $(this).parent().next('.error-area').children('span').text('手机和座机请至少填写一项');
            $(this).parent().next('.error-area').children('span').addClass('dn');
            $('.phone-span').text('手机和座机请至少填写一项');  
            $('.phone-span').addClass('dn');  
        }
    })

});

function check(){
    var bool = true;
    $('.require').each(function(index,obj){
        var value = $(obj).val();
        if(ignoreSpaces(value) == ''){
            $(obj).next('.error-area').children('span').addClass('error-info-span').text($(obj).attr('data-placeholder'));
            bool = false;
        }else{
            $(obj).next('.error-area').children('span').removeClass('error-info-span').html('&nbsp;');
        }
    })
    var scale = $('#scale_id option:selected').val();
    var industry = $('#industry_id option:selected').val();
    var website = $('input[name=website]');
    var reg = /(http\:[\w\.\/]+)/gim;
    var regex = /^(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i
    var contact = $('.contact_name').val();
    var mobile = ignoreSpaces($('.mobile').val());
    var code = ignoreSpaces($('.phone_code').val());
    var number = ignoreSpaces($('.phone_number').val());
    var ext = ignoreSpaces($('.phone_ext').val());
    var code_number = '';
    if(number && code){
        code_number = code+number;
    }
    if(ext && number && code){
        code_number = code+number+ext;
    }

    if(ignoreSpaces(website.val()) != ''){
        if(IsURL(website.val())){
            website.next('.error-area').children('span').removeClass('error-info-span').html('&nbsp;');
        }else{
            if(regex.test(website.val())){
                if(!reg.test(website.val())){
                    website.val('http://' + website.val());
                }
            }else{
                website.next('.error-area').children('span').addClass('error-info-span').text(website.attr('data-placeholder'));
                bool = false;
            }
        }
    }
    if(scale == ''){
        $('#scale').addClass('error-info-span').text('规模不可为空');
        bool = false;
    }else{
        $('#scale').removeClass('error-info-span').html('&nbsp;');
    }

    if(industry == ''){
        $('#industry').addClass('error-info-span').text('行业不可为空');
        bool = false;
    }else{
        $('#industry').removeClass('error-info-span').html('&nbsp;');
    }

    if($('input[name=img]').val() == '' && $('#logo').val() == ''){
        $('#upload').next('.error-area').children('span').addClass('error-info-span').text('请上传图片');
        bool = false;
    }else{
        $('#upload').next('.error-area').children('span').removeClass('error-info-span').html('&nbsp;');
    }

    if($('input[name=city_name]').val() == ''){
        $('input[name=city_name]').next('.error-area').children('span').addClass('error-info-span').text('企业所在地不可为空');
        bool = false;
    }else{
        $('input[name=city_name]').next('.error-area').children('span').removeClass('error-info-span').html('&nbsp;');
    }

    if($('input[name=full_name]').val().lengthB() > 40){
        $('input[name=full_name]').next('.error-area').children('span').addClass('error-info-span').text('不可超过20个汉字');
        bool = false;
    }else{
        $('input[name=full_name]').next('.error-area').children('span').removeClass('error-info-span').html('&nbsp;');
    }

    if(!ignoreSpaces(contact)){
       $('.contact_name').parent().next('.error-area').children('span').removeClass('dn');
        bool = false;
    }else{
        $('.contact_name').parent().next('.error-area').children('span').addClass('dn');
    }
    if(contact.length>10){
            $('.contact_name').parent().next('.error-area').children('span').text('不超过10个字符');
            $('.contact_name').parent().next('.error-area').children('span').removeClass('dn');
            bool = false;
    }
    if(!ignoreSpaces(mobile) && !ignoreSpaces(code_number)){
        $('.mobile').parent().next('.error-area').children('span').removeClass('dn');
        $('.phone_ext').parent().next('.error-area').children('span').removeClass('dn');
        bool = false;
        return bool;
    }
    if(ignoreSpaces(mobile)){
        var m_reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
        if(!m_reg.test(mobile)){
            $('.mobile').parent().next('.error-area').children('span').text('输入合法的11位手机号');
            $('.mobile').parent().next('.error-area').children('span').removeClass('dn');
            bool = false;
        }else{
            $('.mobile').parent().next('.error-area').children('span').text('手机和座机请至少填写一项');
            $('.mobile').parent().next('.error-area').children('span').addClass('dn');   
        }
    }
    if(ignoreSpaces(code)){
        var m_reg = /^0?\d{3,4}$/;
        if(!m_reg.test(code)){
            $('.phone_ext').parent().next('.error-area').children('span').text('输入合法的区号');
            $('.phone_ext').parent().next('.error-area').children('span').removeClass('dn');
            bool = false;
        }else{
            $('.phone_ext').parent().next('.error-area').children('span').text('手机和座机请至少填写一项');
            $('.phone_ext').parent().next('.error-area').children('span').addClass('dn');   
        }

        var m_reg = /^0?\d{7,8}$/;
        if(!m_reg.test(number)){
            $('.phone_ext').parent().next('.error-area').children('span').text('输入合法的总机号');
            $('.phone_ext').parent().next('.error-area').children('span').removeClass('dn');
            bool = false;
        }else{
            $('.phone_ext').parent().next('.error-area').children('span').text('手机和座机请至少填写一项');
            $('.phone_ext').parent().next('.error-area').children('span').addClass('dn');              
        }
    }
    if(ignoreSpaces(number)){
        var m_reg = /^0?\d{3,4}$/;
        if(!m_reg.test(code)){
            $('.phone_ext').parent().next('.error-area').children('span').text('输入合法的区号');
            $('.phone_ext').parent().next('.error-area').children('span').removeClass('dn');
            bool = false;
        }else{
            $('.phone_ext').parent().next('.error-area').children('span').text('手机和座机请至少填写一项');
            $('.phone_ext').parent().next('.error-area').children('span').addClass('dn');   
        }

        var m_reg = /^0?\d{7,8}$/;
        if(!m_reg.test(number)){
            $('.phone_ext').parent().next('.error-area').children('span').text('输入合法的总机号');
            $('.phone_ext').parent().next('.error-area').children('span').removeClass('dn');
            bool = false;
        }else{
            $('.phone_ext').parent().next('.error-area').children('span').text('手机和座机请至少填写一项');
            $('.phone_ext').parent().next('.error-area').children('span').addClass('dn');              
        }
    }
    if(ignoreSpaces(ext)){
        var m_reg = /^\d{1,}$/;
        if(!m_reg.test(ext)){
            $('.phone_ext').parent().next('.error-area').children('span').text('输入合法的分机号');
            $('.phone_ext').parent().next('.error-area').children('span').removeClass('dn');
            bool = false;
        }else{
            $('.phone_ext').parent().next('.error-area').children('span').text('手机和座机请至少填写一项');
            $('.phone_ext').parent().next('.error-area').children('span').addClass('dn');          
        }
    }

    return bool;
}

function choosen_close(obj) {
    $(obj).parent().addClass("dn");
    var url = window.location.href;
    if ($('#param .dn').length >= 2) {
        $('#param').hide();
    }
    var type = $(obj).attr('data-type');
    url = checkP();
    if (type == 'city') {
        var term = $('input[name=ct]').val();
        $.post('/Upload/getCityId',{'city':term},function(data){
            term = data.cityId;
            window.location.href = url.replace('/ct/' + term,'');
        },'json')
        $('input[name=ct]').val('');
    } else if(type == 'industry') {
        term = $('input[name=in]').val();
        window.location.href = url.replace('/in/' + term,'');
        $('input[name=in]').val('');
    }else{
        term = $('input[name=si]').val();
        window.location.href = url.replace('/si/' + term,'');
        $('input[name=si]').val('');
    }
}

function ignoreSpaces(string) {
    var temp = "";
    string = '' + string;
    splitstring = string.split(" ");
    for(i = 0; i < splitstring.length; i++)
        temp += splitstring[i];
    return temp;
}

function urlencode (str) {
    str = (str + '').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').
        replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

function IsURL(str_url){
    var regex = /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
    return regex.test(str_url);
}

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

function checkP(){
    var url = window.location.href;
    if(url.indexOf('p') > 0){
        var page = $('input[name=p]');
        url = url.replace('/p/' + page.val() + '.html','');
    }
    return url;
}