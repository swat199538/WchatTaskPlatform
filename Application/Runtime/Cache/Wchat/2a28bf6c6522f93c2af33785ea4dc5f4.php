<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="Pragma" content="no-cache">
    <meta content="telephone=no" name="format-detection">
    <meta content="email=no" name="format-detection">
    <link href="<?php echo (HOME_CSS_URL); ?>basecss.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo (HOME_JS_URL); ?>jquery.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo (HOME_JS_URL); ?>shike.js"></script><meta name="viewport" content="width=750, user-scalable=no, target-densitydpi=device-dpi">
    <title>提现记录</title>
    <link href="<?php echo (HOME_CSS_URL); ?>myinfocss.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .detialTable {margin: 0px auto; width: 96%;}
        .detialTable tr td{padding:2px;}
        .ll{text-align: left;}
        .rr{ width: 50%;}
    </style>
</head>
<body>
<div class="return_index"><a href="javascript:history.go(-1);" class="return_link"></a><h1 id="aa">提现记录</h1></div><div class="wrap">
    <?php if($withdrawaData == null): ?><p class="no_info" >您还没有提现记录哦!</p>
    <?php else: ?>

    <div class="tx_record cf" id="diw_id" >
        <?php if(is_array($withdrawaData)): $i = 0; $__LIST__ = $withdrawaData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="record_info cf">
            <p class="time"><?php echo ($vo["create_time"]); ?></p>
            <ul class="record_list cf msgid"  data-msg="<?php echo ($vo["id"]); ?>">
                <li class="Click_bj extract_ok">
                    <div class="lit">
                        <input value="33358270" type="hidden">
                        <label>
                            <?php if($vo["withdrawals_type"] == 1): ?>微信钱包<?php endif; ?>
                            <?php if($vo["withdrawals_type"] == 2): ?>支付宝<?php endif; ?>
                            <?php if($vo["withdrawals_type"] == 3): ?>银行卡<?php endif; ?>
                        </label>
                        <p class="record_state">
                            <?php echo ($vo["amount"]); ?> 元
                            <?php if($vo["audit_status"] == 2): if($vo["status"] == 1): ?><span class="bagef8d3e">付款中</span><?php endif; ?>
                                <?php if($vo["status"] == 2): ?><span class="bag3a90f4">已到账</span><?php endif; ?>
                                <?php if($vo["status"] == 3): ?><span class="eb6464">付款失败</span><?php endif; ?>
                            <?php else: ?>
                                <?php if($vo["audit_status"] == 1): ?><span class="bagef8d3e">待审核</span><?php endif; ?>
                                <?php if($vo["audit_status"] == 3): ?><span class="eb6464">审核未过</span><?php endif; endif; ?>
                        </p>
                    </div>
                </li>
            </ul>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>





        <div id="msg_div" class="msg_extract_ing" style="display:none;">
        <div class="tinymask"></div>
            <div class="tinybox cf" style="background:#fff">
                <div class="extract_record"><img src="<?php echo (HOME_IMG_URL); ?>/extract_ing.jpg" alt="" width="100%"></div>
                <span id="close" class="ns-close binding_close"></span>
                <h1 class="extract_title">提现详情</h1>
                <div id="notice" class="ns-box-inner cf" style="height: 267px; overflow: auto;">
                    <div id="load" style="display: block">
                        <img src="<?php echo (HOME_IMG_URL); ?>/loading.gif" style="display: block;margin: 0 auto;" alt="">
                    </div>
                    <table class="detialTable" style="display: none" border="1" cellspacing="0px">
                        <tbody>
                        <!--<tr>
                            <td class="ll">提现申请时间:</td>
                            <td class="rr">2016-10-05 19:08:46</td>
                        </tr>-->
                        </tbody>
                    </table>
                </div>
            </div>
    </div><?php endif; ?>
</div>
<div id="loading" style="display: none">
    <img src="<?php echo (HOME_IMG_URL); ?>/loading.gif" style="display: block;margin: 0 auto;" alt="">
</div>
<script>
    var index=6;
    var url='<?php echo U("Member/ajaxLoadWithdrawalsData","",false);?>';
    var dataUrl='<?php echo U("Member/ajaxLoadWdDetail","",false);?>';
</script>
<script type="text/javascript" charset="utf-8" src="<?php echo (HOME_JS_URL); ?>withdrawalslog.js"></script>

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