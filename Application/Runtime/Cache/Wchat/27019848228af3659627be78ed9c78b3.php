<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<!-- saved from url=(0068)http://localhost/project2/parttime/index.php/Wchat/Member/index.html -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="Pragma" content="no-cache">
    <meta content="telephone=no" name="format-detection">
    <meta content="email=no" name="format-detection">

    <link href="<?php echo (HOME_CSS_URL); ?>basecss.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo (HOME_JS_URL); ?>/jquery.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo (HOME_JS_URL); ?>/shike.js"></script><meta name="viewport" content="width=750, user-scalable=no, target-densitydpi=device-dpi">
    <title>小萌差事-个人中心</title>
    <link href="<?php echo (HOME_CSS_URL); ?>myinfocss.css" rel="stylesheet" type="text/css">
    <!--暂时放css-->
    <style>
        .income_condition{ background:#3a90f4;padding:40px 0px;}
        .cf { zoom: 1;}
        .income_condition .moneyinfo { width: 33%; text-align: center; float: left; position: relative;}
        .income_condition .moneyinfo h4 {font-size: 32px; width: 100%; margin-bottom: 12px; font-weight: normal;color: #fff;}
        .income_condition .moneyinfo p {color: rgba(255,255,255,0.5); font-size: 24px;}
        .income_condition .moneyinfo:last-child.moneyinfo:before { content: "";  width: 0px; }
        .income_condition .moneyinfo:before { content: ""; width: 2px; height: 36px; position: absolute; right: 0px; top: 25%;  display: block; background: rgba(255,255,255,0.6); z-index: 8;}
        /*用于处理下面的塌陷*/
        .individualism .my_er_code{ margin-bottom:135px;}
    </style>
</head>
<body>
<div class="return_index"><a href="javascript:history.go(-1);" class="return_link"></a><h1>个人中心</h1></div><div class="wrap">
    <div class="income_query cf">
        <div class="individualism cf">
            <div class="cf myinfo">
                <span class="my_photo fl">
                    <img src="<?php echo (HOME_URL); echo ($userInfo["head_image_url"]); ?>" alt="">
                </span>
                <div class="tit">
                    <h4 class="my_name"><?php echo ($userInfo["nickname"]); ?></h4>
                    <p class="my_ID">ID:<?php echo ($userInfo["id"]); ?></p>
                </div>
            </div>

            <div class="my_er_code">
                <!--可提收入，已提收入插入-->
                <div id="showtoday" class="income_condition cf">
                    <div id="showtodaymoney" class="moneyinfo">
                        <h4 id="zj"><?php echo ($userInfo["balance"]); ?></h4>
                        <p>我的萌点</p>
                    </div>
                    <div class="moneyinfo">
                        <h4 id="z2"><?php echo ($userInfo["finsh_task_count"]); ?></h4>
                        <p>我完成差事</p>
                    </div>
                    <div class="moneyinfo">
                        <h4 id="z3"><?php echo ($userInfo["invite_member_count"]); ?></h4>
                        <p>我的小伙伴</p>
                    </div>
                </div>
                <!--可提收入，已提收入结束-->
                <div class="click_ercode Click_bj show_Qrcode">
                    <span class="er_code_deck">邀请二维码</span>
                </div>

            </div>
        </div>
        <div class="my_tinymask"></div>
        <ul class="myinfo_list cf">
            <a href="<?php echo U('Member/editWithdrawals',array('id'=>$data));?>">
                <li id="tixian" class="Click_bj">
                    <span class="editor_deck"></span>
                    <div class="info r_point">
                        <label class="name">编辑提现信息</label>
                        <span class="r"></span>
                    </div>
                </li>
            </a>
            <li id="phone_update" class="Click_bj">
                <span class="change_phone_deck"></span>
                <div class="info r_point">
                    <label class="name">提现申请</label>
                    <span class="r"></span>
                </div>
            </li>

        </ul>
        <ul class="myinfo_list cf">
            <a href="<?php echo U('Member/lookTaskLog');?>">
                <li id="li_0" class="Click_bj">
                    <span class="try_deck"></span>
                    <div class="info r_point">
                        <label class="name">任务记录</label>
                        <span class="r"></span>
                    </div>
                </li>
            </a>
            <li id="li_1" class="Click_bj">
                <span class="invite_deck"></span>
                <div class="info r_point">
                    <label class="name">邀请记录</label>
                    <span class="r"></span>
                </div>
            </li>
            <a href="<?php echo U('Member/lookWithdrawalsLog');?>">
            <li id="li_2" class="Click_bj">
                <span class="withdraw_deck"></span>
                <div class="info r_point">
                    <label class="name">提现记录</label>
                    <span class="r"></span>
                </div>
            </li>
            </a>
        </ul>
    </div>
</div>

<div id="msg_div" class="msg_extract_ing" style="display:none;">
    <div class="tinymask"></div>
    <div class="tinybox cf" style="background:#fff">
        <div class="extract_record"><img src="<?php echo (HOME_IMG_URL); ?>/extract_ing.jpg" alt="" width="100%"></div>
        <span id="close" class="ns-close binding_close"></span>
        <h1 class="extract_title">邀请二维码,分享给朋友吧</h1>
        <div id="notice" class="ns-box-inner cf" style="height: 267px; overflow: auto;">
            <div id="load" style="display: block">
                <img id="headImg" src="<?php echo (HOME_IMG_URL); ?>/loading.gif" style="display: block;margin: 0 auto;" alt="">
            </div>
        </div>
    </div>
</div>


<script>
    var state=1;
    $(".show_Qrcode").on("click",function () {
        $("#msg_div").css('display','block');
        if (state==1) {
            $.post(
                    "<?php echo U('Member/loadQRcode');?>",
                    {},
                    function (data) {
                        if(data!=404){
                            $("#headImg").attr('src', data);
                        } else {
                            alert("请求邀请码错误，请稍候再试")
                        }
                    }
            );
        }
    })

    $("#close").on("click",function () {
        $("#msg_div").css('display','none');
    });

</script>

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