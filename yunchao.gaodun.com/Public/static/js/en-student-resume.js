/**
 * Created by gaodun on 2015/9/23.
 */
$(function(){
    $('.right-menu-li').each(function(index,obj){
        $(obj).hover(
            function(){
                var menu = $(obj).attr('data-type');
                if(menu!='Base' || menu!='Intents'){
                    $(obj).children('.del-right-menu').show();
                }
            },function(){
                $(obj).children('.del-right-menu').hide();
            }
        )
    });

    //下拉框改变后对报错进行清除
    $('.select2-offscreen').change(function(){
        $(this).removeClass('input-border-error');
    });

    //文本框移动后自动清除报错
    $('input').blur(function(){
        $(this).removeClass('input-border-error');
    });

    //Dear Hr 的特殊处理
    $('#Dear-Up').click(function(){
        $('#input-Dear').removeClass('dn');
        $('#Dear-div').removeClass('dn');
    })

    $(document).click(function(){
        $('.certification-honor').removeClass('isvisible');
        $('.del-li-div').remove();
    })

    rate();
    getselect();
})

//点击弹出下层面板
function menuShow(obj){
    var menu = $(obj).attr('data-value');
    //menu 1为关闭状态 2为开启状态
    if(menu == 1){
        $('#menu-button').addClass('open_menu');
        $('#menu-down').addClass('dn');
        $(obj).attr('data-value',2);
    }else{
        $('#menu-button').removeClass('open_menu');
        $('#menu-down').removeClass('dn');
        $(obj).attr('data-value',1);
    }

}

//右边栏新增
function menuUp(obj){
    var menu = $(obj).attr('data-type');
    $('#module-list').removeClass('dn');
    $('#' + menu + '-Up').removeClass('dn');
    $('#' + menu + '-Down').addClass('dn');
    $('#' + menu + '-div').removeClass('dn');

    if(menu == 'Internship'){
        $(obj).attr('data-type','Practice');
    }

    //Dear Hr特殊处理
    if(menu == 'Dear'){
        $.post('/EnStudent/returnAddTpl',{label:menu},function(data){
            $('#' + menu + '-edit').addClass('dn');
            $('#input-' + menu).html(data.html);
        },'json')
    }else if(menu == 'Certificates' || menu == 'Campus'){
        if(event.stopPropagation){
            event.stopPropagation();
        }else{
            event.cancelBubble = true;//ie8以下
        }
        $('#'+menu+'-ul').addClass('isvisible');
    }else{
        addTpl(obj);
    }

    if($('#menu-down').children('ul').children('.dn').length >=5){
        $('#menu-button').addClass('dn');
        $('#menu-down').addClass('dn');
    }
}

//右边栏删除
function menuDel(obj,e){
    var event = e || window.event;
    if(event.stopPropagation){
        event.stopPropagation();
    }else{
        event.cancelBubble = true;//ie8以下
    }
    var menu = $(obj).parent('li').attr('data-type');
    console.log(menu);
    var str = '<div class="del-li-div"><div></div><p>确认要删除本模块吗？</p><div id="del-li-div"><span data-type="'+menu+'" class="btn btn-sure delete-button" onclick="delModule(this)">Sure</span><span class="btn btn-cancle information-cancel-button" onclick="cancelModule(this)">Cancel</span></div></div>';
    $(obj).parent('li').append(str);
}

//右边栏显示与隐藏
function menuDn(menu){
    $('#' + menu + '-Up').addClass('dn');
    $('#' + menu + '-Down').removeClass('dn');
    $('#' + menu + '-div').addClass('dn');
    if($('#menu-down').children('ul').children('.dn').length <5){
        $('#menu-button').removeClass('dn');
        $('#menu-down').removeClass('dn');
    }
    $('#dragger-menu').removeClass('dn');
}

function delModule(obj){
    var label = $(obj).attr('data-type');
    $.post('/EnStudent/delModule',{label : label},function(data){
        window.location.reload();
    },'json');
}

function cancelModule(obj){
    $(obj).parent('div').remove();
}

//多模块的展现
function tipShow(obj,event){
    if(event.stopPropagation){
        event.stopPropagation();
    }else{
        event.cancelBubble = true;//ie8以下
    }
    $('.certification-honor').each(function(obj,index){
        $(index).removeClass('isvisible');
        $(index).parent('span').attr('data',1);
    })

    var type = $(obj).attr('data');
    if(type == 1){
        $(obj).children('ul').addClass('isvisible');
        $(obj).attr('data',0);
    }else{
        $(obj).children('ul').removeClass('isvisible');
        $(obj).children('ul>li').removeClass('dn');
        $(obj).attr('data',1);
    }
}

//统一新增模板加载
function addTpl(obj){
    var label = $(obj).attr('data-type');
    if(moduleArr(label) != 'Campus' && moduleArr(label) != 'Certification'){
        $(obj).addClass('dn');
    }

    $('#input-information-'+label).children('ul').children('.dn').removeClass('dn');
    var addDiv = '#input-' + label;
    $.post('/EnStudent/returnAddTpl',{label : label},function(data){
        $(addDiv).removeClass('dn');
        $(addDiv).html(data.html);
        if(label == 'StudentCertificate'){
            cerChange();
        }
        getselect();
    },'json')
}

//统一编辑模板加载
function editTpl(obj){
    var label = $(obj).attr('data-type');
    var id = $(obj).attr('data-id');
    $('#input-information-'+label).children('ul').children('.dn').removeClass('dn');
    $(obj).addClass('dn');
    var addDiv = '#input-' + label;
    $.post('/EnStudent/returnEditTpl',{label : label,id : id},function(data){
        $(addDiv).removeClass('dn');
        $(addDiv).html(data.html);
        if(label == 'StudentCertificate'){
            cerChange();
        }
        getselect();
    },'json');
}

//特殊编辑模板 （实习意向，Dear Hr,基础信息）
function editSpecial(obj){
    var special = $(obj).attr('data-type');
    $(obj).addClass('dn');
    $.post('/EnStudent/returnSpecialTpl',{label:special},function(data){
        $('#input-information-'+moduleArr(special)).addClass('dn');
        $('#input-'+moduleArr(special)).html(data.html);
        $('#input-'+moduleArr(special)).removeClass('dn');
        getselect();
        hotcity("div");
        hotcity('homecity10',true);
        if(special == 'Dear'){
            checkWord();
            charlen();
        }
    },'json');
}

//统一关闭新增模板
function cancelAdd(obj){
    var cancel = $(obj).attr('data-type');
    $('#div-'+cancel+'-add').remove();
    $('#input-'+cancel).addClass('dn');
    $('.add-chat-'+moduleArr(cancel)).removeClass('dn');
    $('.add-chat-'+cancel).removeClass('dn');
    if(cancel == 'Dear'){
        $('#' + cancel + '-div').addClass('dn');
    }
}

//统一关闭编辑模板
function cancelEdit(obj){
    var cancel = $(obj).attr('data-type');
    $('#input-information-'+cancel).children('ul').children('.dn').removeClass('dn');
    $('#div-'+cancel+'-edit').remove();
    $('#input-'+cancel).addClass('dn');
}

//关闭特殊编辑模块
function cancelSpecial(obj){
    var cancel = $(obj).attr('data-type');
    $('#input-information-'+moduleArr(cancel)).removeClass('dn');
    $('.add-chat-'+cancel).removeClass('dn');
    $('#input-'+moduleArr(cancel)).addClass('dn');
}

//统一新增与编辑
function saveInfo(obj){
    var label = $(obj).attr('data-type');
    var status = $('input[name=label_status]').val();
    $.post('/EnStudent/saveInfo',$('#form-' + label + '-' + status).serialize(),function(data){
        if(data.status == 'fail'){
            var errors = data.errors;
            if(errors){
                for(var i = 0;i<errors.length;i++){
                    if(label == 'StudentCertificate'){
                        $('.'+errors[i]).removeClass('dn');
                    }
                    $('[name=' + errors[i] + ']').addClass('input-border-error');
                    $('[name=' + errors[i] + ']').removeClass('input-grey-style');
                }
            }
        }else {
            $('#input-'+label).addClass('dn');
            $('#div-'+label+'-' + status).remove();
            $('#input-information-' + label).html(data.html);
            if(status == 'add'){
                $('.add-chat-'+label).removeClass('dn');
            }
            rate();
        }

    },'json')
}



//统一删除
function del(obj){
    var label = $(obj).attr('data-type');
    var id = $(obj).attr('data-id');
    $.post('/EnStudent/delItem',{label : label,id : id},function(data){
        if(data.status == 'success'){
            $('#input-'+label).addClass('dn');
            $('#div-'+label+'-' + status).remove();
            $('#input-information-' + label).html(data.html);
            rate();
        }
    },'json');
}

//统一删除弹出框
function delShow(obj){
    var label = $(obj).attr('data-type');
    $('#notice-'+label).removeClass('dn');
}

//统一关闭删除弹出框
function closeNotice(obj){
    var label = $(obj).attr('data-type');
    $('#notice-'+label).addClass('dn');
}

//根据模块返回相应的对象名称 基于有2大模块合二为一的状态
function moduleArr(name){
    var moduleArr = new Array();
    moduleArr['Base'] = 'Base';
    moduleArr['Education'] = 'Education';
    moduleArr['Practice'] = 'Internship';
    moduleArr['JobExperience'] = 'Campus';
    moduleArr['ActivityExperience'] = 'Campus';
    moduleArr['PrizeExperience'] = 'Certification';
    moduleArr['StudentCertificate'] = 'Certification';
    moduleArr['Dear'] = 'Dear';
    moduleArr['PostSubscribe'] = 'Intents';
    return moduleArr[name];
}

//修改或保存特殊信息
function saveSpecial(obj){
    var status = $(obj).attr('data-value');
    var save = $(obj).attr('data-type');
    $.post('/EnStudent/saveSpecial',$('#form-'+save+'-'+status).serialize(),function(data){
        if(data.status == 'fail'){
            if(data.errors){
                var errors = data.errors;
                for(var i = 0;i<errors.length;i++){
                    if(save == 'PostSubscribe'){
                        $('[name=' + errors[i] + ']').removeClass('input-grey-style');
                    }
                    $('[name=' + errors[i] + ']').addClass('input-border-error');
                }
            }
        }else{
            $('#input-'+moduleArr(save)).addClass('dn');
            $('#input-information-'+moduleArr(save)).removeClass('dn');
            $('#input-information-'+moduleArr(save)).html(data.html);
            $('.add-chat-'+save).removeClass('dn');
            if(save == 'Base' || save == 'PostSubscribe'){
                window.location.reload();
            }
            rate();
        }
    },'json')
}

//统一清除报错class
function cleanError(obj){
    $(obj).removeClass('input-border-error');
    $(obj).addClass('input-grey-style');
}

//点击性别图标
function getgender(obj,id){
    $('#edit-gender').val(parseInt(id));
    $('.sex-choose').removeClass('span-checked');
    $(obj).addClass('span-checked');
}
//点击身份图标
function getpolitics(obj,id){
    $('#edit-politics-status').val(parseInt(id));
    $('.Party-choosen').removeClass('span-checked');
    $(obj).addClass('span-checked');
}

function getselect(){
    //下拉框
    $("#sun-3,#sun-1,#sun-2,#sun-4,#sun-43,#sun-3-2,#certificate-type,#certificate-id,#mobile_type,#status").select2({
        placeholder: '',
        allowClear: true,
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
}


function cerChange(){
    //证书切换时候的改变
    $('#certificate-type').change(function(){
        $('#certificate-id').removeClass('input-border-error');
        $('#cert-name-other').removeClass('input-border-error');
        var id = $(this).find('option:selected').val();
        if(id == 0){
            $('#certificate-id').addClass('dn');
            $('#certificate-id').val('');
            $('#cert-name-other').removeClass('dn');
            return false;
        }
        $.post('/EnStudent/certList',{id : id},function(data){
            $('#certificate-id').removeClass('dn');
            $('#cert-name-other').addClass('dn');
            $('#certificate-id').html(data.data);
        },'json');
    })

    $('#certificate-id').change(function(){
        $('.cert-repeat').addClass('dn');
    })
}






//初始化执行
uploadHead('upload');


function charlen(obj){
    if(event.which==8){
        return true;
    }else{
        if(JHshStrLen($(obj).val())>=900){
            $(obj).addClass('input-border-error');
            return false;
        }else{
            $(obj).removeClass('input-border-error');
        }
    }
}
//字数限制
function JHshStrLen(sString){
    var sStr,iCount,i,strTemp ;
    iCount = 0 ;
    sStr = sString.split("");
    for (i = 0 ; i < sStr.length ; i ++)  {
        strTemp = escape(sStr[i]);
        if (strTemp.indexOf("%u",0) == -1) // 表示是汉字
        {
            iCount = iCount + 1 ;
        }else  {
            iCount = iCount + 2 ;
        }
    }
    return iCount ;
}

function rate(){
    $.get('/EnStudent/resume_rate',function(data){
        if(data){
            $('.complete-circle').html(data);
        }
    },'json')
}

//日历挂件
function getdate(id,obj,e){
    var e = e || event
    if(e.stopPropagation){
        e.stopPropagation();
    }else{
        e.cancelBubble = true;//ie8以下
    }
    getlaydate(id);
}


function getlaydate(id){
    laydate.skin('molv');
    laydate({elem:'#'+id,format:'YYYY-MM-DD'});
}

function uploadHead(id){
    if($('#'+id).length<1){
        return false;
    }
    new AjaxUpload(id,{
        action : '/Upload/upload',
        name : 'headpic',
        autoSubmit: true,
        responseType: 'json',
        onComplete : function(file, response) {
            if(response.status == 'success'){
                $('#headpic_img').attr('src', response.path);
                $('.img-container img').attr('src', response.path);
                $(".photomodify").show();
                $(".md-overlay2").addClass("apperance");
                crop();
            }else{
                alert(response.msg);
            }

        }
    });

//切图
    function crop(){
        var preview_size = [114, 154],
            aspect_ratio = preview_size[0] / preview_size[1],

            $image = $(".img-container img"),
            $x = $("#img-1-x"),
            $y = $("#img-1-y"),
            $w = $("#img-1-w"),
            $h = $("#img-1-h");

        // Plugin Initialization
        $image.cropper({
            aspectRatio: aspect_ratio,
            preview: '#img-preview',
            dragCrop: false,
            minWidth:435,  //需要除以2.9进行处理
            minHeight:435,
            done: function(data)
            {
                $x.text( data.x );
                $y.text( data.y );
                $w.text( data.width );
                $h.text( data.height );
            }
        });

        // Preview Image Setup (based on defined crop width and height
        $("#img-preview").css({
            width: preview_size[0],
            height: preview_size[1]
        });

        $("#crop-img").on('click', function(ev)
        {
            ev.preventDefault();
            var img_src = $('#headpic_img').attr('src');
            $.post('/Upload/crop',{'x': $x.text(),'y': $y.text(),'w': $w.text(),'h': $h.text(),'tw':
                preview_size[0],'th': preview_size[1],'src' : img_src,'model':'headpic'},function(data){
                $('input[name=img]').val(data.path);
                $('#upload').attr('src',  '/' + data.path);
                $('#logo').val('');
                $(".photomodify").html(resetHtml());
                $(".photomodify").hide();
                $(".md-overlay2").removeClass("apperance");
                $('#upload').next('.error-area').children('span').removeClass('error-info-span').html('&nbsp;');
            },'json')
        });

        $('#crop-close').on('click',function(ev){
            ev.preventDefault();
            close_crop()
        })
    }

    function resetHtml(){
        var html = '<h1><span>请选择裁剪区域</span></h1><div class="panel-body photo_form"><div class="img-container">';
        html += '<img id="headpic_img" class="img-responsive"/></div><div style="display: none">';
        html += '<div id="img-1-x"></div><div id="img-1-y"></div><div id="img-1-w"></div><div id="img-1-h"></div></div>';
        html += '<div class="photomodify_footer"><input type="hidden" name="img"><button id="crop-img" class="btn btn-info confirm-button button-small mt20">Save</button> ' +
        '<button id="crop-close" class="btn confirm-button information-cancel-button button-small mt20">Cancel</button></div></div></div>';
        return html;
    }

    function close_crop(){
        $(".photomodify").html(resetHtml());
        $(".photomodify").hide();
        $(".md-overlay2").removeClass("apperance");
    }

}

var maxstrlen=900;
function Q(s){return document.getElementById(s);}
function checkWord(c){
    len=maxstrlen;
    var str = c.value;
    myLen=getStrleng(str);
    var wck=Q("wordCheck");
    if(myLen>len){
        c.value=str.substring(0,i+1);
    }
    else{
        wck.innerHTML = Math.floor((len*2-myLen)/2);
    }
}
function getStrleng(str){
    myLen =0;
    i=0;
    for(;(i<str.length)&&(myLen<=maxstrlen);i++){
        if(str.charCodeAt(i)>0&&str.charCodeAt(i)<128)
            myLen++;
        else
            myLen+=2;
    }
    return myLen;
}