/**
 * Created by gaodun on 2015/11/6.
 */
$(function(){
    new AjaxUpload('upload',{
        action : '/Upload/upload',
        name : 'headpic',
        autoSubmit: true,
        responseType: 'json',
        onComplete : function(file, response) {
            if(response.status == 'success'){
                $('#cut_img').attr('src', response.path);
                $('#cut_img').attr('width', response.width);
                $('#cut_img').attr('height', response.height);
                $('.img-container img').attr('src', response.path);
                $(".photomodify").show();
                $('#img_grip').show();
                $(".md-overlay2").addClass("apperance");
                avatarinit();
            }else{
                alert(response.msg);
            }

        }
    });

    $("#crop-img").on('click', function(ev) {
        ev.preventDefault();
        var coordinate = $('#cut_pos').val().split(',');
        var x = coordinate[0];
        var y = coordinate[1];
        var w = coordinate[2];
        var h = coordinate[3];
        var tw = '260';
        var th = '260';
        var img_src = $('#cut_img').attr('src');
        $.post('/Upload/new_crop',{'x': x,'y': y,'w': w,'h': h,'tw': tw,'th': th,'src' : img_src},
            function(data){
            $('input[name=img]').val(data.path);
            $('#upload').attr('src',  '/' + data.path);
            $('#logo').val('');
            $(".photomodify").hide();
            $('#img_grip').hide();
            $(".md-overlay2").removeClass("apperance");
            $('#upload').next('.error-area').children('span').removeClass('error-info-span').html('&nbsp;');
        },'json')
    });

    $('#crop-close').on('click',function(ev){
        ev.preventDefault();
        close_crop()
    })

    function close_crop(){
        $(".photomodify").hide();
        $('#img_grip').hide();
        $(".md-overlay2").removeClass("apperance");
    }
})