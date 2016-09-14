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
    <title>手机登录</title>
    <link href="<?php echo (HOME_CSS_URL); ?>/myinfocss.css" rel="stylesheet" type="text/css">
    <style>
        .myInput::-webkit-input-placeholder{
            color: red;
        }
        .myInput:-moz-placeholder{
            color: red;
        }
        .myInput::-moz-placeholder{
            color: red;
        }
        .myInput:-ms-input-placeholder{
            color: red;
        }

    </style>
</head>
<body>
<form id="form" method="post" action="<?php echo U('Home/login');?>">
    <div class="return_index"><a href="#" class="return_link"></a><h1>手机登录</h1></div><div class="wrap cf" style="background:#fff">
    <div class="phone_check cf">
        <p class="phone_img"><img src="/project2/parttime/Public/home/img/phone_checkimg.png" alt=""></p>
        <div class="phone_test cf">
            <div class="phone">
                <input name="username" id="username" placeholder="请填写手机号" maxlength="11" value="<?php echo ($error["username"]); ?>"  type="tel">
            </div>
            <div class="phone">
                <input name="password" id="password" placeholder="请输入密码" maxlength="11" value="<?php echo ($error["password"]); ?>"  type="password">
            </div>
            <div class="test">
                <input id="captcha" name="captcha" maxlength="6" placeholder="填写验证码" value="<?php echo ($error["captcha"]); ?>" type="text">
                <img id="codeImg" src="<?php echo U('Home/verfiyImg');?>" width="188px" height="70px" onclick='this.src="<?php echo U('Home/verfiyImg');?>?" + Math.random()'>
            </div>
            <div  class="btn_submit">登录赚钱</div>
        </div>
    </div>
    <input type="hidden" name="referee" value="<?php echo ($referee); ?>">
</form>
<script>
    var url='<?php echo U("Home/ajaxLogin","",false);?>';
</script>
<script src="/project2/parttime/Public/home/js/regedit.js"></script>
</body>
</html>