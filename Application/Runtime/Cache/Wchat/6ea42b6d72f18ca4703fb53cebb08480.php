<?php if (!defined('THINK_PATH')) exit();?><html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="Pragma" content="no-cache">
    <meta content="telephone=no" name="format-detection">
    <meta content="email=no" name="format-detection">
    <link href="<?php echo (HOME_CSS_URL); ?>/basecss.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo (HOME_JS_URL); ?>/jquery.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo (HOME_JS_URL); ?>/shike.js"></script><meta name="viewport" content="width=750, user-scalable=no, target-densitydpi=device-dpi">
    <title>手机注册</title>
    <link href="<?php echo (HOME_CSS_URL); ?>/myinfocss.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form" method="post" action="<?php echo U('Home/regedit');?>">
<div class="return_index"><a href="#" class="return_link"></a><h1>手机注册</h1></div><div class="wrap cf" style="background:#fff">
    <div class="phone_check cf">
        <p class="phone_img"><img src="/project2/parttime/Public/home/img/phone_checkimg.png" alt=""></p>
        <div class="phone_test cf">
            <div class="phone">
                <input name="phone" id="phone" placeholder="请填写手机号" maxlength="11" value=""  type="tel">
            </div>
            <div class="phone">
                <input name="password" id="password" placeholder="请输入密码" maxlength="11" value=""  type="password">
            </div>
            <div class="phone">
                <input name="password2" id="password2" placeholder="请确认密码" maxlength="11" type="password">
            </div>
            <div class="test">
                <input id="captcha" name="captcha" maxlength="6" placeholder="填写验证码" type="text">
                <img id="codeImg" src="<?php echo U('Home/verfiyImg');?>" width="188px" height="70px" onclick='this.src="<?php echo U('Home/verfiyImg');?>?" + Math.random()'>
            </div>
            <div class="test">
                <input name="phoneCode" id="phoneCode" maxlength="6" placeholder="填写手机收到的验证码" type="tel">
                <div onclick="sendSMS();" id="send_btn2" class="sending Click_bj sbtn" >发送验证码</div>
            </div>
            <div  class="btn_submit">开始赚钱</div>
        </div>
    </div>
    <input type="hidden" name="referee" value="<?php echo ($referee); ?>">
</form>
<script>
    var url='<?php echo U("Home/ajaxRegedit","",false);?>';
    var state=1;
    var count=120;
    var tmp;
    function sendSMS() {
        if (state==1) {
            state=0;
            var mobile=$("#phone").val();
            var code=$("#captcha").val();
            $.get(
                    '<?php echo U("Home/sendIdentify","",false);?>',
                    {
                        'mobile':mobile,
                        'code':code
                    },
                    function (data) {
                        switch (data){
                            case "500":
                                $("#codeImg").attr("src","<?php echo U('Home/verfiyImg');?>?" + Math.random());
                                $("#captcha").val("验证码错误");
                                $("#captcha").css('color','red');
                                $("#captcha").on("focus",function () {
                                    $("#captcha").css('color','#a4a4a4');
                                    $("#captcha").val('');
                                });
                                state=1;
                                break;
                            case "501":
                                $("#phone").val("手机格式错误");
                                $("#codeImg").attr("src","<?php echo U('Home/verfiyImg');?>?" + Math.random());
                                $("#phone").css("color","red");
                                $("#phone").on("focus",function () {
                                    $("#phone").css('color','#a4a4a4');
                                    $("#phone").val('');
                                });
                                pError();
                                state=1;
                                break;
                            case "502":
                                $("#phone").val("该手机已被注册");
                                $("#phone").css("color","red");
                                $("#codeImg").attr("src","<?php echo U('Home/verfiyImg');?>?" + Math.random());
                                $("#phone").on("focus",function () {
                                    $("#phone").css('color','#a4a4a4');
                                    $("#phone").val('');
                                });
                                pError();
                                state=1;
                                break;
                            case "503":
                                $("#phone").val("手机号必须填写");
                                $("#phone").css("color","red");
                                $("#codeImg").attr("src","<?php echo U('Home/verfiyImg');?>?" + Math.random());
                                $("#phone").on("focus",function () {
                                    $("#phone").css('color','#a4a4a4');
                                    $("#phone").val('');
                                });
                                pError();
                                state=1;
                                break;
                            case "504":
                                alert("短信发送失败，请刷新页面重试");
                                state=1;
                                break;
                            case "100":
                                $(".sbtn").unbind("click");
                                tmp=setInterval("restrictSend()",1000);
                                break;
                            case "505":
                                alert("120秒以后重新发送");
                                state=1;
                                break;
                        }
                    }
            );
        }
    }

    function restrictSend() {
        $(".sbtn").text(count+"秒后重送");
        count--;
        if (count<0) {
            state=1;
            $(".sbtn").text("发送验证码");
            count=120;
            clearInterval(tmp);
            $(".sbtn").bind("click",function () {
                sendSMS();
            });
        }
    }

    $(".sbtn").bind("click",function () {
        sendSMS();
    });
    function pError() {
        $("#captcha").on("focus",function () {
            $("#captcha").css('color','#a4a4a4');
            $("#captcha").val('');
        });
    }
</script>
<script src="/project2/parttime/Public/home/js/regedit.js"></script>
</body>
</html>