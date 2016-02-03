// -----------------------2015-07-15 15:20:16  星期三
$(function(){
  var marjor_bool = true;
  $("#div").click(function(event) {
      event.stopPropagation();
      if(marjor_bool){
          $("#div").addClass("div1");
          $(".list").show();
          marjor_bool=false;
      }
      else{
          $(".list").hide();
          $("#div").removeClass("div1");
          marjor_bool=true;
      }
    
  });

  $(".list").on("click", "li", function (event) {
       event.stopPropagation();
  
        //如果选择值大于5个就不准选择了
        var liSize=$("#div").find("ul").find("li").length;
        if(liSize>4){
         $(".list").hide();
          $("#div").removeClass("div1");
          marjor_bool=true;
         return false;
        };
        
        if((liSize+1)>0){
          $("#div >p").hide();
        }else{
          $("#div >p").show();
        }


        //禁止重复值
        var aVal=ignoreSpaces($("#div").find("li").text()).split("×");
        var val=ignoreSpaces($(this).html());
        var val_id=$(this).attr('data-id');
        if(val_id==0){
          $("#div").find("ul").html('<li>'+ val + ' <span class=\"span\">×</span><input type=\"hidden\" name=\"major_wish[]\" value='+ val_id +'></li>');
        }else{
          for(var i=0;i<aVal.length;i++){
            if(ignoreSpaces(aVal[i])==$(this).html()){
              return false;
            }
          }          
        }

        //向输入框填入值
        $('#div').next('.error-area').addClass('dn');        
        if(val_id==0){
          $("#div").find("ul").html('<li>'+ val + ' <span class=\"span\">×</span><input type=\"hidden\" name=\"major_wish[]\" value='+ val_id +'></li>');
        }else{
          if($("#div").find("ul>li>input[name='major_wish[]']").eq(0).val()==0){
            return false;
          }else{
            $("#div").find("ul").append('<li>'+ val + ' <span class=\"span\">×</span><input type=\"hidden\" name=\"major_wish[]\" value='+ val_id +'></li>');
          }
        }
      });


  //点击×删除
  $("#div").on("click", ".span", function (event) {
       var liSize=$("#div").find("ul").find("li").length;
       event.stopPropagation();
       $(this).parent("li").remove(); 

       if(liSize<=1){
          $("#div >p").show();
        }
  });

  $(document).click(function(event) {
      marjor_bool=true;
      $(".list").hide();
      $("#div").removeClass("div1");  
  });

});

function ignoreSpaces(string) {
    var temp = "";
    string = '' + string;
    splitstring = string.split(" ");
    for(i = 0; i < splitstring.length; i++)
        temp += splitstring[i];
    return temp;
}