if (/Android (\d+\.\d+)/.test(navigator.userAgent)) {
	var version = parseFloat(RegExp.$1);
	if (version > 2.3) {
		var phoneScale = parseInt(window.screen.width) / 750;
		document.write('<meta name="viewport" content="width=750, minimum-scale = ' + phoneScale + ', maximum-scale = ' + phoneScale + ', target-densitydpi=device-dpi">');
	} else {
		document.write('<meta name="viewport" content="width=750, target-densitydpi=device-dpi">');
	}
}else if(navigator.userAgent.indexOf("iPad")>-1){
var phoneScale = parseInt(window.screen.width) / 750;
	document.write('<meta content="width=device-width,initial-scale=1.0,maximum-scale=0.77,user-scalable=no" id="viewport" name="viewport">');
} else {
	document.write('<meta name="viewport" content="width=750, user-scalable=no, target-densitydpi=device-dpi">');
}
//按下背景色
$(function(){
	$(document).delegate('.Click_bj','touchstart',function(){
		$(this).addClass('brc');
	});
	$(document).delegate('.Click_bj','touchend',function(){
		$(this).removeClass('brc');
	});
})

