$(function(){
    init();
});

function init(){
    //换一批
    $('.change-account').click(function(){
        var text="";
        $(".choosed").each(function() {
            text += ","+$(this).val();
        });
        $.post('/ResumePost/getTagLists',{type:'choose',p:$(this).attr('data-page'),count:$(this).attr('data-count'),lists:text},function(data){
            $('.choose-area').html(data.html);
            init();
        },'json')
    })

    $(".choosen-area .limit-math").css("color","#63cdff").text($(".choosen-pag-area").find("li").length+"/10");

    var  choosen_list=$(".choosen-pag-area").find("li");
    var pagSize=$(".choosen-pag-area").find("li").length;
    var  limit_math=$(".choosen-area .limit-math")

    //关闭按钮
    $("#chooen-area-list").on("click",".close-button",function(event){
        event.stopPropagation();
        $(this).parent("li").remove();
        var id = $(this).attr('data-id');
        if($('.tag-'+id)){
            $('.tag-'+id).removeClass('dn');
        }
        var pagSize=$(".choosen-pag-area").find("li").length;
        if(pagSize<=10){
            $(".increate-pag").css("visibility","hidden");
            if(pagSize <= 0){pagSize = 0}
            $(".choosen-area .limit-math").css("color","#63cdff").text(pagSize+"/10");
        }
    });
    //选择标签
    $(".choose-pag-area").find("li").on("click",function(event){
        event.stopPropagation();
        var relation_id=$(this).attr('data-value');
        var pagSize=$(".choosen-pag-area").find("li").length;
        var choosen_list_text=$(".choosen-pag-area").find("li").text().trim().split("|");
        for(var i=0;i<choosen_list_text.length;i++){
            if(choosen_list_text[i].trim()==$(this).html()){
                return false;
            }
        }
        if(pagSize<=9){
            var pagcontent=$(this).text().trim();
            $(".choosen-pag-area").append("<li><span class='pag-title'>"+pagcontent+"</span><span class='close-button' data-id='"+relation_id+"'>|</span><input type='hidden' class='choosed' name='relation[]' value='"+relation_id+"'></li>")
            $(".increate-pag").css("visibility","hidden");
            $(this).addClass('dn');
        }
        pagSize++;
        $(".choosen-area .limit-math").text(pagSize+"/10");
        if(pagSize>10){
            $(".choosen-area .limit-math").css("color","#ff5d39");
            $(".increate-pag").css("visibility","visible").text('您已添加10个标签了，不能再添加了');
            pagSize=10;
            $(".choosen-area .limit-math").text(pagSize+"/10");
            return false;
        }
    });
    //input标签输入

    $(".click-down").on("click",function (event){
        event.stopPropagation();
        var number = $(this).attr('data-number');
        if(number > 1){
            return false;
        }
        $(this).attr('data-number',2);
        var pagSize=$(".choosen-pag-area").find("li").length;
        var zVal=$("#chooen-area-list").find("li").text().trim();
        var vali=$(".self-input").val().trim();
        if(vali == ''){
            return false;
        }
        //重复不能输入
        var choosen_list_text=$(".choosen-pag-area").find("li").text().trim().split("|");
        for(var i=0;i<choosen_list_text.length;i++){
            if(choosen_list_text[i].trim()==vali){
                $('.increate-pag').css('visibility','visible').text('此标签已添加')
                return false;
            }
        }
        //输入多了提示
        if(pagSize<=9){
            $.post('/ResumePost/tagAdd',{title:vali},function(data) {
                $(".choosen-pag-area").append("<li><span class='pag-title'>" + vali + "</span><span class='close-button' data-id='"+data.pkid+"'>|</span><input type='hidden' class='choosed' name='relation[]' value='" + data.pkid + "'></li>")
                $(".choosen-area .limit-math").text(pagSize + "/10");
                $(this).prev().val("");
                $('.click-down').attr('data-number',1);
                $(".increate-pag").css("visibility","hidden");
                $('.self-input').val('');
            },'json')
        }
        pagSize++;
        $(".choosen-area .limit-math").text(pagSize+"/10");
        if(pagSize>10){
            pagSize=10;
            $(".choosen-area .limit-math").css("color","#ff5d39");
            $(".increate-pag").css("visibility","visible").text('您已添加10个标签了，不能再添加了');
            $(".choosen-area .limit-math").text(pagSize+"/10");
            $('.self-input').val('');
            $('.click-down').attr('data-number',1);
            return false;
        }
    });

    //input框选中样式
    $(".self-input").focus(function(){
        $(this).addClass("focusen");
        $(this).next().addClass("actived");
    }).blur(function(){
        $(this).removeClass("focusen");
        $(this).next().removeClass("actived");
    });
    //键盘keydown input添加
    $(".self-input").keyup(function(event){
        if(event.keyCode == 13){
            event.stopPropagation();
            var number = $(this).attr('data-number');
            if(number > 1){
                return false;
            }
            $(this).attr('data-number',2);
            var pagSize=$(".choosen-pag-area").find("li").length;
            var zVal=$("#chooen-area-list").find("li").text().trim();
            var vali=$(".self-input").val().trim();
            if(vali == ''){
                return false;
            }
            //重复不能输入
            var choosen_list_text=$(".choosen-pag-area").find("li").text().trim().split("|");
            for(var i=0;i<choosen_list_text.length;i++){
                if(choosen_list_text[i].trim()==vali){
                    $('.increate-pag').css('visibility','visible').text('此标签已添加')
                    return false;
                }
            }
            //输入多了提示
            if(pagSize<=9){
                $.post('/ResumePost/tagAdd',{title:vali},function(data){
                    $(".choosen-pag-area").append("<li><span class='pag-title'>"+vali+"</span><span class='close-button' data-id='"+data.pkid+"'>|</span><input type='hidden' class='choosed' name='relation[]' value='"+data.pkid+"'></li>")
                    $(".choosen-area .limit-math").text(pagSize+"/10");
                    $(this).prev().val("");
                    $('.click-down').attr('data-number',1);
                    $(".increate-pag").css("visibility","hidden");
                },'json')
            }
            pagSize++;
            $(".choosen-area .limit-math").text(pagSize+"/10");
            if(pagSize>10){
                pagSize=10;
                $(".choosen-area .limit-math").css("color","#ff5d39");
                $(".increate-pag").css("visibility","visible").text('您已添加10个标签了，不能再添加了');
                $(".choosen-area .limit-math").text(pagSize+"/10");
                $('.click-down').attr('data-number',1);
                return false;
            }
        }
    });
}