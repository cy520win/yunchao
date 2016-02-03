/**
 * Created by allen on 2015/5/27.
 */
jQuery(document).ready(function(){
    var url = window.location.href;
    $('#city>dd>span').click(function(){
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

    $('#position-category>dd>span').click(function(){
        var category = $(this).attr('data-value');
        var category_name = $(this).attr('class');
        if(category_name != 'more' && category_name != 'retract'){
            $('#param').show();
            $('#category_name').removeClass('dn');
            url = checkP();
            if(url.indexOf('ci') > 0){
                window.location.href = url.replace('/ci/' + $('input[name=ci]').val(), '/ci/' + category);
            }else{
                window.location.href = url + '/ci/' + category;
            }
            $('input[name=ci]').val(category);
        }
    })

    $('#week>dd>span').click(function(){
        var week = $(this).attr('data-value');
        var week_name = $(this).text();
        $('#param').show();
        $('#week_name').removeClass('dn');
        url = checkP();
        if(url.indexOf('wa') > 0){
            window.location.href = url.replace('/wa/' + $('input[name=wa]').val(), '/wa/' + week);
        }else{
            window.location.href = url + '/wa/' + week;
        }
        $('input[name=wa]').val(week);
    })

    $('#education>dd>span').click(function(){
        var education = $(this).attr('data-value');
        var education_name = $(this).text();
        $('#param').show();
        $('#education_name').removeClass('dn');
        url = checkP();
        if(url.indexOf('ed') > 0){
            window.location.href = url.replace('/ed/' + $('input[name=ed]').val(), '/ed/' + education);
        }else{
            window.location.href = url + '/ed/' + education;
        }
        $('input[name=ed]').val(education);
    })

    $('#leave-work>dd>span').click(function(){
        var keep = $(this).attr('data-value');
        var keep_name = $(this).text();
        $('#param').show();
        $('#keep_name').removeClass('dn');
        url = checkP();
        if(url.indexOf('kp') > 0){
            window.location.href = url.replace('/kp/' + $('input[name=kp]').val(), '/kp/' + keep);
        }else{
            window.location.href = url + '/kp/' + keep;
        }
        $('input[name=kp]').val(keep);
    })

    $('#graduated-years>dd>span').click(function(){
        var graduate = $(this).attr('data-value');
        var graduated_name = $(this).attr('class');
        if(graduated_name != 'more' && graduated_name != 'retract'){
            $('#param').show();
            $('#graduated_name').removeClass('dn');
            url = checkP();
            if(url.indexOf('gy') > 0){
                window.location.href = url.replace('/gy/' + $('input[name=gy]').val(), '/gy/' + graduate);
            }else{
                window.location.href = url + '/gy/' + graduate;
            }
            $('input[name=gy]').val(graduate);
        }
    })

    $('#search').click(function(){
        search();
    })
    $('#keyword').on('keydown',function(e){
        if(e.keyCode == 13){
            search();
        }
    })

})

function choosen_close(obj) {
    $(obj).parent('dd').addClass("dn");
    if ($('#param .dn').length >= 4) {
        $('#param').hide();
    }
    var url = window.location.href;
    var type = $(obj).attr('data-type');
    url = checkP();
    if (type == 'city') {
        var term = $('input[name=ct]').val();
        $.post('/Upload/getCityId',{'city':term},function(data){
            term = data.cityId;
            window.location.href = url.replace('/ct/' + term,'');
        },'json')
        $('input[name=ct]').val('');
    } else if(type == 'category') {
        term = $('input[name=ci]').val();
        window.location.href = url.replace('/ci/' + term,'');
        $('input[name=ci]').val('');
    } else if(type == 'week'){
        term = $('input[name=wa]').val();
        window.location.href = url.replace('/wa/' + term,'');
        $('input[name=wa]').val('');
    } else if(type == 'education') {
        term = $('input[name=ed]').val();
        window.location.href = url.replace('/ed/' + term,'');
        $('input[name=ed]').val('');
    } else if(type == 'keep'){
        term = $('input[name=kp]').val();
        window.location.href = url.replace('/kp/' + term,'');
        $('input[name=kp]').val('');
    } else{
        term = $('input[name=gy]').val();
        window.location.href = url.replace('/gy/' + term,'');
        $('input[name=gy]').val('');
    }
}

function urlencode (str) {
    str = (str + '').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').
        replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

function search(){
    var key = $('#serch-left>p').attr('data-value');
    var keyword = ignoreSpaces($('#keyword').val());

    $('input').each(function(index,obj){
        $(obj).val('');
    })

    if(keyword == ''){
        window.location.href = '/Post/index'
    }else{
        $('input[name=key]').val(key);
        $('input[name=keyword]').val(keyword);
        window.location.href = '/Post/index/key/' + key + '/keyword/' + keyword;
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

function checkP(){
    var url = window.location.href;
    if(url.indexOf('p') > 0){
        var page = $('input[name=p]');
        url = url.replace('/p/' + page.val() + '.html','');
    }
    return url;
}