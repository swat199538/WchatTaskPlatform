<?php if (!defined('THINK_PATH')) exit();?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="Pragma" content="no-cache">
    <meta content="telephone=no" name="format-detection">
    <meta content="email=no" name="format-detection">

    <link href="<?php echo (HOME_CSS_URL); ?>basecss.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo (HOME_JS_URL); ?>/jquery.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo (HOME_JS_URL); ?>/shike.js"></script>
    <meta name="viewport" content="width=750, user-scalable=no, target-densitydpi=device-dpi">
    <link href="<?php echo (HOME_CSS_URL); ?>myinfocss.css" rel="stylesheet" type="text/css">
    <title>提现信息</title>
    <style>
        @-webkit-keyframes center_touch {
            0%,100%,60%,75%,90% {
                -webkit-animation-timing-function:cubic-bezier(0.215,.61,.355,1);
                animation-timing-function:cubic-bezier(0.215,.61,.355,1)
            }
            0% {
                opacity:0;
                -webkit-transform:translate3d(0,-3000px,0);
                transform:translate3d(0,-3000px,0)
            }
            60% {
                opacity:1;
                -webkit-transform:translate3d(0,25px,0);
                transform:translate3d(0,25px,0)
            }
            75% {
                -webkit-transform:translate3d(0,-10px,0);
                transform:translate3d(0,-10px,0)
            }
            90% {
                -webkit-transform:translate3d(0,5px,0);
                transform:translate3d(0,5px,0)
            }
            100% {
                -webkit-transform:none;
                transform:none
            }
        }
        @keyframes center_touch {
            0%,100%,60%,75%,90% {
                -webkit-animation-timing-function:cubic-bezier(0.215,.61,.355,1);
                animation-timing-function:cubic-bezier(0.215,.61,.355,1)
            }
            0% {
                opacity:0;
                -webkit-transform:translate3d(0,-3000px,0);
                transform:translate3d(0,-3000px,0)
            }
            60% {
                opacity:1;
                -webkit-transform:translate3d(0,25px,0);
                transform:translate3d(0,25px,0)
            }
            75% {
                -webkit-transform:translate3d(0,-10px,0);
                transform:translate3d(0,-10px,0)
            }
            90% {
                -webkit-transform:translate3d(0,5px,0);
                transform:translate3d(0,5px,0)
            }
            100% {
                -webkit-transform:none;
                transform:none
            }
        }
        .center_touch{width: 100%; overflow-y: auto;max-height:88%;-webkit-overflow-scrolling: touch;-webkit-animation-name:center_touch;animation-name:center_touch;-webkit-animation-duration:1s;animation-duration:1s;}
        .tinymask{z-index: 99; margin: 0px; padding: 0px; width: 100%; height:100%; top: 0px; left: 0px;position:fixed;background-color:rgba(0,0,0,0.5);display: -webkit-flex;display: flex;flex-flow: column;-webkit-tap-highlight-color:transparent;
            -webkit-align-items: center;align-items: center;-webkit-justify-content: center;justify-content: center;}
        .tinybox{z-index:100;border-radius:10px;width:570px;background:#fff;position:relative;left:50%;top: inherit; margin:30px auto 50px -285px; overflow: hidden;}
    </style>

</head>
<body>
<div class="return_index"><a href="javascript:history.go(-1);" class="return_link"></a><h1>提现信息</h1></div><div class="wrap">
    <div class="binding">
        <div class="binding_info cf">
            <div id="zfb_binding" class="alipay cf Click_bj">
                <p class="alipay_icon"></p>
                <h3 id="z_bd_name" class="bd_name">支付宝帐户<p class="add">添加</p></h3>
                <p id="z_bd_account" class="bd_account" style="display: none;"></p>
                <span id="z_bd_modify" class="modify" style="display: none;">修改</span>
            </div>
            <div id="wechat_binding" class="wechat cf Click_bj">
                <p class="wechat_icon"></p>
                <h3 id="w_bd_name" class="bd_name">微信钱包<p class="add">添加</p></h3>
                <p id="w_bd_account" class="bd_account" style="display: none;">刘坚</p>
                <span id="w_bd_modify" class="modify" style="display: none">修改</span>
            </div>
        </div>
        <!--<ul class="bindinglist cf">
            <li id="creditCard_add" class="Click_bj add_password" style="display:none">
                <span class="password_deck"></span>
                <div class="info r_point">
                    添加银行卡
                </div>
            </li>
            <li id="creditCard_edit" class="Click_bj add_password" style="">
                <span class="password_deck"></span>
                <div class="info r_point">
                    已添加银行卡
                    <span class="fr r">修改</span>
                </div>
            </li>

            <li id="password_add" class="Click_bj add_password" style="display: none">
                <span class="password_deck"></span>
                <div class="info r_point">
                    添加提现密码
                </div>
            </li>
            <li id="password_yitianjia" class="Click_bj add_password" style="display: block;">
                <span class="password_deck"></span>
                <div class="info r_point">
                    已添加
                    <span class="fr r">修改</span>
                </div>
            </li>
        </ul>-->
    </div>
    <!------绑定微信------->
    <div class="msg_binding_wechat" style="display:none">
        <div class="tinymask">
            <div class="center_touch">
                <div id="wx_bindPhone_div" class="tinybox cf">
                    <h4 class="notice binding-head wechatbj">微信钱包</h4>
                    <span class="ns-close binding_close"></span>
                    <div class="ns-box-inner mb30">
                        <div class="binding_card">
                            <h4 class="bt">请填写<br><span class="wechat_tutorial">绑定微信钱包</span> 的银行卡姓名</h4>
                            <p class="card_info"><input id="wei_name" placeholder="微信收款人姓名" type="text"></p>
                            <div id="wx_phone" class="binding_phone">
                                <p class="card_info"><input id="wx_tel" placeholder="请输入手机号" type="tel"></p>
                                <p class="card_info">
                                    <input id="wx_vCode" style=" width:42%; margin-right:2%" placeholder="填写验证码" type="number">
                                    <button id="wx_send" class="send col48b960" onclick="sendMsg('wx',1)">发送验证码</button><!----点击发送后加class  ARQ---->
                                </p>
                                <p class="speech_verification" style="margin-top:30px">
                                    <span class="voicecode2" onclick="sendMsg('wx',2)">收听语音验证码</span>
                                </p>
                            </div>
                            <input value="7102993" id="wx_id" type="hidden">
                        </div>
                    </div>
                    <div class="ns_action">
                        <input class="Click_bj cancel" value="取消" id="wx_cal" type="button">
                        <input class="Click_bj" value="删除" id="wx_del" type="button">
                        <input id="but_add_weixin" class="brc48b960 Click_bj colfff w_bt" value="确定" type="button">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---微信钱包绑定成功--->
    <div class="msg_binding_wechatDone" style="display:none">
        <div class="tinymask">
            <div class="center_touch">
                <div class="tinybox cf">
                    <h4 class="notice binding-head wechatbj">添加成功</h4>
                    <span class="ns-close binding_close"></span>
                    <div class="ns-box-inner mb30">


                    </div>
                    <div class="ns_action">
                        <input class="Click_bj Iknow off" value="我知道了" onclick="reload();" type="button">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---微信钱包删除信息--->
    <div id="wx_del_msg" class="" style="display:none">
        <div class="tinymask">
            <div class="center_touch">
                <div class="tinybox cf">
                    <h4 class="notice binding-head wechatbj">温馨提示</h4>
                    <span class="ns-close binding_close"></span>
                    <div class="ns-box-inner mb30">
                        <p class="tips">您确定删除微信钱包信息？</p>
                    </div>
                    <div class="ns_action">
                        <input class="Click_bj cancel" value="取消" type="button">
                        <input id="wx_do_del" class="brc48b960 Click_bj colfff" value="删除" type="button">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!------绑定微信步骤------->
    <div class="msg_bd_wechat_step" style="display:none;">
        <div class="tinymask">
            <div class="center_touch">
                <div class="tinybox cf">
                    <h4 class="notice binding-head wechatbj">绑定微信钱包</h4>
                    <span class="ns-close binding_close"></span>
                    <div class="ns-box-inner mb30 step_ts">
                        <div class="w_lit">
                            <img src="<?php echo (HOME_IMG_URL); ?>/weixin_qbimg1.jpg" alt="">
                            <img src="<?php echo (HOME_IMG_URL); ?>/weixin_qbimg2.jpg" alt="">
                            <img src="<?php echo (HOME_IMG_URL); ?>/weixin_qbimg3.jpg" alt="">
                        </div>
                    </div>
                    <div class="ns_action">
                        <input class="Click_bj Iknow step_w" value="我知道了" type="button">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---绑定支付宝--->
    <div class="msg_binding_alipay" style="display:none">
        <div class="tinymask">
            <div class="center_touch">
                <div id="zfb_bindPhone_div" class="tinybox cf">
                    <h4 class="notice alipaybj binding-head">支付宝</h4>
                    <span class="ns-close binding_close" id="closeApay"></span>
                    <div class="ns-box-inner mb30">
                        <div class="binding_card">
                            <h4 class="bt">绑定 <span class="alipay_tutorial">实名认证</span><br>过的支付宝账号才能收款</h4>
                            <p class="card_info"><input id="bank_username" placeholder="支付宝收款人姓名" class="" type="text"></p>
                            <p class="card_info"><input id="bank_num" placeholder="支付宝收款人帐号" class="" type="text"></p>
                            <div id="zfb_phone" class="binding_phone">
                                <p class="card_info"><input id="zfb_tel" placeholder="请输入手机号" type="tel"></p>
                                <p class="card_info">
                                    <input id="zfb_vCode" style=" width:42%; margin-right:2%" placeholder="填写验证码" type="number">
                                    <button id="zfb_send" class="send col169bf8" onclick="sendMsg('zfb',1)">发送验证码</button><!----点击发送后加class  ARQ---->
                                </p>
                                <p class="speech_verification" style="margin-top:30px">
                                    <span class="voicecode1" onclick="sendMsg('zfb',2)">收听语音验证码</span>
                                </p>
                            </div>
                            <input value="0" id="pay_id" type="hidden">
                        </div>
                    </div>
                    <div class="ns_action">
                        <input id="pay_cal" class="Click_bj cancel" value="取消" type="button">
                        <!--<input id="pay_del" class="Click_bj" value="删除" type="button">-->
                        <input id="but_add_zfb" class="brc169bf8 Click_bj colfff ali_bt" value="确定" type="button">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---支付宝绑定成功--->
    <div class="msg_binding_alipayDone" style="display:none">
        <div class="tinymask">
            <div class="center_touch">
                <div class="tinybox cf">
                    <h4 class="notice binding-head alipaybj">添加成功</h4>
                    <span class="ns-close binding_close"></span>
                    <div class="ns-box-inner mb30">

                    </div>
                    <div class="ns_action">
                        <input class="Click_bj Iknow off" value="我知道了"  type="button">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---支付删除信息宝--->
    <div id="zfb_del_msg" class="" style="display:none">
        <div class="tinymask">
            <div class="center_touch">
                <div class="tinybox cf">
                    <h4 class="notice alipaybj binding-head">温馨提示</h4>
                    <span class="ns-close binding_close"></span>
                    <div class="ns-box-inner mb30">
                        <p class="tips">您确定删除支付宝信息？</p>
                    </div>
                    <div class="ns_action">
                        <input class="Click_bj cancel" value="取消" type="button">
                        <input id="zfb_do_del" class="brc169bf8 Click_bj colfff" value="删除" type="button">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="nav4">		        <nav>	            <div id="nav4_ul" class="nav_4">	                <ul class="box">	                	<li style="width:15%">                			<a href="http://i.appshike.com/html/menu.html" class="Click_bj"><span class="Home_page"></span></a>                		</li>	                    <li>	                        <a href="http://i.appshike.com/shike/appList?t=1472527852072" class="Click_bj j_date"><span>开始赚钱</span></a>	                    </li>	                    <li>	                        <a href="javascript:void(0);" class="Click_bj"><em class="connav2"></em><span>我的信息</span></a>	                        <dl>	                            <dd><a href="http://i.appshike.com/itry/personalcenter/toPersonalCenter?t=1472527852072" class="Click_bj j_date"><span>个人中心</span></a></dd>	                            <dd><a href="http://i.appshike.com/itry/invite/toInviteFriendsOfXB?type=2" class="Click_bj"><span>邀请好友</span></a></dd>	                            <dd><a href="http://i.appshike.com/itry/weixin/toRankingList" class="Click_bj"><span>排行榜</span></a></dd>	                            <dd><a href="http://i.appshike.com/itry/income/toIncomeInfo?t=1472527852072" class="Click_bj j_date"><span style="border-bottom:0px;">收入查询</span></a></dd>	                        </dl>	                    </li>	                    <li>	                        <a href="javascript:void(0);" class="Click_bj"><em class="connav2"></em><span>更多</span></a>	                        <dl>	                            <dd><a href="http://i.appshike.com/html/aboutus.jsp" class="Click_bj"><span>关于我们</span></a></dd>	                            <dd><a href="javascript:download_xb();" class="Click_bj"><span>下载小兵</span></a></dd>	                            <dd><a href="http://i.appshike.com/html/notescontact.jsp" class="Click_bj"><span style="border-bottom:0px;">常见问题</span></a></dd>	                        </dl>	                    </li>	                </ul>	            </div>	        </nav>	        <div id="nav4_masklayer" class="masklayer_div">&nbsp;</div>	    </div></div>

<!------绑定支付宝步骤------->
<div class="msg_bd_alpay_step" style="display:none;">
    <div class="tinymask">
        <div class="center_touch">
            <div class="tinybox cf">
                <h4 class="notice alipaybj binding-head">绑定支付宝</h4>
                <span class="ns-close binding_close"></span>
                <div class="ns-box-inner mb30 step_ts">
                    <div class="ali_lit">
                        <img src="<?php echo (HOME_IMG_URL); ?>/alipay_qbimg1.jpg" alt="">
                    </div>
                </div>
                <div class="ns_action">
                    <input class="Click_bj Iknow step_ali" value="我知道了" type="button">
                </div>
            </div>
        </div>
    </div>
</div>
<!---提现密码---->
<div class="msg_password" style="display:none">
    <div class="tinymask">
        <div class="center_touch">
            <div class="tinybox cf">
                <h4 class="notice password-head">提现密码</h4>
                <span class="ns-close"></span>
                <div class="ns-box-inner mb30">
                    <div class="password_tit">此密码仅作为<br>提现使用</div>
                    <p class="password_entry"><input placeholder="请输入原始密码" style="display:none" class="" id="old_pass" maxlength="6" type="password"></p>
                    <p class="password_entry"><input placeholder="请输入 6 位数字" class="" id="new_pass" maxlength="6" type="password"></p>
                    <p class="password_entry"><input placeholder="请输入 6 位数字" class="" id="new_pass2" maxlength="6" type="password"></p>
                </div>
                <div class="ns_action">
                    <input class="Click_bj cancel" value="取消" id="pw_cal" type="button">
                    <input class="Click_bj" value="删除" style="display: none;" id="pw_del" type="button">
                    <input class="brc169bf8 Click_bj colfff pass_st" value="确定" id="do_mod_pw" type="button">
                </div>
            </div>
        </div>
    </div>
</div>


<!---支付宝或者微信钱包删除成功---->
<div id="del_success_msg" class="msg_password_Done" style="display:none">
    <div class="tinymask">
        <div class="center_touch">
            <div class="tinybox cf">
                <h4 class="notice password-head">温馨提示</h4>
                <span class="ns-close"></span>
                <div class="ns-box-inner mb30">
                    <p id="del_msg_tips" class="tips">删除成功</p>
                </div>
                <div class="ns_action">
                    <input class="Click_bj Iknow off" value="我知道了" type="button">
                </div>
            </div>
        </div>
    </div>
</div>
<!---没有openid 添加微信提现信息时的提示---->
<div id="no_weixin_msg" class="msg_password_Done" style="display:none">
    <div class="tinymask">
        <div class="center_touch">
            <div class="tinybox cf">
                <h4 class="notice password-head">温馨提示</h4>
                <span class="ns-close"></span>
                <div class="ns-box-inner mb30">
                    <p class="tips">暂不支持微信提现<br>请使用支付宝</p>
                </div>
                <div class="ns_action">
                    <input class="Click_bj Iknow off" value="我知道了" type="button">
                </div>
            </div>
        </div>
    </div>
</div>
<!---没有openid 添加微信提现信息时的提示---->
<div id="no_zfb_msg" class="msg_password_Done" style="display:none">
    <div class="tinymask">
        <div class="center_touch">
            <div class="tinybox cf">
                <h4 class="notice password-head">温馨提示</h4>
                <span class="ns-close"></span>
                <div class="ns-box-inner mb30">
                    <p class="tips">暂不支持支付宝提现<br>请使用微信</p>
                </div>
                <div class="ns_action">
                    <input class="Click_bj Iknow off" value="我知道了" type="button">
                </div>
            </div>
        </div>
    </div>
</div>
<!---提现密码绑写成功---->
<div class="msg_password_Done" style="display:none">
    <div class="tinymask">
        <div class="center_touch">
            <div class="tinybox cf">
                <h4 class="notice password-head">修改成功</h4>
                <span class="ns-close"></span>
                <div class="ns-box-inner mb30">
                    <p class="tips">请牢记<br>
                        你的密码是: 123456</p>
                </div>
                <div class="ns_action">
                    <input class="Click_bj Iknow off" value="我知道了" type="button">
                </div>
            </div>
        </div>
    </div>
</div>


<!---删除提现密码提示---->
<div class="msg_password_delete" style="display:none">
    <div class="tinymask">
        <div class="center_touch">
            <div class="tinybox cf">
                <h4 class="notice password-head">温馨提示</h4>
                <span class="ns-close"></span>
                <div class="ns-box-inner mb30">
                    <p class="tips">为保护您的账户安全<br> 24 小时后消除提现密码</p>
                </div>
                <div class="ns_action">
                    <input id="do_del" class="Click_bj Iknow col3a90f4 " value="确定" type="button">
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" charset="utf-8" src="<?php echo (HOME_JS_URL); ?>/editWithdrawals.js"></script>


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