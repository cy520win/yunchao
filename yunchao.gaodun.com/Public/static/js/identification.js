$(function(){
    var h=$(document).height();
    $("#footer").css("top",h-100);
    $("#reset-send").click(function(){              
        $(".md-overlay").fadeIn("1000");
        $("#sendsuccess-form").fadeIn("500");
    	$.post("/Account/checkMailAgain",'',function(data){
    		if(data.status){
	            return true;
    		}else{
    		      return false;
    		}
    	},'json')
    });
    $("#close-send-success").click(function(){
        $(".md-overlay").fadeOut("300");
        $("#sendsuccess-form").fadeOut("300");
    });
});