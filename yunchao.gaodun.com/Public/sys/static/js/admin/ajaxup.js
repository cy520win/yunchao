// 管理员上传头像
function upimageman(){
	$.ajaxFileUpload({
	url:"admin.php?s=/Admin/AdminUser/uphead",//处理图片脚本
	secureuri :false,
	type: 'post',
	fileElementId :'field-4',//file控件id
	dataType : 'json',
	success : function (data,status){
		if(typeof(data.error) != 'undefined'){
			if(data.error != ''){
				alert(data.error);
			}else{

				$('.headpic').attr('src',data.msg);
				$('.heapcic-2').val(data.msg);
			}
		}

	},			
	error: function(data, status, e){
		// alert(e);
	}
})
return false;
}

// 上传学员头像
function upimageuser(){
	$.ajaxFileUpload({
	url:"admin.php?s=/Admin/Student/uphead",//处理图片脚本
	secureuri :false,
	type: 'post',
	fileElementId :'field-4',//file控件id
	dataType : 'json',
	success : function (data,status){
		if(typeof(data.error) != 'undefined'){
			if(data.error != ''){
				alert(data.error);
			}else{

				$('.headpic').attr('src',data.msg);
				$('.heapcic-2').val(data.msg);
			}
		}

	},			
	error: function(data, status, e){
		// alert(e);
	}
})
return false;
}

// 上传企业logo
function upimagebus(){
	$.ajaxFileUpload({
	url:"admin.php?s=/Admin/Enterprise/uphead",//处理图片脚本
	secureuri :false,
	type: 'post',
	fileElementId :'field-4',//file控件id
	dataType : 'json',
	success : function (data,status){
		if(typeof(data.error) != 'undefined'){
			if(data.error != ''){
				alert(data.error);
			}else{

				$('.headpic').attr('src',data.msg);
				$('.heapcic-2').val(data.msg);
			}
		}
	},			
	error: function(data, status, e){
		// alert(e);
	}
})
return false;
}

// 上传广告
function upimageadver(){
	$.ajaxFileUpload({
	url:"admin.php?s=/Admin/Adver/uphead",//处理图片脚本
	secureuri :false,
	type: 'post',
	fileElementId :'field-4',//file控件id
	dataType : 'json',
	success : function (data,status){
		if(typeof(data.error) != 'undefined'){
			if(data.error != ''){
				alert(data.error);
			}else{				
				$('.headpic').attr('src',data.msg);
				$('.heapcic-2').val(data.msg);
			}
		}
	},			
	error: function(data, status, e){
		//alert(e);
	}
})
return false;
}

// 上传广告
function upimagesnsforum(){
	$.ajaxFileUpload({
	url:"admin.php?s=/Admin/SnsForum/uphead",//处理图片脚本
	secureuri :false,
	type: 'post',
	fileElementId :'field-4',//file控件id
	dataType : 'json',
	success : function (data,status){
		if(typeof(data.error) != 'undefined'){
			if(data.error != ''){
				alert(data.error);
			}else{				
				$('.headpic').attr('src',data.msg);
				$('.heapcic-2').val(data.msg);
			}
		}
	},			
	error: function(data, status, e){
		//alert(e);
	}
})
return false;
}

// 上传广告
function upimageicon(){
	$.ajaxFileUpload({
	url:"admin.php?s=/Admin/SnsForum/upicon",//处理图片脚本
	secureuri :false,
	type: 'post',
	fileElementId :'field-6',//file控件id
	dataType : 'json',
	success : function (data,status){
		if(typeof(data.error) != 'undefined'){
			if(data.error != ''){
				alert(data.error);
			}else{				
				$('.headpic3').attr('src',data.msg);
				$('.heapcic-3').val(data.msg);
			}
		}
	},			
	error: function(data, status, e){
		//alert(e);
	}
})
return false;
}

// 上传广告
function upimagesnstopic(){
	$.ajaxFileUpload({
	url:"admin.php?s=/Admin/SnsTopic/uphead",//处理图片脚本
	secureuri :false,
	type: 'post',
	fileElementId :'field-4',//file控件id
	dataType : 'json',
	success : function (data,status){
		if(typeof(data.error) != 'undefined'){
			if(data.error != ''){
				alert(data.error);
			}else{				
				$('.headpic').attr('src',data.msg);
				$('.heapcic-2').val(data.msg);
			}
		}
	},			
	error: function(data, status, e){
		//alert(e);
	}
})
return false;
}

// 上传广告
function upimagesnstopicnew(){
	$.ajaxFileUpload({
	url:"admin.php?s=/Admin/SnsTopic/upheadnew",//处理图片脚本
	secureuri :false,
	type: 'post',
	fileElementId :'field-5',//file控件id
	dataType : 'json',
	success : function (data,status){	

		if(typeof(data.error) != 'undefined'){
			if(data.error != ''){
				alert(data.error);
			}else{					
				$('.headpic_new').attr('src',data.msg);
				$('.heapcic-2_new').val(data.msg);
			}
		}
	},			
	error: function(data, status, e){
		//alert(e);
	}
})
return false;
}

// 上传banner
function upimagerecommend(){
	$.ajaxFileUpload({
	url:"admin.php?s=/Admin/RecommendActivity/uploadBanner",//处理图片脚本
	secureuri :false,
	type: 'post',
	fileElementId :'field-4',//file控件id
	dataType : 'json',
	success : function (data,status){	 

		if(typeof(data.error) != 'undefined'){
			if(data.error != ''){
				alert(data.error);
			}else{					
				$('.headpic').attr('src',data.msg);
				$('.heapcic-2').val(data.msg);
			}
		}
	},			
	error: function(data, status, e){
		// alert(e);
	}
})
return false;
}