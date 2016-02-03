var add_chat = true;
var add_honor = true;
$(function(){
	$('#sun-43').on('click',function(){
		if($(this).val()){
			$('.certificate-option').removeClass('input-border-error')
		}else{
			$('.certificate-option').addClass('input-border-error')
		}
	})	

	$('.expect-cateory-add').on('click',function(){
		if($(this).val()){
			$('.expect-cateory-add').removeClass('input-border-error')
		}else{
			$('.expect-cateory-add').addClass('input-border-error')
		}
	})	

	$('.salary-range-add').on('click',function(){
		if($(this).val()){
			$('.salary-range-add').removeClass('input-border-error')
		}else{
			$('.salary-range-add').addClass('input-border-error')
		}
	})

	$('.week-available-add').on('click',function(){
		if($(this).val()){
			$('.week-available-add').removeClass('input-border-error')
		}else{
			$('.week-available-add').addClass('input-border-error')
		}
	})	
	$('.edu-add-greee').on('click',function(){
		if($(this).val()){
			$('.edu-add-greee').removeClass('input-border-error')
		}else{
			$('.edu-add-greee').addClass('input-border-error')
		}
	})

})

//初始化执行
moduleTpl();//小球导航
ball3();//小球导航2
initgender();//初始化性别 身份icon
areaStyle();//改变textarea样式
optionStyle();//初始化option样式
intStyle();//初花话input textareta option样式
aHref();//锚点样式
uploadHead('upload');
//保存基本信息
function savebase(obj){
	$.post('/Student/update_base',{'user':$('#edit-base-form').serializeArray()},function(msg){
		if(msg.code==1){
			$('#user-name').next('.error-info').text(msg.name);
			$('#user-graduate-school').next('.error-info').text(msg.graduate_school);
			$('.user-major-type').next('.error-info').text(msg.major_type);
			$('.user-education').next('.error-info').text(msg.education);
			$('.user-current-grade').next('.error-info').text(msg.graduate_year);
			$('#user-detail-major').next('.error-info').text(msg.detail_major);
			$('#user-mobile').next('.error-info').text(msg.mobile);
			$('#user-birth').next('.error-info').text(msg.birth_date);
			$('#contact_email').next('.error-info').text(msg.contact_email);
			$('#id_number').next('.error-info').text(msg.id_number);
			$('#homecity10').parent().next('.error-info').text(msg.living_city);
		}
		if(msg.code==2 || msg.code==3){
			if(msg.code==2){
				$('.basic_information').html(msg.data);	
				$('#userinfo-form').html('');
				$('.personal_message_column').addClass('dn');
				$('.basic_information').removeClass('dn');
				// $('.basic_information').addClass('pt13')
				$('.edit-base-span').removeClass('dn');
				slide($('.practice-wish'),'d');
				$('#practice-wish-div').removeClass('dn');
				$('.md-overlay').removeClass('apperance');
				intTag();
				intStyle();
			}
			return;
		}
	},'json')
	return false;
}

//加载基本信息模板
function editbase(){
	$.post("/Student/base_info",{'type':'edit'},function(data){
		if(data.status==true){
			$('.basic_information').addClass('dn')
			$('#userinfo-form').html(data.data);	
			$('.personal_message_column').removeClass('dn');
			$('.edit-base-span').addClass('dn');

	$('#sun-1').on("click", function(){ 
		if($(this).val()){
			$(this).next('.error-info').text('')
		}else{
			$(this).next('.error-info').text('必填')
		}
	});

	$('#sun-2').on("click", function(){ 
		if($(this).val()){
			$(this).next('.error-info').text('')
		}else{
			$(this).next('.error-info').text('必填')
		}
	});

	$('#sun-3').on("click", function(){ 
		if($(this).val()){
			$(this).next('.error-info').text('')
		}else{
			$(this).next('.error-info').text('必填')
		}
	});

			initgender();
			optionStyle();
			intTag2();
			intStyle();
			laydate.skin('molv');
			uploadHead('upload');
			hotcity("homecity10",true);
		}
	},'json')
}
function caclebase(obj){
    $('.personal_message_column').addClass('dn');
	caclebaseOut();
	$('.edit-base-span').removeClass('dn');
	return false;
}
function caclebaseOut(){
	$('#userinfo-form').empty();
	$('.basic_information').removeClass('dn');
	intTag();
}

function cancle(obj){
	var a = $(obj).parents('.input_column');
	$(obj).parents('.module-block').find('.add-chat').removeClass('dn')
	$(a).prev().removeClass('dn');
	$('.md-overlay').removeClass('apperance');
	$(a).remove();
	ball3();
}

function moduleTpl(){
	var a = $('.module-block').length;
	var b = $('.right-menu-nav>li').length;
	var c = $('.right-menu-li').length;
	$('.right-menu-nav>li').each(function(){
		if($(this).attr('data')==1){
			$(this).addClass('dn')
		}else{
			$(this).removeClass('dn')
		}		
	})
}

function ball3(){
	var len=$('.right-menu-nav>li').length;
	var len2=$(".right-menu-nav>li[class='dn']").length;
	if($('.right-menu-nav>li').length==$(".right-menu-nav>li[class='dn']").length){
		$('#dragger-menu').addClass('dn');
	}else{
		if($(".right-menu-nav>li[class='dn']").length>0){
			$('#dragger-menu').removeClass('dn');			
		}
	}
}

//点击性别图标
function getgender(obj,id){
	$('#edit-gender').val(parseInt(id));
	$('.sex-choose').removeClass('span-checked');
	$(obj).addClass('span-checked');
}
//点击身份图标
function getpolitics(obj,id){
	var sex=$('#edit-politics-status').val(parseInt(id));
	$('.Party-choosen').removeClass('span-checked');
	$(obj).addClass('span-checked');	
}

//更新实习意向
function saveexpect2(obj){
    if($('#demo4').val()){
        if($('#demo3').val() == ''){
            $('.date_tip').addClass('error-info').text('请选择“开始日期”');
            return false;
        }
    }
	$.post("/Student/edit_expect",{'data':$('#expect-form-edit').serializeArray()},function(msg){
		if(msg.code==1){
			redborder($('.city-text2'),msg.city_text);
			redborder($('.week-available2'),msg.week_available);
			redborder($('.salary-range2'),msg.salary_range);
			redborder($('.expect-cateory2'),msg.expect_cateory);
			redborder($('.period-start2'),msg.period_start);
			redborder($('.period-finish2'),msg.period_finish);
		}
		if(msg.code==2){
			$('#input-information-expect').html(msg.data);
			cancle2();
			return;
		}
		if(msg.code==3){
			return;
		}
	},'json')
	return false;
}
//取消实习意向编辑
function cancle2(obj){
	$('#expect-div').find('.practise_wish_column').remove();
	$('#expect-div').find('.add-chat').removeClass('dn');
	$('#expect-div').find('.edit_icon').removeClass('dn');
	intTag();
	return false;
}

//编辑实习意向
function editexpect(obj){
	$.post('/Student/expect_tpl','',function(data){
		if(data.status){
			$('#expect-div').append(data.data);
			$('#expect-div').find('.practise_wish_column').removeClass('dn');
			optionStyle();
			hotcity("inputTest2");
			intStyle();
		}
	},'json')
}
//新增实习意向
function saveexpect(obj){
    if($('#demo2').val()){
        if($('#demo').val() == ''){
            $('.date_tip').addClass('error-info').text('请选择“开始日期”');
            return false;
        }
    }
	$.post("/Student/add_expect",{'data':$('#expect-form-add').serializeArray()},function(msg){
		if(msg.code==1){
			redborder($('.city-text'),msg.city_text);
			redborder($('.week-available'),msg.week_available);
			redborder($('.salary-range'),msg.salary_range);
			redborder($('.expect-cateory'),msg.expect_category);
			redborder($('.period-start'),msg.period_start);
			redborder($('.period-finish'),msg.period_finish);
		}
		if(msg.code==2){
			window.location.reload();
			return;
		}
		if(msg.code==3){
			return;
		}
	},'json')
	return false;
}


function areaStyle(){
	$('.experience-distance ul li').hover(function(){
		$(this).find('textarea').css('background','#eafcfc');
		$(this).find('textarea').css('cursor','pointer');
		$(this).find('textarea').css('border','1px solid #eafcfc')
	},function(){
		$(this).find('textarea').css('background','#ffffff');
		$(this).find('textarea').css('cursor','default');
		$(this).find('textarea').css('border','1px solid #ffffff')
	})
}

//更新校内职务
function saveschool2(obj){
	$.post("/Student/edit_school",{'data':$('.form-position-edit').serializeArray()},function(msg){
		if(msg.code==1){
			redborder($('.school-start2'),msg.start_time);
			redborder($('.school-finish2'),msg.finish_time);
			redborder($('.school-job2'),msg.job_title);
			redborder($('.school-description2'),msg.description);
		}
		if(msg.code==2){
			$('#input-information-position').html(msg.data);
			$(obj).parents('.input_column').addClass('dn');
			$(obj).parents('#school-div').find('.add-chat').removeClass('dn');
			areaStyle();
			intStyle();
			intTag();
			textarea();
			return;
		}
		if(msg.code==3){
			return;
		}
	},'json')
	return false;
}

//编辑校内职务
function editschoolli(obj){
	$.post("/Student/schoolTpl",{'id':$(obj).attr('data-id')},function(msg){      
    		$(obj).addClass("dn");
	        $(obj).after(msg.data);
	        $(".md-overlay").addClass("apperance");
	        $(".dragger-module-menu").addClass("dn");
	        optionStyle();
	        intStyle();
    },'json')
}

//新增校内职务
function saveschool(obj){
	$.post("/Student/add_school",{'data':$('.form-position-add').serializeArray()},function(msg){
		if(msg.code==1){
			redborder($('.school-start'),msg.start_time);
			redborder($('.school-finish'),msg.finish_time);
			redborder($('.school-job'),msg.job_title);
			redborder($('.school-description'),msg.description);
		}
		if(msg.code==2){
			$('#input-information-position').html(msg.data);
			$(obj).parents('.input_column').addClass('dn');
			$(obj).parents('#school-div').find('.add-chat').removeClass('dn');
			areaStyle();
			rate();
			intTag();
			textarea();
			return;
		}
		if(msg.code==3){
			return;
		}
	},'json')
	return false;
}

//更新证书
function savecert2(obj){
	$.post("/Student/edit_cert",{'data':$('.form-certification-edit').serializeArray()},function(msg){
		if(msg.code==1){
			redborder($('.cert-finish-time2'),msg.finish_time);
			redborder($('.certificate-option2'),msg.certificate_id?msg.certificate_id:msg.certificate_name);
			redborder($('.cert-status2'),msg.status);
			if(msg.cert_bool){
				$('.cert-repeat2').text(msg.cert_bool);
				$('.cert-repeat2').removeClass('dn');
			}
		}
		if(msg.code==2){
			$('#input-information-certification').html(msg.data);
			$(obj).parents('.input_column').addClass('dn');
			$(obj).parents('#card-div').find('.add-chat').removeClass('dn');
			intTag();
			return;
		}
		if(msg.code==3){
			return;
		}
	},'json')
	return false;
}

//编辑证书
function editcertli(obj){
	$.post("/Student/certificateTpl",{'id':$(obj).attr('data-id')},function(msg){      
    		$(obj).addClass("dn");
	        $(obj).after(msg.data);
	        $(".md-overlay").addClass("apperance");
	        $(".dragger-module-menu").addClass("dn");

	        $('#sun-3-2').on("click",function(){
	        	$('.certificate-option2').removeClass('input-border-error')
	        })
	        optionStyle();
	        intStyle();
    },'json')
}

//新增证书
function savecert(obj){
	$.post("/Student/add_cert",{'data':$('.form-certification-add').serializeArray()},function(msg){
		if(msg.code==1){
			redborder($('.cert-finish-time'),msg.finish_time);
			redborder($('.certificate-option'),msg.certificate_id?msg.certificate_id:msg.certificate_name);
			redborder($('.cert-status'),msg.status);
			if(msg.cert_bool){
				$('.cert-repeat').text(msg.cert_bool);
				$('.cert-repeat').removeClass('dn');
			}
		}
		if(msg.code==2){
			$('#input-information-certification').html(msg.data);
			$(obj).parents('.input_column').addClass('dn');
			$(obj).parents('#card-div').find('.add-chat').removeClass('dn');
			rate();
			intTag();
			return;
		}
		if(msg.code==3){
			return;
		}
	},'json')
	return false;
}

//获取证书
function getcert(obj){
	if($(obj).val()==''){
		$('#cert-name-other').css('display','none');
		$('#cert-name-other').prop('disabled','disabled');
		$('#sun-43').html("<option value=''>证书名称</option>");
		optionStyle();
		intStyle();
		return;
	}
	if($(obj).val()==0){
		$('.certificate-option').css('display','none');
		$('#cert-name-other').css('display','inline-block');
		$('#cert-name-other').removeAttr('disabled');
		return;
	}else{
		$('#cert-name-other').css('display','none');
		$('#cert-name-other').prop('disabled','disabled');
		$.post("/Student/cert_list",{'id':$(obj).val()},function(data){
			$(obj).parent().next().find('select').html(data.data);
			optionStyle();
			intStyle();
			return;
		},'json')		
	}
}

function getcertEdit(obj){
	if($(obj).val()==0){
		$('.certificate-option2').css('display','none');
		$('#cert-name-other-edit').css('display','inline-block');
		$('#cert-name-other-edit').val('')
		$('#cert-name-other-edit').removeAttr('disabled');
	}else{
		$('#cert-name-other-edit').css('display','none');
		$('#cert-name-other-edit').prop('disabled','disabled');
		$.post("/Student/cert_list",{'id':$(obj).val()},function(data){
			$(obj).parent().next().find('select').html(data.data);
			optionStyle();
			intStyle();
		},'json')		
	}
}

//编辑课外活动
function saveactivity2(obj){
	$.post("/Student/edit_activity",{'data':$('.form-activity-edit').serializeArray()},function(msg){
		if(msg.code==1){
			redborder($('.activity-period2'),msg.period);
			redborder($('.activity-period3'),msg.period_end);
			redborder($('.activity-description2'),msg.description);
		}
		if(msg.code==2){
			$('#input-information-activity').html(msg.data);
			$(obj).parents('.input_column').addClass('dn');
			$(obj).parents('#activity-div').find('.add-chat').removeClass('dn');
			rate();
			intTag();
			return;
		}
		if(msg.code==3){
			return;
		}
	},'json')
	return false;
}

//编辑实习经历
function editactivityli(obj){
	$.post("/Student/activityTpl",{'id':$(obj).attr('data-id')},function(msg){      
    		$(obj).addClass("dn");
	        $(obj).after(msg.data);
	        $(".md-overlay").addClass("apperance");
	        $(".dragger-module-menu").addClass("dn");
	        intStyle();
    },'json')
}

//新增课外活动
function saveactivity(obj){
	$.post("/Student/add_activity",{'data':$('.form-activity-add').serializeArray()},function(msg){
		if(msg.code==1){
			redborder($('.activity-period'),msg.period);
			redborder($('.activity-period2'),msg.period_end);
			redborder($('.activity-description'),msg.description);
		}
		if(msg.code==2){
			$('#input-information-activity').html(msg.data);
			$(obj).parents('.input_column').addClass('dn');
			$(obj).parents('#activity-div').find('.add-chat').removeClass('dn');
			intTag();
			rate();
			return;
		}
		if(msg.code==3){
			return;
		}
	},'json')
	return false;
}

//编辑获奖荣誉
function saveprize2(obj){
	$.post("/Student/edit_prize",{'data':$('.form-honor-edit').serializeArray()},function(msg){
		if(msg.code==1){
			redborder($('.prize-period'),msg.period);
			redborder($('.prize-description'),msg.description);
		}
		if(msg.code==2){
			$('#input-information-honor').html(msg.data);
			$(obj).parents('.input_column').addClass('dn');
			$(obj).parents('#awards-div').find('.add-chat').removeClass('dn');
			intTag();
			return;
		}
		if(msg.code==3){
			return;
		}
	},'json')
	return false;
}

//编辑实习经历
function editprizeli(obj){
	$.post("/Student/prizeTpl",{'id':$(obj).attr('data-id')},function(msg){      
    		$(obj).addClass("dn");
	        $(obj).after(msg.data);
	        $(".md-overlay").addClass("apperance");
	        $(".dragger-module-menu").addClass("dn");
	        intStyle();
    },'json')
}

//新增获奖荣誉
function saveprize(obj){
	$.post("/Student/add_prize",{'data':$('.form-honor-add').serializeArray()},function(msg){
		if(msg.code==1){
			redborder($('.prize-period'),msg.period);
			redborder($('.prize-description'),msg.description);
		}
		if(msg.code==2){
			$('#input-information-honor').html(msg.data);
			$(obj).parents('.input_column').addClass('dn');
			$(obj).parents('#awards-div').find('.add-chat').removeClass('dn');
			rate();
			intTag();
			return;
		}
		if(msg.code==3){
			return;
		}
	},'json')
	return false;
}

//更新实习经历
function savepriactice2(obj){
	$.post("/Student/edit_pri",{'data':$('#form-exprience-edit').serializeArray()},function(msg){
		if(msg.code==1){
			redborder($('.priactice-start-time2'),msg.start_time);
			redborder($('.priactice-finish-time2'),msg.finish_time);
			redborder($('.priactice-organization2'),msg.organization);
			redborder($('.priactice-quarters2'),msg.quarters);
			redborder($('.priactice-content2'),msg.content);
		}
		if(msg.code==2){
			$('#input-information-experience').html(msg.data);
			$(obj).parents('.input_column').addClass('dn');
			$(obj).parents('#exprience-div').find('.add-chat').removeClass('dn');
			intTag();
			areaStyle();textarea();
			return;
		}
		if(msg.code==3){
			return;
		}
	},'json')
	return false;
}

//编辑实习经历
function editprili(obj){
	$.post("/Student/practiceTpl",{'id':$(obj).attr('data-id')},function(msg){      
    		$(obj).addClass("dn");
	        $(obj).after(msg.data);
	        $(".md-overlay").addClass("apperance");
	        $(".dragger-module-menu").addClass("dn");
	        intStyle();
    },'json')
}

//添加实习经历
function savepriactice(obj){
	$.post("/Student/add_pri",{'data':$('.add-priactice-form').serializeArray()},function(msg){
		if(msg.code==1){
			redborder($('.priactice-start-time'),msg.start_time);
			redborder($('.priactice-finish-time'),msg.finish_time);
			redborder($('.priactice-organization'),msg.organization);
			redborder($('.priactice-quarters'),msg.quarters);
			redborder($('.priactice-content'),msg.content);
		}
		if(msg.code==2){
			$('#input-information-experience').html(msg.data);
			$(obj).parents('.input_column').addClass('dn');
			$(obj).parents('#exprience-div').find('.add-chat').removeClass('dn');
			areaStyle();
			rate();
			intTag();
			textarea();
			return;
		}
		if(msg.code==3){
			return;
		}
	},'json')
	return false;
}

//新增教育背景
function saveedu2(obj){
	$.post("/Student/add_edu",{'edu':$('#form-education-add').serializeArray()},function(msg){
		if(msg.code==1){
			redborder($('.edu-add-schoolname'),msg.school_name);
			redborder($('.edu-add-majortitle'),msg.major_title);
			redborder($('.edu-add-greee'),msg.degree);
			redborder($('.edu-add-starttime'),msg.start_time);
			redborder($('.edu-add-finishtime'),msg.finish_time);
		}
		if(msg.code==2){
			$('#input-information-education').html(msg.data);
			$('#input-education').addClass('dn');
			$(obj).parents('#education-div').find('.add-chat').removeClass('dn');
			rate();
			intTag();
			return;
		}
		if(msg.code==3){
			return;
		}
	},'json')
	return false;
}


//编辑教育背景
function editeduli(obj){
	$.post("/Student/educationTpl",{'id':$(obj).attr('data-id')},function(msg){      
    		$(obj).addClass("dn");
	        $(obj).after(msg.data);
	        $(".md-overlay").addClass("apperance");
	        $(".dragger-module-menu").addClass("dn");
	        optionStyle();
	        intStyle();
    },'json')
}

//编辑保存教育背景
function saveedu(obj){
	$.post("/Student/update_edu",{'edu':$('#edit-education-from').serializeArray()},function(msg){
		if(msg.code==1){
			redborder($('#edu-edit-schoolname'),msg.school_name);
			redborder($('#edu-edit-majortitle'),msg.major_title);
			redborder($('.edu-edit-greee'),msg.degree);
			redborder($('#edu-edit-starttime'),msg.start_time);
			redborder($('#edu-edit-finishtime'),msg.finish_time);
		}
		if(msg.code==2){
			$('#input-information-education').html(msg.data);
			$('.md-overlay').removeClass('apperance')
			intTag();
			return;
		}
		if(msg.code==3){
			return;
		}
	},'json')
	return false;
}


//新增自我介绍
function saveintroduce(obj){
	$.post('/Student/add_introduce',{'data':$('#form-introduce').serializeArray()},function(msg){
		if(!msg.status){
			redborder($('#introduce-2'),msg.msg);
		}
		if(msg.status){
			$('#input-introduce').addClass('dn');
			$('#input-information-introduce').find('.p-textarea').html(msg.data)
			$('#input-information-introduce').removeClass('dn');
			$('.introduce-edit-i').removeClass('dn');
			ball3();
			rate();
		}
	},'json')
	return false;
}

//编辑自我介绍
function editintroduce(obj){
	$('#input-information-introduce').addClass('dn');
	$.post('/Student/get_introduceTpl','',function(msg){
		$('#introduce-2').val(msg.data);
	})
}
//添加红色边框
function redborder(obj,str){
	if(str){
		$(obj).addClass('input-border-error');
	}
}

//判断小球的显示与隐藏
function ball2(id){
	show($('#dragger-menu'),id);
}

//判断小球菜单的显示与隐藏
function ball(index,id){
	show($('.right-menu-nav>li').eq(index),id);
}
//判断右侧简历菜单的显示与隐藏
function menu(index,id){
	show($('.right-menu>li').eq(index+1),id);
}
//判定整个模板显示与隐藏
function module(index,id){
		show($('.module-block').eq(index),id);
		show($('.module-block').eq(index).find('.input_column'),id)		
}

function moduleFix(index,id,d){
	show($('.module-block').eq(index),id);
	setTimeout(function(){
		$('.certification-honor').eq(d).addClass('isvisible')
		if(d==0){
			add_chat = false;
		}
		if(d==1){
			add_honor = false;
		}
	},500)
}
//过度效果
function slide(obj,id){
	if(id=='d'){
		$(obj).slideDown(1000);//效果展示
	}
	if(id=='p' || id=='u'){
		$(obj).slideUp(1000);//效果隐藏
	}
}
//没有过度效果
function show(obj,id){
	if(id=='d'){
		$(obj).removeClass('dn');//块展示
	}
	if(id=='n' || id=='u'){
		$(obj).addClass('dn');//隐藏
	}
	if(id=='i'){
		$(obj).css('display','inline');//行展示
	}
}

//x删除按钮
function delmenu(obj,e){
		var li = $(obj).parents('li');
		var index = $(obj).parents('li').index()-1;
		suremenudel(li,index);
		var ev= e || window.event;
		if(ev.stopPropagation){
	        ev.stopPropagation();
	    }else{
	        ev.cancelBubble=true;     
	    }
}

//确定要删除整个模块
function surebtndel(obj,index){
	$.post("/Student/delete_module",{'id':index},function(msg){
			// ball(index,'d');
			// menu(index,'n');
			// module(index,'u');
			// ball2('d');
			// canclebtn();			
			// return;
			window.location.reload();
	},'json')

}

//删除层弹出层效果
function suremenudel(obj,index){
	canclebtn();
	var str = "<div class=\"del-li-div\" onclick=\"delidive(this,event)\"><div></div><p>确认要删除本模块吗？</p><div id=\"del-li-div\"><span class=\"btn btn-sure delete-button\" onclick=\"surebtndel(this,"+index+")\">确&nbsp;定</span><span class=\"btn btn-cancle information-cancel-button\" onclick=\"canclebtn()\">取&nbsp;消</span></div></div>";
	$(obj).append(str);
}

function suremenudel2(obj,mid,id){
	var str = "<div class=\"notice_board\" id=\"notice\"><span>确认删除本条信息？</span><div class=\"button\"><button onclick=\"return makedel("+mid+","+id+")\" class=\"btn button_style delete delete-button\">删除</button><button class=\"btn button_style information-cancel-button\" onclick=\"makeclose3()\">取消</button></div></div>";
	$(obj).parent().append(str);
}
//删除单条信息
function makedel(mid,id){
	if(mid==1){
		$.post("/Student/education_del",{'id':id},function(msg){
			makeclose();
			$('#edu-edit-form').prev().remove();
			$('#edu-edit-form').remove();
			if($('#input-information-education').find('ul>li').length==0){
				$('.module-block').eq(0).addClass('dn');
				    menu(mid-1,'n');
				    ball2('d')
					ball(mid-1,'d');
			}
		},'json')		
	}

	if(mid==2){
		$.post("/Student/prize_del",{'id':id},function(msg){
			makeclose();
			$('.input-honor-edit').prev().remove();
			$('.input-honor-edit').remove();
			var school_acticity_len = $('.cert-prize-ul>ul>li').length;
			if(school_acticity_len==0){
				// $('.module-block').eq(3).addClass('dn');
					menu(mid,'n');
					ball2('d')
					ball(mid,'d');
			}
		},'json')		
	}
	if(mid==3){
		$.post("/Student/practice_del",{'id':id},function(msg){
				makeclose();
				$('.edit-priactice-input').prev().remove();
				$('.edit-priactice-input').remove();
				if($('#input-information-experience').find('ul>li').length==0){
					$('.module-block').eq(1).addClass('dn');
					menu(mid-2,'n');
					ball2('d')
					ball(mid-2,'d');	
				};
			},'json')
	}

	if(mid==4){
		$.post("/Student/activity_del",{'id':id},function(msg){
			makeclose();
			$('.input-activity-edit').prev().remove();
			$('.input-activity-edit').remove();
			var school_acticity_len = $('.school-acticity-ul>ul>li').length;
				if(school_acticity_len==0){
					// $('.module-block').eq(2).addClass('dn');
					menu(mid,'n');
					ball2('d')
					ball(mid,'d');
				};
		},'json')
	}

	if(mid==5){
		$.post("/Student/cert_del",{'id':id},function(msg){
			makeclose();
			$('.input-cert-input').prev().remove();
			$('.input-cert-input').remove();
			var school_acticity_len = $('.cert-prize-ul>ul>li').length;
				if(school_acticity_len==0){
					// $('.module-block').eq(3).addClass('dn');
					menu(mid,'n');
					ball2('d')
					ball(mid,'d');
				};
		},'json')
	}

	if(mid==6){
		$.post("/Student/school_del",{'id':id},function(msg){
			makeclose();
			$('.input-school-edit').prev().remove();
			$('.input-school-edit').remove();
			var school_acticity_len = $('.school-acticity-ul>ul>li').length;
				if(school_acticity_len==0){
					// $('.module-block').eq(2).addClass('dn');
					menu(mid,'n');
					ball2('d')
					ball(mid,'d');
				};
		},'json')
	}
	return false;
}

function makeclose(){
	$('#notice').remove();
	$('.md-overlay2').removeClass('apperance');
	$('.md-overlay').removeClass('apperance');
	ball3();
	rate();
}

function makeclose2(){
	$('#notice').remove();
	$('.md-overlay2').removeClass('apperance');
	$('.md-overlay').removeClass('apperance');	
}

function makeclose3(){
	$('#notice').remove();
}

//删除该条教育背景
function deledu(obj,id){
	suremenudel2(obj,1,id);
}

//删除该条获奖经历
function delprize(obj,id){
	suremenudel2(obj,2,id);
}

//删除该条实习经历
function delpri(obj,id){
	suremenudel2(obj,3,id);
}

//删除该条课外活动
function delactivity(obj,id){
	suremenudel2(obj,4,id);
}

//删除证书
function delcert(obj,id){
	suremenudel2(obj,5,id);
}

//删除校内职务
function delschool(obj,id){
	suremenudel2(obj,6,id);	
}

//消除删除层
function canclebtn(){
	$('.del-li-div').remove();
}

function add_chat(classname){
     $(classname).addClass("dn");
     $(".add-chat").removeClass("dn");
     $(".md-overlay").removeClass("apperance");   
}

function optionStyle(){
	$("#sun-3,#sun-1,#sun-2,#sun-4,#sun-43,#sun-3-2,#mobile_type").select2({
		placeholder: '',
		allowClear: true,
		minimumResultsForSearch: -1, // Hide the search bar
		formatResult: function(state)
		{
			return '<div style="display:inline-block;position:relative;width:20px;height:15px;margin-right: 0px;top:2px;"></div>' 
					+ state.text;
		}
	}).on('select2-open', function()
	{
		$(this).data('select2').results.perfectScrollbar();
	});
}

function intTag(){
	$('.md-overlay').removeClass('apperance');
	ball3();
}

function intTag2(){
	$('.md-overlay').addClass('apperance');
	ball3();	
}

function datePlug(id){
	!function(){
		laydate.skin('molv');
		laydate({elem: id,format: 'YYYY-MM'});
	}();
}

function intStyle(){
	$('input[type=text]').focusin(function(){
    	$(this).next('span').html('');
    })

    $('input[type=text]').focusout(function(){
    	$(this).next('span').html('');
    	$(this).removeClass('input-border-error');
    })

    $('textarea').focusout(function(){
    	$(this).removeClass('input-border-error');
    })

    $('textarea').focusin(function(){
    	$(this).removeClass('input-border-error');
    })

    $('#inputTest2').click(function(){
    	$(this).removeClass('input-border-error');
    })

    $('#inputTest20').click(function(){
    	$(this).removeClass('input-border-error');
    })

    $('#inputTest').click(function(){
    	$(this).removeClass('input-border-error');
    })

    $('#module-list .module-block').removeClass('pdb15');
    $('#module-list .module-block').not('.dn').last().addClass('pdb15');
}

function delidive(obj,e){
	var ev=e||event;
	ev.stopPropagation();
	return false;
}


function aHref(id){
    if(!id){
		var url = window.location.toString();
	    var id = url.split("#")[1];
	    var mid = '#'+id;
    }
    if(id){
    	var mid = '#'+id;
    }
    $('.right-menu-li>a').removeClass('font-active');
    $('.right-menu-li>a>i').each(function(){
    	$(this).removeClass($(this).attr('icon')+'-active');
    })
    $('.right-menu-li>a[href='+mid+']').addClass('font-active');
    var icon = $('.right-menu-li>a[href='+mid+']').find('i').attr('icon');
    $('.right-menu-li>a[href='+mid+']').find('i').addClass(icon+'-active');
}

$(function(){
		//右侧模块导航 hover
		$('.right-menu>li').hover(function(){
			show($(this).find('.del-right-menu'),'i')
			aHref();
			$(this).children('a').addClass('font-active');
			$(this).find('a>i').addClass($(this).find('a>i').attr('icon')+'-active')
		},function(){
			$(this).find('.del-right-menu').css('display','none');
			$(this).find('a>i').removeClass($(this).find('a>i').attr('icon')+'-active');
			aHref();
		})
		//小球模块导航
		$('.right-menu-nav>li').click(function(event){
			    	if(event.stopPropagation){
			event.stopPropagation();
		}else{
			//ie8以下
			 event.cancelBubble = true;
		}
			clickmenuli();	
			var index = $(this).index();
			var model = $(this).attr('module');
			var i=0;
			show($(this),'n')
			$('.right-menu-nav>li').each(function(){
				if($(this).css('display')=='none'){
					++i;
					if(i==$('.right-menu-nav>li').length){
						ball2('n')
					}
				}
			})
			if(model=='cert_prize'){
				moduleFix(index,'d',1);
				menu(index,'d');
			}else if(model=='school_acticity'){
				moduleFix(index,'d',0);
				menu(index,'d');
			}else{
				module(index,'d');
				menu(index,'d');
				$('.add-chat').eq(index).addClass('dn');				
			}
			if(index==0){
				aHref('education-div');
			}
			if(index==1){
				aHref('exprience-div');
			}
			if(index==2){
				aHref('activity-div');
			}
			if(index==3){
				aHref('card-div');
			}
			if(index==4){
				aHref('introduce-div');
				$('.introduce-edit-i').addClass('dn');
			}

			$('#module-list .module-block').removeClass('pdb15');
			$('#module-list .module-block').not('.dn').last().addClass('pdb15');
	
		})

	//新增取消按钮
     $(".information-cancel-button").click(function(){

     	$(this).parents(".module-block").find('.normal-input').val('');
	    $(this).parents(".module-block").find('.normal-input').addClass('default-input-border');
	    $(this).parents(".module-block").find('select').eq(0).find('option').eq(0).attr('selected','selected');
	    $(this).parents(".module-block").find('select').eq(1).find('option').eq(0).attr('selected','selected');
	    var opt = $(this).parents(".module-block").find('select').eq(0).find('option').eq(0).text();
	    $(this).parents(".module-block").find('select').eq(0).prev().find('.select2-chosen').text(opt);

     	if($(this).attr('data-cancle')=='introduce'){
     		if($('.p-introduc').text()){
     			$('#input-information-introduce').removeClass('dn');
     			$('#input-introduce').addClass('dn');
     			$('.introduce-edit-i').removeClass('dn');
     			ball3();
     		}else{
     			$('#introduce-div').addClass('dn');
     			menu($(this).parents('.module-block').index(),'n');
	         	ball($(this).parents('.module-block').index(),'d');
	         	ball3();
     		}
     	}else if($(this).attr('data-cancle')=='activity'){
     		var school_acticity_len = $('.school-acticity-ul>ul>li').length;
     		if(school_acticity_len<1){
     			$('#input-activity').addClass('dn');
     			// menu($(this).parents('.module-block').index(),'n');
	       		// ball($(this).parents('.module-block').index(),'d');
	       		// ball3();
     		}else{
     			$('#input-activity').addClass('dn');
     		}
     	}else if($(this).attr('data-cancle')=='school'){
     		var school_acticity_len = $('.school-acticity-ul>ul>li').length;
     		if(school_acticity_len<1){
     			$('#input-school-position').addClass('dn');
     			// menu($(this).parents('.module-block').index(),'n');
	       		// ball($(this).parents('.module-block').index(),'d');
	       		// ball3();
     		}else{
     			$('#input-school-position').addClass('dn');
     		}
     	}else if($(this).attr('data-cancle')=='cert'){
     		var school_acticity_len = $('.cert-prize-ul>ul>li').length;
     		if(school_acticity_len<1){
     			$('#input-certification').addClass('dn');
     			// menu($(this).parents('.module-block').index(),'n');
	       //   	ball($(this).parents('.module-block').index(),'d');
	       //   	ball3();
     		}else{
     			$('#input-certification').addClass('dn');
     		}
     	}else if($(this).attr('data-cancle')=='honor'){
       		var school_acticity_len = $('.cert-prize-ul>ul>li').length;
     		if(school_acticity_len<1){
     			$('#input-honor').addClass('dn');
     			// menu($(this).parents('.module-block').index(),'n');
	       //   	ball($(this).parents('.module-block').index(),'d');
	       //   	ball3();
     		}else{
     			$('#input-honor').addClass('dn');
     		}   		
     	}else{
	         $(this).parents(".input_column").addClass("dn");
	         $(".add-chat").removeClass("dn");
	         $(".dragger-module-menu").removeClass("dn");
	         $(".md-overlay").removeClass("apperance");
	         var li = $(this).parents(".input_column").next().find('ul>li').length;
	         if(li==0){
	         	$(this).parents('.module-block').addClass('dn');
	         	menu($(this).parents('.module-block').index(),'n');
	         	ball($(this).parents('.module-block').index(),'d');
	         }
	         ball3();
	         return false;     		
	     }
    });

     //圆球菜单
    var menu_button_a=true;
    var menu_buttonHH=$('body').height();
    $("#menu-button").click(function(){
    		if(menu_button_a){
    			$('body').css('min-height','900px');  
    			menu_button_a= false;			
    		}else{
    			$('body').css('min-height',menu_buttonHH);
    			menu_button_a=true;
    		}
           $(".dragger-module-content").toggleClass("dn");
           $(this).toggleClass("open_menu");
    });

    //添加按钮
	$(".add-chat a").click(function(){
	  	$(this).parents(".model-title").next().removeClass('dn');
	    $(".dragger-module-menu").addClass("dn");
	    $(this).parents("span").addClass("dn");
	    $(".md-overlay").addClass("apperance");
	    $(this).parents(".model-title").next().find('.normal-input').val('');
	    $(this).parents(".model-title").next().find('.normal-input').addClass('default-input-border');
	    $(this).parents(".model-title").next().find('select').eq(0).find('option').eq(0).attr('selected','selected');
	    $(this).parents(".model-title").next().find('select').eq(1).find('option').eq(0).attr('selected','selected');
	    var opt = $(this).parents(".model-title").next().find('select').eq(0).find('option').eq(0).text();
	    $(this).parents(".model-title").next().find('select').eq(0).prev().find('.select2-chosen').text(opt);
	    // optionStyle();
	    return false;
	});

	$('.add-chat-school').click(function(event){
        if(event.stopPropagation){
			event.stopPropagation();
		}else{
			//ie8以下
			 event.cancelBubble = true;
		}
		if(add_chat){
			$(this).children("ul").addClass("isvisible");
			add_chat = false; 
		}else{
			$(this).children("ul").removeClass("isvisible");
			add_chat = true;
		}	
	})

	$('.add-chat-honor').click(function(event){
            	if(event.stopPropagation){
			event.stopPropagation();
		}else{
			//ie8以下
			 event.cancelBubble = true;
		}
		if(add_honor){
			$(this).children("ul").addClass("isvisible");
			add_honor = false; 
		}else{
			$(this).children("ul").removeClass("isvisible");
			add_honor = true;
		}	
	})


	//body全局点击事件
	$(document).click(function(event){
		$('.del-li-div').remove();
        $('.certification-honor').removeClass("isvisible");
        $(".down-billow-icon").removeClass("change");
          add_chat = true;
          add_honor = true;
	})

	laydate.skin('molv');
});

function charlen(obj,e){
	var e = e || event;
	if(e.which==8){
		return true;
	}else{
		if(JHshStrLen($(obj).val())>=64){
			$(obj).addClass('input-border-error');
			return false;
		}else{
			$(obj).removeClass('input-border-error');
		}		
	}
}

function charlen2(obj,len,e){
	var e = e || event;
	if(e.which==8){
		return true;
	}else{
		if(JHshStrLen($(obj).val())>=len*2){
			$(obj).next('span').html($(obj).attr('placeholder'));
			return false;
		}else{
			$(obj).next('span').html('');
		}		
	}		
}

function charlen3(obj,len,e){
	var e = e || event;
	if(e.which==8){
		return true;
	}else{
		if(JHshStrLen($(obj).val())>=len*2){
			$(obj).addClass('input-border-error');
			return false;
		}else{
			$(obj).removeClass('input-border-error');
		}		
	}		
}
//字数限制
function JHshStrLen(sString){  
	var sStr,iCount,i,strTemp ;  
	iCount = 0 ;  
	sStr = sString.split("");  
	for (i = 0 ; i < sStr.length ; i ++)  {  
		strTemp = escape(sStr[i]);  
		if (strTemp.indexOf("%u",0) == -1) // 表示是汉字  
		{  
			iCount = iCount + 1 ;  
		}else  {  
			iCount = iCount + 2 ;  
		}  
	}  
	return iCount ;  
} 

//rate()
var data='';
function rate(){
	var data=''
    $.get('/Student/resume_rate',function(data){
    	if(data){
    		$('.complete-circle').html(data);
    	}
    },'json')
}

//日历挂件
function getdate(id,e){
    var e = e || event
	if(e.stopPropagation){
		e.stopPropagation();
	}else{
		e.cancelBubble = true;//ie8以下
	}
    laydate({elem:'#'+id,format:'YYYY-MM-DD'});
}


function getlaydate(id){
	laydate({elem:'#'+id,format:'YYYY-MM-DD'});
}

//实习阶段专用日期使用
function getnewdate(id){
    laydate({
        elem:'#'+id,
        format:'YYYY-MM-DD',
        choose:function(dates){
            if(id == 'demo'){
                if($('#demo2').val() == ''){
                    $('.date_tip').text('不填截止日期，则表示“开始日期”之后的工作日都可以实习');
                }else{
                    $('.date_tip').text('');
                }
            }
            if(id == 'demo2'){
                if($('#demo').val() == ''){
                    $('.date_tip').addClass('error-info').text('请选择“开始日期”');
                }else{
                    $('.date_tip').text('');
                }
            }

            if(id == 'demo3'){
                if($('#demo4').val() == ''){
                    $('.date_tip').text('不填截止日期，则表示“开始日期”之后的工作日都可以实习');
                }else{
                    $('.date_tip').text('');
                }
            }
            if(id == 'demo4'){
                if($('#demo3').val() == ''){
                    $('.date_tip').addClass('error-info').text('请选择“开始日期”');
                }else{
                    $('.date_tip').text('');
                }
            }
        }
    });
}


function uploadHead(id){
	if($('#'+id).length<1){
		return false;
	}
    new AjaxUpload(id,{
        action : '/Upload/upload',
        name : 'headpic',
        autoSubmit: true,
        responseType: 'json',
        onComplete : function(file, response) {
            if(response.status == 'success'){
                $('#headpic_img').attr('src', response.path);
                $('.img-container img').attr('src', response.path);
                $(".photomodify").show();
                $(".md-overlay2").addClass("apperance");
                crop();
            }else{
                alert(response.msg);
            }

        }
    }); 

//切图
function crop(){
        var preview_size = [114, 154],
            aspect_ratio = preview_size[0] / preview_size[1],

            $image = $(".img-container img"),
            $x = $("#img-1-x"),
            $y = $("#img-1-y"),
            $w = $("#img-1-w"),
            $h = $("#img-1-h");

        // Plugin Initialization
        $image.cropper({
            aspectRatio: aspect_ratio,
            preview: '#img-preview',
            dragCrop: false,
            minWidth:435,  //需要除以2.9进行处理
            minHeight:435,
            done: function(data)
            {
                $x.text( data.x );
                $y.text( data.y );
                $w.text( data.width );
                $h.text( data.height );
            }
        });

        // Preview Image Setup (based on defined crop width and height
        $("#img-preview").css({
            width: preview_size[0],
            height: preview_size[1]
        });

        $("#crop-img").on('click', function(ev)
        {
            ev.preventDefault();
            var img_src = $('#headpic_img').attr('src');
            $.post('/Upload/crop',{'x': $x.text(),'y': $y.text(),'w': $w.text(),'h': $h.text(),'tw':
                preview_size[0],'th': preview_size[1],'src' : img_src,'model':'headpic'},function(data){
                $('input[name=img]').val(data.path);
                $('#upload').attr('src',  '/' + data.path);
                $('#logo').val('');
                $(".photomodify").html(resetHtml());
                $(".photomodify").hide();
                $(".md-overlay2").removeClass("apperance");
                $('#upload').next('.error-area').children('span').removeClass('error-info-span').html('&nbsp;');
            },'json')
        });

        $('#crop-close').on('click',function(ev){
            ev.preventDefault();
            close_crop()
        })
    }

    function resetHtml(){
        var html = '<h1><span>请选择裁剪区域</span></h1><div class="panel-body photo_form"><div class="img-container">';
        html += '<img id="headpic_img" class="img-responsive"/></div><div style="display: none">';
        html += '<div id="img-1-x"></div><div id="img-1-y"></div><div id="img-1-w"></div><div id="img-1-h"></div></div>';
        html += '<div class="photomodify_footer"><input type="hidden" name="img"><button id="crop-img" class="btn btn-info confirm-button button-small mt20">保 存</button> ' +
        '<button id="crop-close" class="btn confirm-button information-cancel-button button-small mt20">取 消</button></div></div></div>';
        return html;
    }

    function close_crop(){
        $(".photomodify").html(resetHtml());
        $(".photomodify").hide();
        $(".md-overlay2").removeClass("apperance");
    }

}
    
function schoolAdd(obj,id){
	var id = parseInt(id);
	if(id==1){
		$('#input-activity').removeClass('dn');
		$('#input-school-position').addClass('dn');
		$('#form-activity').find('.normal-input').val('');
		$('#form-activity').find('.normal-input').removeClass('input-border-error');
	}
	if(id==2){
		$('#input-school-position').removeClass('dn');
		$('#input-activity').addClass('dn');
		$('#form-school-position').find('.normal-input').val('');
		$('#form-school-position').find('.normal-input').removeClass('input-border-error');
	}
	if(id==3){
		$('#input-certification').removeClass('dn');
		$('#input-honor').addClass('dn');
		$('#form-certification').find('.normal-input').val('');
		$('#form-certification').find('.normal-input').removeClass('input-border-error');
		optionStyle()
	}
	if(id==4){
		$('#input-certification').addClass('dn');
		$('#input-honor').removeClass('dn');
		$('#form-honor').find('.normal-input').val('');	
		$('#form-honor').find('.normal-input').removeClass('input-border-error');
	}
}

var maxstrlen=300;
function Q(s){return document.getElementById(s);}
function checkWord(c){
    len=maxstrlen;
    var str = c.value;
    myLen=getStrleng(str);
    var wck=Q("wordCheck");
    if(myLen>len*2){
        c.value=str.substring(0,i+1);
    }
    else{
        wck.innerHTML = Math.floor((len*2-myLen)/2);
    }
}
function getStrleng(str){
    myLen =0;
    i=0;
    for(;(i<str.length)&&(myLen<=maxstrlen*2);i++){
        if(str.charCodeAt(i)>0&&str.charCodeAt(i)<128)
            myLen++;
        else
            myLen+=2;
    }
    return myLen;
}

function textarea(){}
function initgender(){}

function closeBack(){
    $('.box-t').hide();
}