  

$(function(){
   function second(seconds){
        var i=seconds;
        var intervalid;
        intervalid=setInterval(fun,1000);
        function fun(){
       if(i==0){
         window.location.href = "../index.html";
         clearInterval(intervalid);
     }
         $(".countdown").html(i+"s"); 
            i--;
     }
    }
       
  second(60);
    
   });     