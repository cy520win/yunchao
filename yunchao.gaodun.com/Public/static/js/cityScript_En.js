var citySelector = {};
var default_hotcity = ["北京", "上海", "天津", "重庆"];
citySelector.pc = new Array();
citySelector.pc[0]=new Array("Beijing","Beijing");
citySelector.pc[1]=new Array("Tianjin","Tianjin");
citySelector.pc[2]=new Array("Hebei","Shijiazhuang|Tangshan|Qinhuangdao|Handan|Xingtai|Baoding|Zhangjiakou|Chengde|Cangzhou|Langfang|Hengshui");
citySelector.pc[3]=new Array("Shanxi","Taiyuan|Datong|Yangquan|Changzhi|Jincheng|Shuozhou|Jinzhong|Yuncheng|Xinzhou|Linfen|Luliang");
citySelector.pc[4]=new Array("Inner Mongolia","Hohhot|Baotou|Wuhai|Chifeng|Tongliao|Ordos|Hulunbeier|Bayannao'er|Wulanchabu|Xing'anmeng|Xilin Gol|Alashan");
citySelector.pc[5]=new Array("Liaoning","Shenyang|Dalian|Anshan|Fushun|Benxi|Dandong|Jinzhou|Yingkou|Fuxin|Liaoyang|Panjin|Tieling|Chaoyang|Huludao");
citySelector.pc[6]=new Array("Jilin","Changchun|Jilin|Siping|Liaoyuan|Tonghua|Baishan|Songyuan|Baicheng|Yanbian");
citySelector.pc[7]=new Array("Heilongjiang","Harbin|Qiqihar|Jixi|Hegang|Shuangyashan|Daqing|Yichun|Jiamusi|Qitaihe|Mudanjiang|Heihe|Suihua|Daxing'anling");
citySelector.pc[8]=new Array("Shanghai","Shanghai");
citySelector.pc[9]=new Array("Jiangsu","Nanjing|Wuxi|Xuzhou|Changzhou|Suzhou|Nantong|Lianyungang|Huaian|Yancheng|Yangzhou|Zhenjiang|Taizhou|Suqian");
citySelector.pc[10]=new Array("Zhejiang","Hangzhou|Ningbo|Wenzhou|Jiaxing|Huzhou|Shaoxing|Jinhua|Quzhou|Zhoushan|Taizhou|Lishui");
citySelector.pc[11]=new Array("Anhui","Hefei|Wuhu|Bengbu|Huainan|Ma'anshan|Huaibei|Tongling|Anqing|Huangshan|Chuzhou|Fuyang|Suzhou|Lu'an|Bozhou|Chizhou|Xuancheng");
citySelector.pc[12]=new Array("Fujian","Fuzhou|Xiamen|Putian|Sanming|Quanzhou|Zhangzhou|Nanping|Longyan|Ningde");
citySelector.pc[13]=new Array("Jiangxi","Nanchang|Jingdezhen|Pingxiang|Jiujiang|Xinyu|Yingtan|Ganzhou|Ji'an|Yichun|Fuzhou|Shangrao");
citySelector.pc[14]=new Array("Shandong","Jinan|Qingdao|Zibo|Zaozhuang|Dongying|Yantai|Weifang|Jining|Tai'an|Weihai|Rizhao|Laiwu|Linyi|Dezhou|Liaocheng|Binzhou|Heze");
citySelector.pc[15]=new Array("Henan","Zhengzhou|Kaifeng|Luoyang|Pingdingshan|Anyang|Hebi|Xinxiang|Jiaozuo|Puyang|Xuchang|Luohe|Sanmenxia|Nanyang|Shangqiu|Xinyang|Zhoukou|Zhumadian");
citySelector.pc[16]=new Array("Hubei","Wuhan|Huangshi|Shiyan|Yichang|Xiangyang|Ezhou|Jingmen|Xiaogan|Jingzhou|Huanggang|Xianning|Suizhou|Enshi|Crown county");
citySelector.pc[17]=new Array("Hunan","Changsha|Zhuzhou|Xiangtan|Hengyang|Shaoyang|Yueyang|Changde|zhangjiajie|Yiyang|Chenzhou|Yongzhou|Huaihua|Loudi|Xiangxi");
citySelector.pc[18]=new Array("Guangdong","Guangzhou|Shaoguan|Shenzhen|Zhuhai|Shantou|Foshan|Jiangmen|Zhanjiang|Maoming|Zhaoqing|Huizhou|Meizhou|Shanwei|Heyuan|Yangjiang|Qingyuan|Dongguan|Zhongshan|Chaozhou|Jieyang|Yunfu");
citySelector.pc[19]=new Array("Guangxi","Nanning|Liuzhou|Quilin|Wuzhou|Beihai|Fangchenggang|Qinzhou|Guigang|Yulin|Baise|Hezhou|Hechi|Laibin|Chongzuo");
citySelector.pc[20]=new Array("Hainan","Haikou|Sanya|Sansha|Crown county");
citySelector.pc[21]=new Array("Chongqing","Chongqing|County jurisdiction");
citySelector.pc[22]=new Array("Sichuan","Chengdu|Zigong|Panzhihua|Luzhou|Deyang|Mianyang|Guangyuan|Suining|Neijiang|Leshan|Nanchong|Meishan|Yibin|Guang'an|Dazhou|Ya'an|Bazhong|Ziyang|Aba|Ganzi|Liangshan");
citySelector.pc[23]=new Array("Guizhou","Guiyang|Liupanshui|Zunyi|Anshun|Bijie|Tongren|QianXiNa|Qiandongnan|Qiannan");
citySelector.pc[24]=new Array("Yunnan","Kunming|Qujing|Yuxi|Baoshan|Zhaotong|Lijiang|Pu'er|Lincang|Chuxiong|Honghe|Wenshan|Xishuangbanna|Dali |Dehong |Nujiang|Diqing");
citySelector.pc[25]=new Array("Tibet","Lhasa|Changdu|Shannan|Xigaze|Naqu|Ali|Linzhi|Multi-Region");
citySelector.pc[26]=new Array("Shaanxi","Xi 'an|Tongchuan|Baoji|Xianyang|Weinan|Yanan|Hanzhong|Yulin|Ankang|Shangluo");
citySelector.pc[27]=new Array("Gansu","Lanzhou|Jiayuguan|Jinchang|Baiyin|Tianshui|Wuwei|Zhangye|Pingliang|Jiuquan|Qingyang|Dingxi|Longnan|Linxia|Gannan");
citySelector.pc[28]=new Array("Qinghai","Xining|Haidong|Haibei|Huangnan|HaiNa|Guoluo|Yushu|Haixi");
citySelector.pc[29]=new Array("Ningxia","Yinchuan|Shizuishan|Wuzhong|Guyuan|Zhongwei");
citySelector.pc[30]=new Array("Xinjiang","Urumqi|Kelamayi|Turpan|Hami|Changji|Boertala|Bayinguoleng|Aksu|Kyzyl|Kashi|Hetian|Lli|Tacheng|Altay");
citySelector.pc[31]=new Array("Other","Taiwan|HongKong|Macau|Abroad");

citySelector.cityInit = function (input,local,bool) {
        var city_bool=true;
        if(local){
            citySelector.hotCity = local;
        }else{
            citySelector.hotCity = default_hotcity;
        }
        $("#" + input).click(function (event) {
            event.stopPropagation();
            //点×删除
            // $("#" + input).find("ul").find("li").find("span").click(function(event){
            //     event.stopPropagation();
            //     $(this).parent("li").remove();                  
            // });
            
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
                event.stopPropagation();
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
                        e.stopPropagation();
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
                            $("#" + _input).find("ul").append('<li>'+ val + ' <span onclick=\"delSelCity(this)\">×</span><input type=\"hidden\" name=\"city_text[]\" value='+ val +'></li>');

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

function delSelCity(obj){
    event.stopPropagation();
    $(obj).parent("li").remove();  
}

