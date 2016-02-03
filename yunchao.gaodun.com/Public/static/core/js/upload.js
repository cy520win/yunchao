/**
 * Created by allen on 2015/5/15.
 */
$(function(){
    new AjaxUpload('upload',{
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

    function crop(){
        var preview_size = [300, 300],
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
        html += '<div class="photomodify_footer"><input type="hidden" name="img"><button id="crop-img" class="btn btn-info confirm-button button-small mt20">保 存</button> ' +
        '<button id="crop-close" class="btn confirm-button information-cancel-button button-small mt20">取 消</button></div></div></div>';
        return html;
    }

    function close_crop(){
        $(".photomodify").html(resetHtml());
        $(".photomodify").hide();
        $(".md-overlay2").removeClass("apperance");
    }
})