//百度地图API功能
var sMap = new BMap.Map("smallmap");
sMap.enableScrollWheelZoom(true);

var address_shi = $('#smallmap').attr('data-home');
var address_info = $('#smallmap').attr('data-city');

// 创建地址解析器实例
var sMyGeo = new BMap.Geocoder();
// 将地址解析结果显示在地图上,并调整地图视野
sMyGeo.getPoint(address_info, function(sPoint){
    if(sPoint){
      sMap.centerAndZoom(sPoint, 16);
      sMap.addOverlay(new BMap.Marker(sPoint));
    }
}, address_shi);

/*弹窗大地图*/
var map = new BMap.Map("allmap");
map.addControl(new BMap.NavigationControl());
map.addControl(new BMap.MapTypeControl());
map.addControl(new BMap.OverviewMapControl());
map.enableScrollWheelZoom(true);
// 创建地址解析器实例
var gc = new BMap.Geocoder();
$(function(){
  $('#seacher-whole-map').bind('click',function(){
    map.clearOverlays();//清空原来的标注
    var address = address_info;
    var city = address_shi;
    var mark = $('#smallmap').attr('data-mark');
      map.setCurrentCity(city);
      map.setZoom(12);
      gc.getPoint(address, function(point){
          if(point){
            var p = new BMap.Point(point.lng, point.lat);
            var marker = new BMap.Marker(p);  // 创建标注
            map.addOverlay(marker);              // 将标注添加到地图中
            setTimeout(function(){
              map.centerAndZoom(p, 15);
            },200);
            map.setZoom(14);
            var sContent =
              "<div><h4 style='margin:0 0 5px 0;padding:0.2em 0;text-align:center'>"+city+"</h4>" + 
              "<p style='margin:0;line-height:1.5;font-size:13px;text-indent:2em'>"+address+"</p>" + 
              "<div style=\"text-align: center;\"><a href=http://map.baidu.com/?newmap=1&ie=utf-8&s=s%26wd%3D"+mark+" target=\"_blank\" style=\"color:#57d5e4;text-decoration:underline;\">查看公交/地铁</a></div>";
             var infoWindow = new BMap.InfoWindow(sContent);  // 创建信息窗口对象
            //图片加载完毕重绘infowindow
            marker.addEventListener("click", function () { this.openInfoWindow(infoWindow); });
            marker.openInfoWindow(infoWindow);
            marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
          }
      }, city);
  });
});