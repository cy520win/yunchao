    $(function(){
            var oUlh=$(".user-header >ul");
            var ulHh=$(".user-header >ul >li").length*40+20;
            var wh = $(document).height();
            var bb = true;
            var oUlh=$(".user-header >ul");
            var ulHh=$(".user-header >ul >li").length*40+40;

            $(".user-header").mouseover(function(){

                oUlh.css("height",ulHh);
            });
            $(".user-header").mouseout(function(){
                oUlh.css("height",0);
            });

            // if(wh > 1075){
            //     $('.center-content').css('min-height','1075px');
            // }else{
            //     $('.center-content').css('min-height',wh-$('#footer').height()-$('#header').height());
            // }
        });
        function logout(){
            var status = WB2.checkLogin();
            if(status){
                    WB2.logout(function(){
                        window.location.href = "/Account/logout";
                    });               
            }
            if(!status){
                $.get('/Account/out',function(data){
                    window.location.reload();
                },'json')                
            }
        }
        addLoadEvent(ie8banner);
        function addLoadEvent(fn){
            var oldLoad=window.onload;
            if(typeof window.onload!='function'){
                window.onload=fn;
            }else{
                window.onload=function(){
                    oldLoad();
                    fn();
                };
            }
        }
        function ie8banner() {
            var ie8Prompt=document.getElementById("ie8-prompt");
            var user=window.navigator.userAgent;
            var re=/MSIE 9.0|MSIE 8.0|MSIE 7.0|MSIE 6.0/;
            if(user.search(re)>0){
                ie8Prompt.style.display="block";
            }
        }
        $(function(){
            var user1=window.navigator.userAgent;
            var re1=/MSIE 8.0|MSIE 7.0|MSIE 6.0/;
            if(user1.search(re1)>0){
                $(".header-nav").addClass("ie8-nav");
            }
        });