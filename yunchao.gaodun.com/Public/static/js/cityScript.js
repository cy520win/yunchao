var citySelector = {};
var default_hotcity = ["北京", "上海", "天津", "重庆"];
citySelector.pc = new Array();
citySelector.pc[0]=new Array("北京","北京市");
citySelector.pc[1]=new Array("天津","天津市");
citySelector.pc[2]=new Array("河北省","石家庄市|唐山市|秦皇岛市|邯郸市|邢台市|保定市|张家口市|承德市|沧州市|廊坊市|衡水市");
citySelector.pc[3]=new Array("山西省","太原市|大同市|阳泉市|长治市|晋城市|朔州市|晋中市|运城市|忻州市|临汾市|吕梁市");
citySelector.pc[4]=new Array("内蒙古","呼和浩特|包头市|乌海市|赤峰市|通辽市|鄂尔多斯|呼伦贝尔|巴彦淖尔|乌兰察布|兴安盟|锡林郭勒|阿拉善盟");
citySelector.pc[5]=new Array("辽宁省","沈阳市|大连市|鞍山市|抚顺市|本溪市|丹东市|锦州市|营口市|阜新市|辽阳市|盘锦市|铁岭市|朝阳市|葫芦岛市");
citySelector.pc[6]=new Array("吉林省","长春市|吉林市|四平市|辽源市|通化市|白山市|松原市|白城市|延边州");
citySelector.pc[7]=new Array("黑龙江","哈尔滨市|齐齐哈尔|鸡西市|鹤岗市|双鸭山市|大庆市|伊春市|佳木斯市|七台河市|牡丹江市|黑河市|绥化市|大兴安岭");
citySelector.pc[8]=new Array("上海","上海市");
citySelector.pc[9]=new Array("江苏省","南京市|无锡市|徐州市|常州市|苏州市|南通市|连云港市|淮安市|盐城市|扬州市|镇江市|泰州市|宿迁市");
citySelector.pc[10]=new Array("浙江省","杭州市|宁波市|温州市|嘉兴市|湖州市|绍兴市|金华市|衢州市|舟山市|台州市|丽水市");
citySelector.pc[11]=new Array("安徽省","合肥市|芜湖市|蚌埠市|淮南市|马鞍山市|淮北市|铜陵市|安庆市|黄山市|滁州市|阜阳市|宿州市|六安市|亳州市|池州市|宣城市");
citySelector.pc[12]=new Array("福建省","福州市|厦门市|莆田市|三明市|泉州市|漳州市|南平市|龙岩市|宁德市");
citySelector.pc[13]=new Array("江西省","南昌市|景德镇市|萍乡市|九江市|新余市|鹰潭市|赣州市|吉安市|宜春市|抚州市|上饶市");
citySelector.pc[14]=new Array("山东省","济南市|青岛市|淄博市|枣庄市|东营市|烟台市|潍坊市|济宁市|泰安市|威海市|日照市|莱芜市|临沂市|德州市|聊城市|滨州市|菏泽市");
citySelector.pc[15]=new Array("河南省","郑州市|开封市|洛阳市|平顶山市|安阳市|鹤壁市|新乡市|焦作市|濮阳市|许昌市|漯河市|三门峡市|南阳市|商丘市|信阳市|周口市|驻马店市");
citySelector.pc[16]=new Array("湖北省","武汉市|黄石市|十堰市|宜昌市|襄阳市|鄂州市|荆门市|孝感市|荆州市|黄冈市|咸宁市|随州市|恩施州");
citySelector.pc[17]=new Array("湖南省","长沙市|株洲市|湘潭市|衡阳市|邵阳市|岳阳市|常德市|张家界市|益阳市|郴州市|永州市|怀化市|娄底市|湘西州");
citySelector.pc[18]=new Array("广东省","广州市|韶关市|深圳市|珠海市|汕头市|佛山市|江门市|湛江市|茂名市|肇庆市|惠州市|梅州市|汕尾市|河源市|阳江市|清远市|东莞市|中山市|潮州市|揭阳市|云浮市");
citySelector.pc[19]=new Array("广西","南宁市|柳州市|桂林市|梧州市|北海市|防城港市|钦州市|贵港市|玉林市|百色市|贺州市|河池市|来宾市|崇左市");
citySelector.pc[20]=new Array("海南省","海口市|三亚市|三沙市");
citySelector.pc[21]=new Array("重庆","重庆市");
citySelector.pc[22]=new Array("四川省","成都市|自贡市|攀枝花市|泸州市|德阳市|绵阳市|广元市|遂宁市|内江市|乐山市|南充市|眉山市|宜宾市|广安市|达州市|雅安市|巴中市|资阳市|阿坝州|甘孜州|凉山州");
citySelector.pc[23]=new Array("贵州省","贵阳市|六盘水|遵义市|安顺市|毕节市|铜仁市|黔西南|黔东南|黔南州");
citySelector.pc[24]=new Array("云南省","昆明市|曲靖市|玉溪市|保山市|昭通市|丽江市|普洱市|临沧市|楚雄州|红河州|文山州|西双版纳|大理州|德宏州|怒江州|迪庆州");
citySelector.pc[25]=new Array("西藏","拉萨市|昌都|山南|日喀则|那曲|阿里|林芝");
citySelector.pc[26]=new Array("陕西省","西安市|铜川市|宝鸡市|咸阳市|渭南市|延安市|汉中市|榆林市|安康市|商洛市");
citySelector.pc[27]=new Array("甘肃省","兰州市|嘉峪关|金昌市|白银市|天水市|武威市|张掖市|平凉市|酒泉市|庆阳市|定西市|陇南市|临夏州|甘南州");
citySelector.pc[28]=new Array("青海省","西宁市|海东|海北州|黄南州|海南州|果洛州|玉树州|海西州");
citySelector.pc[29]=new Array("宁夏","银川市|石嘴山市|吴忠市|固原市|中卫市");
citySelector.pc[30]=new Array("新疆","乌鲁木齐|克拉玛依|吐鲁番|哈密|昌吉州|博尔塔拉|巴音郭楞|阿克苏|克孜勒|喀什|和田|伊犁|塔城|阿勒泰");
citySelector.pc[31]=new Array("其他","台湾省|香港|澳门|海外");

citySelector.cityInit = function (input,local,bool) {
        var city_bool=true;
        if(local){
            citySelector.hotCity = local;
        }else{
            citySelector.hotCity = default_hotcity;
        }
        $("#" + input).click(function (event) {
            if(event.stopPropagation){
                event.stopPropagation();
            }else{
                event.cancelBubble = true;
            }
            if(city_bool){
                $("body").append(citySelector.template);
                $(this).addClass("city-span-btn");
                var _top = $(this).offset().top + 40,
                    _left = $(this).offset().left,
                    _width = $(window).width();
                if (_width - _left < 450) {
                    $("#js_cityBox").css({ "top": _top + "px", "right": "0px" }).addClass("rightSelector");
                }
                else {
                    $("#js_cityBox").css({ "top": _top + "px", "left": _left + "px" });
                }

                var label = "false";
                $("#js_provList").on("click", ".provinceName", function (event) {
                    if(event.stopPropagation){
                        event.stopPropagation();
                    }else{
                         event.cancelBubble = true;
                    }
                    function createUl(_this){
                        _this.css({ "background": "#fff", "border-color": "#7de0ec", "border-bottom-color": "#fff", "position": "absolute", "top": "0", "left": "0", "z-index": "999999" });
                        var xuhao = _this.parent("li").attr("data-xuhao"),
                            cityArr = citySelector.pc[xuhao][1].split("|"),
                            cityHtmls = "<ul id='js_provCitys'>";
                        for (var i = 0; i < cityArr.length; i++) {
                            cityHtmls += "<li class='js_cityName'>" + cityArr[i] + "</li>";
                        }
                        cityHtmls += "</ul>";
                        $("#js_provCitys").remove();
                        _this.parent("li").append(cityHtmls);
                    }

                    if (label == "false") {
                        label = "true";
                        $(this).attr("now-item", "true");
                        createUl($(this));
                    }
                    else {
                        if ($(this).attr("now-item") == "" || $(this).attr("now-item") == "false" || $(this).attr("now-item") == undefined) {
                            $(this).parents("#js_provList").find("span").attr("now-item", "false");
                            $(this).attr("now-item", "true");
                            $("#js_provList span").css({ "background": "", "border-color": "", "border-bottom-color": "", "position": "", "top": "", "left": "", "z-index": "" });
                            $("#js_provCitys").remove();
                            createUl($(this));
                        }
                        else {
                            label = "false";
                            $(this).css({ "background": "", "border-color": "", "border-bottom-color": "", "position": "", "top": "", "left": "", "z-index": "" });
                            $("#js_provCitys").remove();
                        }
                    }

                });

                var _input = input;
                //多选
                if(!bool){
                    $("#js_cityBox").on("click", ".js_cityName", function (e) {
                        var  e = e || event;
                        if(e.stopPropagation){
                            e.stopPropagation();
                        }else{
                            //ie8以下
                            e.cancelBubble = true;
                        }
                            //如果选择值大于5个就不准选择了
                            var liSize=$("#" + _input).find("ul").find("li").length;
                            if(liSize>4){
                                $("#js_cityBox").remove();
                                return false;
                            };
                            
                            //禁止重复值
                            var aVal=$("#" + _input).find("ul").find("li").text().trim().split("×");
                            for(var i=0;i<aVal.length;i++){
                                if(aVal[i].trim()==$(this).html()){
                                    return false;
                                }
                            }
                            
                            //向输入框填如城市值
                            var val=$(this).html().trim();
                            $('#inputTest-li').remove();
                            $("#" + _input).find("ul").append('<li>'+ val + ' <span onclick=\"delSelCity(this,event)\">×</span><input type=\"hidden\" name=\"city_text\" value='+ val +'></li>');          

                            var liSize=$("#" + _input).find("ul").find("li").length;
                            if(liSize>4){
                                $("#js_cityBox").remove();
                                return false;
                            };

                    });
                
                    //禁止li的可输入状态
                    $("#" + _input).find("ul").find("li").each(function(){
                            $(this).attr("contentEditable",false);
                    });                    
                }

                //单选
                if(bool){
                    $("#js_cityBox").on("click", ".js_cityName", function (e) {
                        e.stopPropagation();
                        $("#" + _input).val($(this).html());
                        $("#js_cityBox").remove();
                    });
                }
                city_bool=false;
            }else{
                    $("#js_cityBox").remove();
                    city_bool=true;
                    $(this).removeClass("city-span-btn"); 
            }
        });

        citySelector.hotCityhtmls = "";
        citySelector.provHtmls = "";
        for (var j = 0; j < citySelector.pc.length; j++) {
            citySelector.provHtmls += "<li data-xuhao='" + j + "'><span class='provinceName'>" + citySelector.pc[j][0] + "</span></li>";
        }
        for (var i = 0; i < citySelector.hotCity.length; i++) {
            citySelector.hotCityhtmls += "<li class='js_cityName'>" + citySelector.hotCity[i] + "</li>";
        }
        citySelector.template = '<div class="city-box" id="js_cityBox"><img src="/Public/static/images/del_city.png" alt="" class="del-city-box" onclick="delcity(this)"/><div class="prov-city" id="js_provCity"><div class="city-title-sp"></div><p class="city-title-sp2">热门城市</p><div class="city-title-sp"></div><ul>' + citySelector.hotCityhtmls + '</ul></div><div class="provence"><div class="city-title-sp"></div><p class="city-title-sp3 city-title-sp2">选择省份</p><div class="city-title-sp"></div><div><ul id="js_provList">' + citySelector.provHtmls + '</ul></div></div></div>';

        $(document).click(function(){
            $("#js_cityBox").remove();
            city_bool=true;
            delcitywrap();
        });        
}

function delcity(obj){
    $(obj).parent().remove();
    delcitywrap();
}

function delcitywrap(){
    $("#inputTest").removeClass("city-span-btn");  
    $("#inputTest2").removeClass("city-span-btn");
    $("#inputTest10").removeClass("city-span-btn");
}

function delSelCity(obj,e){
    var e = e || event;
    if(e.stopPropagation){
            e.stopPropagation();
        }else{
             e.cancelBubble = true;
    }
    $(obj).parent("li").remove();  
}

