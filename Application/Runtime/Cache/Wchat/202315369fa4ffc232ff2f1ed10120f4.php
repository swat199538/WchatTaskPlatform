<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="Pragma" content="no-cache">
    <meta content="telephone=no" name="format-detection">
    <meta content="email=no" name="format-detection">
    <link href="<?php echo (ADMIN_CSS_URL); ?>style.css" rel="stylesheet" type="text/css">

    <link href="<?php echo (HOME_CSS_URL); ?>basecss.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo (HOME_JS_URL); ?>/jquery.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo (HOME_JS_URL); ?>/shike.js"></script><meta name="viewport" content="width=750, user-scalable=no, target-densitydpi=device-dpi">
    <title>小萌差事-公众号分配</title>
    <link href="<?php echo (HOME_CSS_URL); ?>myinfocss.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="wrap">
        <div class="main">
            <div class="qrcode">
                <strong>微信扫描</strong>下方二维码进入，扫描后将自动绑定您的账号。【此二维码包含您的账户信息，请勿外泄】
                <div class="danger">为了您的账户安全，请务必从本网站获取二维码扫描进入</div>
                <!--<img src="<?php echo ($url); ?>">-->
            </div>
            <img class="jztp"  src="<?php echo ($url); ?>"/>
        </div>
</div>




<div class="nav4">
    <nav>
        <div id="nav4_ul" class="nav_4">
            <ul class="box">
                <li style="width:15%">
                    <a href="#" class="Click_bj">
                        <span class="Home_page"></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo U('Member/acceptTask',array('id'=>$data));?>" class="Click_bj j_date">
                        <span>开始赚钱</span></a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="Click_bj"><em class="connav2">
                    </em><span>我的信息</span></a>
                    <dl>
                        <dd>
                            <a href="<?php echo U('Member/index');?>" class="Click_bj j_date"><span>个人中心</span></a>
                        </dd>
                    </dl>
                </li>
                <li>
                    <a href="javascript:void(0);" class="Click_bj"><em class="connav2"></em><span>更多</span></a>
                    <dl>
                        <dd>
                            <a href="http://i.appshike.com/html/aboutus.jsp" class="Click_bj"><span>关于我们</span></a>
                        </dd>
                        <dd>
                            <a href="http://i.appshike.com/html/notescontact.jsp" class="Click_bj">
                                <span style="border-bottom:0px;">常见问题</span>
                            </a>
                        </dd>
                    </dl>
                </li>
            </ul>
        </div>
    </nav>
    <div id="nav4_masklayer" class="masklayer_div">&nbsp;</div></div></div>
    <script src="/project2/parttime/Public/home/js/bottom.js"></script>
</body>
</html>