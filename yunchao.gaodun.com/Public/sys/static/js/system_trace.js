/**
 * Created by allen on 2015/5/22.
 */
jQuery(document).ready(function(){
    $('#save').click(function(){
        if(check() == false){
            return false;
        }else{
            var id = $('input[name=id]').val();
            $.post('/admin.php?s=/Admin/SystemTrace/checkRepeat',{'name' : $('input[name=name]').val(),'id' : id},function(data){
                if(data.status == 'fail'){
                    $('input[name=name]').css('border','1px solid #ffcc00');
                    $('input[name=name]').after('<span style="color: #ff2f32">'+ data.msg +'</span>');
                    return false;
                }else{
                    $('form').submit();
                }
            },'json')
        }
    })
})

function check(){
    var bool = true;
    var name = $('input[name=name]').val();
    var remark = $('#remark').val();
    if(name == ''){
        $('input[name=name]').css('border','1px solid #ffcc00');
        bool = false;
    }else{
        $('input[name=name]').css('border','1px solid #e4e4e4');
    }
    if(remark == ''){
        $('#remark').css('border','1px solid #ffcc00');
        bool = false;
    }else{
        $('#remark').css('border','1px solid #e4e4e4');
    }
    return bool;
}