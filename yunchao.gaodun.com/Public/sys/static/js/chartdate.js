/**
 * Created by gaodun on 2015/7/17.
 */
$(function(){
    $('.datetimepicker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'cn',
        autoclose: true
    });

    $('#type').change(function(){
        $('input[name=check_type]').val($(this).find('option:selected').val());
    })

    $('#check').click(function(){
        var start = $('input[name=date_start]').val();
        var end = $('input[name=date_end]').val();
        if(start != ''){
            if(end == ''){
                alert('请选择结束时间');
                return false;
            }
        }
        if(end != ''){
            if(start == ''){
                alert('请选择开始时间');
                return false;
            }
        }



        if(start != '' && end != ''){
            var num = daysBetween(start,end);
            var type = $('input[name=check_type]').val();
            if(num < 0){
                alert('起始时间不得大于结束时间');
                return false;
            }
            if(type == 5 || type == 6 || type == 7 || type==4){
                if(num > 31){
                    alert('建议选择的时间区间在31天内');
                    return false;
                }
            }
        }

        $('form').submit();
    })
})

function daysBetween(DateOne,DateTwo)
{
    var OneMonth = DateOne.substring(5,DateOne.lastIndexOf ('-'));
    var OneDay = DateOne.substring(DateOne.length,DateOne.lastIndexOf ('-')+1);
    var OneYear = DateOne.substring(0,DateOne.indexOf ('-'));

    var TwoMonth = DateTwo.substring(5,DateTwo.lastIndexOf ('-'));
    var TwoDay = DateTwo.substring(DateTwo.length,DateTwo.lastIndexOf ('-')+1);
    var TwoYear = DateTwo.substring(0,DateTwo.indexOf ('-'));

    var cha=((Date.parse(TwoMonth+'/'+TwoDay+'/'+TwoYear)- Date.parse(OneMonth+'/'+OneDay+'/'+OneYear))/86400000);
    return cha;
}