	function onMouseWheel(ev)
	{
		var oEvent=ev||event;
		var bDown=true;
		var bb=true;
		var a=document.getElementById("a");	
		
		
		bDown=oEvent.wheelDelta?oEvent.wheelDelta<0:oEvent.detail>0;
		
		if(bDown)
		{
			document.documentElement.scrollTop+=100;
			document.body.scrollTop+=100;
			if(document.body.scrollTop + document.documentElement.scrollTop >= 500){
				$("#module-menu").addClass("fided");
			}
		}
		else
		{
			document.documentElement.scrollTop-=100;
			document.body.scrollTop-=100;
			if(document.body.scrollTop + document.documentElement.scrollTop <500){
				$("#module-menu").removeClass("fided");
			}		
		}
		 	
		if(oEvent.preventDefault&&bb)
		{
			oEvent.preventDefault();
		}
		
		return false;
}
// myAddEvent(document, 'mousewheel', onMouseWheel);
// myAddEvent(document, 'DOMMouseScroll', onMouseWheel);
function myAddEvent(obj, sEvent, fn)
{
	if(obj.attachEvent)
	{
		obj.attachEvent('on'+sEvent, fn);
	}
	else
	{
		obj.addEventListener(sEvent, fn, false);
	}
}
window.onscroll=function(){
	if(document.body.scrollTop + document.documentElement.scrollTop >= 500){
				$("#module-menu").addClass("fided");
			};
	if(document.body.scrollTop + document.documentElement.scrollTop <500){
		$("#module-menu").removeClass("fided");
	}
}
function clickmenuli(){
		if(document.body.scrollTop + document.documentElement.scrollTop >= 500){
				$("#module-menu").addClass("fided");
			};
	if(document.body.scrollTop + document.documentElement.scrollTop <500){
		$("#module-menu").removeClass("fided");
	}
		console.log(document.body.scrollTop);		
		console.log(document.documentElement.scrollTop);
}