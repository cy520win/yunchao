
$(function(){
    
$(".list-title .delete").on("click",function(event){

    $(this).parent().next().addClass("is-visible");
	return false;
});
$(".collected-detail .cancel-collected").on("click",function(event){
  $(this).parent().parent().next().addClass("is-visible");
	return false;
});
    
$(".permit-interview-box .no, .refuse-interview-box .no").on("click",function(event){
    event.stopPropagation();
    $(this).parent().removeClass("is-visible");
});

$(document).on("click",function(){
 event.stopPropagation();
     $(".permit-interview-box").removeClass("is-visible");
});

});                         