//region_config.js
var appid = "wx6fbe0a100f94ef8c";
var openWxUrl="https://open.weixin.qq.com/connect/oauth2/authorize?appid="+appid;
var itryAddress="itry://";
var itrycom="i.appshike.com";
var shokey_param_key = 'itry.shokey.param';
var download_xb_by_safari =true;
var down_xb_url = "http://yysk-itry.58.com/s/ndown.html";
var down_xb_url_by_safari = "itms-services://?action=download-manifest&url=https://down.appshike.com/plist/";
var download_app_by_safari =true;
var down_app_url="http://yysk-itry.58.com/s/itunes.html?a=###";
var down_app_url_by_safari = "itms-apps://itunes.apple.com/WebObjects/MZStore.woa/wa/search";

var play_app_by_safari =true;
var play_480079300_url = "http://yysk-itry.58.com/s/58.html";
var play_480079300_url_by_safari = "http://yysk-itry.58.com/s/58.html";

window.appClassCache = {'旅游':'tourism_type','游戏':'game_m','财务':'financial_m','摄影与录像':'video_m','音乐':'music_m','天气':'weather_m','效率':'efficiency_m',
		'工具':'tool_m','社交':'social_m','导航':'navigation_m','教育':'education_m','商品指南':'Commodities_guide_m','美食':'food_m','医疗':'medical_m','图书':'book_m',
		'儿童':'children_m','生活':'life_m','健康':'health_m','新闻':'news_m','参考':'reference_m','体育':'sports_m','商务':'business_m','娱乐':'entertainment_m','其他':'other_m'};

//store.min.js
(function(e){function o(){try{return r in e&&e[r]}catch(t){return!1}}var t={},n=e.document,r="localStorage",i="script",s;t.disabled=!1,t.set=function(e,t){},t.get=function(e){},t.remove=function(e){},t.clear=function(){},t.transact=function(e,n,r){var i=t.get(e);r==null&&(r=n,n=null),typeof i=="undefined"&&(i=n||{}),r(i),t.set(e,i)},t.getAll=function(){},t.forEach=function(){},t.serialize=function(e){return JSON.stringify(e)},t.deserialize=function(e){if(typeof e!="string")return undefined;try{return JSON.parse(e)}catch(t){return e||undefined}};if(o())s=e[r],t.set=function(e,n){return n===undefined?t.remove(e):(s.setItem(e,t.serialize(n)),n)},t.get=function(e){return t.deserialize(s.getItem(e))},t.remove=function(e){s.removeItem(e)},t.clear=function(){s.clear()},t.getAll=function(){var e={};return t.forEach(function(t,n){e[t]=n}),e},t.forEach=function(e){for(var n=0;n<s.length;n++){var r=s.key(n);e(r,t.get(r))}};else if(n.documentElement.addBehavior){var u,a;try{a=new ActiveXObject("htmlfile"),a.open(),a.write("<"+i+">document.w=window</"+i+'><iframe src="/favicon.ico"></iframe>'),a.close(),u=a.w.frames[0].document,s=u.createElement("div")}catch(f){s=n.createElement("div"),u=n.body}function l(e){return function(){var n=Array.prototype.slice.call(arguments,0);n.unshift(s),u.appendChild(s),s.addBehavior("#default#userData"),s.load(r);var i=e.apply(t,n);return u.removeChild(s),i}}var c=new RegExp("[!\"#$%&'()*+,/\\\\:;<=>?@[\\]^`{|}~]","g");function h(e){return e.replace(/^d/,"___$&").replace(c,"___")}t.set=l(function(e,n,i){return n=h(n),i===undefined?t.remove(n):(e.setAttribute(n,t.serialize(i)),e.save(r),i)}),t.get=l(function(e,n){return n=h(n),t.deserialize(e.getAttribute(n))}),t.remove=l(function(e,t){t=h(t),e.removeAttribute(t),e.save(r)}),t.clear=l(function(e){var t=e.XMLDocument.documentElement.attributes;e.load(r);for(var n=0,i;i=t[n];n++)e.removeAttribute(i.name);e.save(r)}),t.getAll=function(e){var n={};return t.forEach(function(e,t){n[e]=t}),n},t.forEach=l(function(e,n){var r=e.XMLDocument.documentElement.attributes;for(var i=0,s;s=r[i];++i)n(s.name,t.deserialize(e.getAttribute(s.name)))})}try{var p="__storejs__";t.set(p,p),t.get(p)!=p&&(t.disabled=!0),t.remove(p)}catch(f){t.disabled=!0}t.enabled=!t.disabled,typeof module!="undefined"&&module.exports&&this.module!==module?module.exports=t:typeof define=="function"&&define.amd?define(t):e.store=t})(Function("return this")());

function check_store() {
    return store.enabled;
}

function getStore(key){
    if(check_store())
        return store.get(key);
    return null;
}

function setStore(key,val){
    if(check_store())
        store.set(key,val);
}

function removeStore(key){
    if(check_store())
        store.remove(key);
}
function clearStore(){
    if(check_store())
        store.clear();
}
//wx_utils.js
function open_xb(){
	if($('.offline_xiaobing').length>0){
		window.now=+new Date();
		$.post('/itry/checkVersion',{t:window.now},function(data){
			if(data == '1'){
				alert_Iknow('小兵有新版本，请重新下载并打开<br />记得在下载后删除历史版本哦~','下载小兵',function(){show_download_xb();});
			}else{
				window.now=+new Date();
				location.href = 'itry://card/show';
				window.setTimeout(function(){
					getXBStatus();
				},500);
			}
		})
	}
}

function alert_tips(msg,cancel_p,ok,ok_event){
    var div =
		"<div class='msg_t'>\
			<div class='tinymask'>\
			</div>\
			<div class='tinybox cf'>\
				<h4 class='notice'>温馨提示</h4>\
				<div class='ns-box-inner' style='text-align:center'>"+msg+"\
				</div>\
				<div class='ns_action'>\
					<input type='button' class='Click_bj cancel' value='"+cancel_p+"'>\
					<input type='button' class='brc169bf8 Click_bj colfff j_ok' value='"+ok+"'>\
				</div>\
			</div>\
        </div>";
    $(".wrap").append(div);
	$(".cancel").click(function(){
        $(".wrap .msg_t").remove();
    });
	ok_event && $('.j_ok').click(ok_event);
}

function alert_Iknow(msg,ok_inf,ok_event){
    var div =
		"<div class='msg_t'>\
			<div class='tinymask'>\
			</div>\
			<div class='tinybox cf'>\
				<h4 class='notice'>温馨提示</h4>\
				<div class='ns-box-inner' style='text-align:center'>"+msg+"\
				</div>\
				<div class='ns_action'>\
					<input type='button' class='Click_bj Iknow col3a90f4' value='"+ok_inf+"'>\
				</div>\
			</div>\
        </div>";
    $(".wrap").append(div);
	$(".Iknow").click(function(){
		$(".wrap .msg_t").remove();
		ok_event && ok_event();
    });
}

//微信的js sdk
var menu_type = 'showOptionMenu';
function bridgeCallShowMenu(){
    menu_type = 'showOptionMenu';
    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
            document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
        }
    }else{
        onBridgeReady();
    }
}

function bridgeCallHideMenu(){
    menu_type = 'hideOptionMenu';
    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
            document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
        }
    }else{
        onBridgeReady();
    }
}

function onBridgeReady(){
    WeixinJSBridge.call(menu_type);
}

var tryTimes=0;
var token;
var getXBStatus = function() {
	if($('.offline_xiaobing').length>0 && tryTimes++<5){
		window.setTimeout(function(){
    		$.getJSON('/itry/xbStatus',{token:token,t:Math.random()},function(isOnline){
				if(isOnline){
					$('.msg_xb_notopen').hide();
					$('.offline_xiaobing').removeClass('offline_xiaobing').addClass('online_xiaobing');
				}else{
					window.setTimeout(getXBStatus,tryTimes*1000);
				}
			});
    	},tryTimes*1000);
	}
};
function download_xb(oid_md5,is_safari){
	window.location.href = '/itry/download_xb';
}

function getFileSize(file_size_byte){
	return isNaN(file_size_byte)?'1':Math.ceil(file_size_byte/1024/1024);
}

//before_submit.js
/**
 * Created with IntelliJ IDEA.
 * User: gzy
 * Date: 13-12-4
 * Time: 下午4:01
 * To change this template use File | Settings | File Templates.
 */

/**
 * 判断是否为空
 * 空：返回true
 * @param input
 * @return {boolean}
 */
function isNull(input){
    var flag = false;
    if(input==undefined||input==null||input.length==0)
        flag = true;
    return flag;
}
/**
 * 判断是否为数字
 * 是数字或者空：返回true
 * @param input
 * @return {boolean}
 */
function isNumber(input){
    var flag = true;
    if(!isNull(input)){
        if(isNaN(input))
            flag = false;
    }
    return flag;
}
/**
 * 判断是否是整数
 * 空或者整数：true
 * @param input
 * @return {boolean}
 */
function isInteger(input){
    var flag = true;
    if(!isNull(input)){
        if(!/^-?\d+$/.test(input))
            flag = false;
    }
    return flag;
}
/**
 * 判断是否是浮点数
 * 是或者空：true
 * @param input
 * @return {boolean}
 */
function isFloat(input){
    var flag = true;
    if(!isNull(input)){
        if(!/^(-?\d+)(\.\d+)?$/.test(input))
            flag = false;
    }
    return flag;
}
/**
 * 判断是否是IP地址
 * 空或者是IP地址：true
 * @param input
 * @return {boolean}
 */
function isIP(input){
    var flag = true;
    if(!isNull(input)){
        if(!/^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/.test(input))
            flag = false;
    }
    return flag;
}
/**
 * 判断身份证号码
 * 空或者正确：true
 * @param idNumber
 * @return {boolean}
 */
function isIdCardNo(idNumber){
    var flag = true;
    if(!isNull(idNumber)){
        var factorArr = new Array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2,1);
        var varArray = new Array();
        var lngProduct = 0;
        var intCheckDigit;

        if ((idNumber.length != 15) && (idNumber.length != 18)){
            return false;
        }
        for(i=0;i<idNumber.length;i++){
            varArray[i] = idNumber.charAt(i);
            if ((varArray[i] < '0' || varArray[i] > '9') && (i != 17)){
                return false;
            }else if (i < 17){
                varArray[i] = varArray[i]*factorArr[i];
            }
        }
        if (idNumber.length == 18){
            var date8 = idNumber.substring(6,14);
            if (isDate2(date8) == false){
                return false;
            }
            for(i=0;i<17;i++){
                lngProduct = lngProduct + varArray[i];
            }
            intCheckDigit = 12 - lngProduct % 11;
            switch (intCheckDigit){
                case 10:
                    intCheckDigit = 'X';
                    break;
                case 11:
                    intCheckDigit = 0;
                    break;
                case 12:
                    intCheckDigit = 1;
                    break;
            }
            if (varArray[17].toUpperCase() != intCheckDigit){
                return false;
            }
        }else{
            var date6 = idNumber.substring(6,12);
            if (isDate2(date6) == false){
                flag = false;
            }
        }
    }
    return flag;
}
/**
 * 判断是否符合QQ号格式
 * 空或者QQ号：true
 * @param input
 * @return {boolean}
 */
function isQQ(input){
    var flag = true;
    if(!isNull(input)){
        if(!/^[1-9]\d{4,10}$/.test(input))
            flag = false;
    }
    return flag;
}
/**
 * 判断电话号码格式[101-****-*******]
 * 空或者符合格式：true
 * @param input
 * @return {boolean}
 */
function isPhone(input){
    var flag = true;
    if(!isNull(input)){
        if(!/^(0[1-9]\d{1,2}-)\d{7,8}(-\d{1,8})?/.test(input))
            flag = false;
    }
    return flag;
}
/**
 * 判断是否是手机号码
 * 空或者手机号码：true
 * @param input
 * @return {boolean}
 */
function isMobile(input){
    var flag = true;
    if(!isNull(input)){
        if(!/^(13\d{9})|(15\d{9})|(18\d{9})|(0\d{10,11})$/.test(input))
            flag = false;
    }
    return flag;
}
/**
 * 判断是否是邮编
 * 空或者邮编：true
 * @param input
 * @return {boolean}
 */
function isPost(input){
    var flag = true;
    if(!isNull(input)){
        if(!/^\d{1,6}$/.test(input))
            flag = false;
    }
    return flag;
}
/**
 * 判断字符串长度是否在length范围内
 * 是或者空：true
 * @param input
 * @param length
 * @return {boolean}
 */
function isInRange(input,length){
    var flag = true;
    if(!isNull(input)){
        if(input.length<=length)
        flag = false;
    }
    return flag;
}
/**
 * 根据type的形式判断日期
 * 空或者符合形式：true
 * @param input
 * @param type
 * @return {boolean}
 */
function isDate(input,type){
    var flag = true;
    if(!isNull(input)){
        var reg = /^((((1[6-9]|[2-9]\d)\d{2})-(0?[13578]|1[02])-(0?[1-9]|[12]\d|3[01]))|(((1[6-9]|[2-9]\d)\d{2})-(0?[13456789]|1[012])-(0?[1-9]|[12]\d|30))|(((1[6-9]|[2-9]\d)\d{2})-0?2-(0?[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))-0?2-29-))$/;
        if(!isNull(type)){
            if("YYYY/MM/DD"==type.toUpperCase())
                reg = /^((((1[6-9]|[2-9]\d)\d{2})\/(0?[13578]|1[02])\/(0?[1-9]|[12]\d|3[01]))|(((1[6-9]|[2-9]\d)\d{2})\/(0?[13456789]|1[012])-(0?[1-9]|[12]\d|30))|(((1[6-9]|[2-9]\d)\d{2})-0?2-(0?[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))-0?2-29-))$/;
            else if("YYYYMMDD"==type.toUpperCase())
                reg = /^((((1[6-9]|[2-9]\d)\d{2})(0?[13578]|1[02])(0?[1-9]|[12]\d|3[01]))|(((1[6-9]|[2-9]\d)\d{2})(0?[13456789]|1[012])-(0?[1-9]|[12]\d|30))|(((1[6-9]|[2-9]\d)\d{2})-0?2-(0?[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))-0?2-29-))$/;
        }
        if(!reg.test(input)){
            flag = false;
        }
    }
    return flag;
}

function isDate2(dateStr){
	var dateInfo = dateStr.match(/(\d{4})(\d{2})(\d{2})/);
	var tmpDate = new Date(dateInfo[1],dateInfo[2]-1,dateInfo[3]);
	return tmpDate.getFullYear()==dateInfo[1] && tmpDate.getMonth()+1==dateInfo[2] && tmpDate.getDate()==dateInfo[3];
}
/**
 * 判断是否是汉字
 * 空或者是汉字：true
 * @param input
 * @return {boolean}
 */
function isChinese(input){
    var flag = true;
    if(!isNull(input)){
        if (!(/^[\u4E00-\uFA29]*$/.test(input)&&(!/^[\uE7C7-\uE7F3]*$/.test(input))))
            flag = false;
    }
    return flag;
}
/**
 * 判断字符串含有 汉字和数字
 * @param input
 * @return {boolean}
 */
function isChineseOrNum(input){
    var flag = true;
    if(!isNull(input)){
        input = input.replace(/\d+/g,'');
        if (!(/^[\u4E00-\uFA29]*$/.test(input)&&(!/^[\uE7C7-\uE7F3]*$/.test(input))))
            flag = false;
    }
    return flag;
}
/**
 * 过滤掉字符串头和尾的空格,空了返回null
 * @param input
 * @return {*}
 */
function trimSpace(input){
    if(!isNull(input)){
        input.replace(/(^\s*)|(\s*$)/g, '');
    }
    return input;
}
/**
 * 过滤字符串左边空格
 * @param input
 * @return {*}
 */
function trimSpaceLeft(input){
    if(!isNull(input)){
        input.replace(/^\s*/g, '');
    }
    return input;
}
/**
 * 过滤字符串右边数据
 * @param input
 * @return {*}
 */
function trimSpaceRight(input){
    if(!isNull(input)){
        input.replace(/\s*$/, '');
    }
    return input;
}
/**
 * 判断是否是链接
 * 空或者链接，返回true
 * @param input
 * @return {boolean}
 */
function isUrl(input){
    var flag = true;
    if(!isNull(input)){
        var re = new RegExp("^((https|http|ftp|rtsp|mms)://)?[a-z0-9A-Z]{3}\.[a-z0-9A-Z][a-z0-9A-Z]{0,61}?[a-z0-9A-Z]\.com|net|cn|cc (:s[0-9]{1-4})?/$");
        if (!re.test(input))
            flag = false;
    }
    return flag;
}
/**
 * 判断是否是Email
 * 空或者是，返回true
 * @param input
 * @return {boolean}
 */
function isEmail(input) {
    var flag = true;
    if(!isNull(input)){
        input = input.substring(0,input.indexOf("@")).replace(".","").replace(".","")
            .replace(".","").replace(".","").replace(".","").replace(".","").replace(".","")
            +input.substring(input.indexOf("@"),input.length).replace("-","");

        var reg = /^\w{1,30}(?:@(?!-))(?:(?:[a-z0-9-]*(?:[a-z0-9](?!-))(?:\.(?!-))))+[a-z]{2,4}$/;
        if (!reg.test(input))
            flag = false;

    }
    return flag;
}

//这是有设定过期时间的使用示例：
//s20是代表20秒
//h是指小时，如12小时则是：h12
//d是天数，30天则：d30
//m是分钟
function setCookie(name,value,time){
    var strsec = getsec(time);
    var exp = new Date();
    exp.setTime(exp.getTime() + strsec*1);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
function getsec(str){
    var str1=str.substring(1,str.length)*1;
    var str2=str.substring(0,1);
    if (str2=="s"){
        return str1*1000;
    }else if (str2=="m"){
        return str1*60*1000;
    }else if (str2=="h"){
        return str1*60*60*1000;
    }else if (str2=="d"){
        return str1*24*60*60*1000;
    }
}
function getCookie(name){
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");

    if(arr=document.cookie.match(reg))
        return (arr[2]);
    else
        return null;
}
function delCookie(name){
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null)
        document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}

function error_warming(info){
    if(isNull(info))
        info = "网络错误 请重新打开";
    var error_info = '<div class="web_loading cf"><p>'+info+'</p><p><img src="/weixin/app/images/xiaobing_loading.jpg" alt=""/></p></div>';
    $(' .wrap').html(error_info);
}

//shared_js.js
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

///common.jsp
var nav4 =(function(){
	bindClick = function(els, mask){
		if(!els || !els.length){return;}
		var isMobile = "ontouchstart" in window;
		var eventName = isMobile?"touchstart":"click";
		for(var i=0,ci; ci = els[i]; i++){
			ci.addEventListener(eventName,evtFn, false);
			ci.num = i;
		}
		
		var closeAll = function(){
			$.each(els,function(){
				$(this).removeClass('on');
			});
			$(mask).removeClass('on');
		};
		
		mask.addEventListener(eventName, function(){
			closeAll();
			mask.last = null;
		}, false);
		
		function evtFn(evt){
			closeAll();
			if (mask.last && mask.last.num == this.num) {
				mask.last = null;
			} else {
				$(this).addClass('on');
				$(mask).addClass('on');
				mask.last = this;
			}
		}		
	}
	return {"bindClick":bindClick};
})();


/*function show_download_xb(){
	clearStyle();
	if(typeof(powerDownload) == 'undefined'){
		$.getJSON("/itry/log_download_xb",{oid_md5:''},function(back){
	        if(back.rtn>0){
	        	var d_url = down_xb_url_by_safari+back.binding+".plist";
	    		window.location.href= d_url;
	        }else{
	            alert("请重新打开");
	        }
	    },"json");
		download_xb("",""+Boolean(navigator.userAgent.match(/Safari/i)));
	}else{
		location.href = powerDownload;
	}
    if(Boolean(navigator.userAgent.match(/OS [0-8]_\d[_\d]* like Mac OS X/i))){
    }else{
        $(" .wrap").append('<div class="msg_seeios9" style="display:block">\
                <div class="tinymask"></div>\
                <div class="tinybox cf" style="top:30%;overflow:inherit">\
                    <div class="head_ios9"><img src="http://static.appshike.com/images/deckicon_ios9.png" alt=""></div>\
                    <span class="ns-close"></span>\
                    <div class="ns-box-inner mb30"><p class="clueinfo">请先安装试客小兵<br>刷新页面即可再次安装<br>试客小兵是企业级应用<br><font>“iOS 9”</font> 的小伙伴需设置后才能打开</p></div>\
                    <p class="protocols"><span>请阅读</span>《<a href="http://static.appshike.com/html/userAgreement.pdf" class="link">用户协议</a>》</p>\
                    <div class="ns_action"><a href="/html/help_ios9.jsp" type="button" style=" width:100%; border-radius:0px 0px 10px 10px" class="brc169bf8 Click_bj colfff pass_st">查看设置方法</a></div>\
                </div></div>');
        $(".ns-close").click(function(){$(".msg_seeios9").hide();})
    }
}*/

function clearStyle(){
	window.setTimeout(function(){
		$('.brc').removeClass('brc');
		$('.on').removeClass('on');	
	},100);
}

function setMenu(){
    if(window.location.href.indexOf("/html/aboutus.jsp")>-1){
        $(" .about_us").before('<a href="javascript:history.go(-1);" class="return_link" style="border-top:5px solid #fff; border-left:5px solid #fff; position:absolute;top:30px; left:30px; width:30px; height:30px;"></a>');
    }else{
        $(".wrap:not(.noNav)").before("<div class='return_index'><a href='javascript:history.go(-1);' class='return_link'></a><h1></h1></div>");
    }

    if(history.length==1){
        $('.return_index a').attr('href','http://i.appshike.com/html/menu.html');
    }
    if(document.title=='排行榜'){
        $(".ranking_content .time").attr({"top":"199px"});
    }
    $(".return_index h1").html(document.title);
    $('.wrap:not(.noMenu)').append('<div class="nav4">\
		        <nav>\
	            <div id="nav4_ul" class="nav_4">\
	                <ul class="box">\
	                	<li style="width:15%">\
                			<a href="http://i.appshike.com/html/menu.html" class="Click_bj"><span class="Home_page"></span></a>\
                		</li>\
	                    <li>\
	                        <a href="/shike/appList" class="Click_bj j_date"><span>开始赚钱</span></a>\
	                    </li>\
	                    <li>\
	                        <a href="javascript:void(0);" class="Click_bj"><em class="connav2"></em><span>我的信息</span></a>\
	                        <dl>\
	                            <dd><a href="/itry/personalcenter/toPersonalCenter" class="Click_bj j_date"><span>个人中心</span></a></dd>\
	                            <dd><a href="/itry/invite/toInviteFriendsOfXB?type=2" class="Click_bj"><span>邀请好友</span></a></dd>\
	                            <dd><a href="/itry/weixin/toRankingList" class="Click_bj"><span>排行榜</span></a></dd>\
	                            <dd><a href="/itry/income/toIncomeInfo" class="Click_bj j_date"><span style="border-bottom:0px;">收入查询</span></a></dd>\
	                        </dl>\
	                    </li>\
	                    <li>\
	                        <a href="javascript:void(0);" class="Click_bj"><em class="connav2"></em><span>更多</span></a>\
	                        <dl>\
	                            <dd><a href="/html/aboutus.jsp" class="Click_bj"><span>关于我们</span></a></dd>\
	                            <dd><a href="javascript:download_xb();" class="Click_bj"><span>下载小兵</span></a></dd>\
	                            <dd><a href="/html/notescontact.jsp" class="Click_bj"><span style="border-bottom:0px;">常见问题</span></a></dd>\
	                        </dl>\
	                    </li>\
	                </ul>\
	            </div>\
	        </nav>\
	        <div id="nav4_masklayer" class="masklayer_div on">&nbsp;</div>\
	    </div>');

    try{nav4.bindClick(document.getElementById("nav4_ul").querySelectorAll("li>a"), document.getElementById("nav4_masklayer"));}catch(e){}
    $("#nav4_ul dl>dd").on('touchend',function(){
    	$("#nav4_ul ul>li>a").removeClass("on");
    });
    
    $('.j_date').each(function(){var tmphref=$(this).attr('href');$(this).attr('href',tmphref+'?t='+new Date().getTime());});
    $('#nav4_ul dd a').on('touchstart',function(){
		document.getElementById("nav4_masklayer").last = null;
		var target_href = $(this).attr('href');
		window.setTimeout(function(){
			document.location = target_href;
		},300);
		return false;
    });
    $(window).bind("pageshow",window.onpagehide = clearStyle);
}

function safariopen_tips(){
    var div =
    	"<div class='safariopen'>\
        	<div class='safari_img'><img src='http://static.appshike.com/images/details_safari2.png' alt=''></div>\
        </div>";
        $("body").append(div);
        document.ontouchmove=function(){return false;}
}
