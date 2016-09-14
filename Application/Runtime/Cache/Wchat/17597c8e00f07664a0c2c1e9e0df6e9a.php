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
    <title>任务记录</title>
    <link href="<?php echo (HOME_CSS_URL); ?>myinfocss.css" rel="stylesheet" type="text/css">
    <link href="<?php echo (HOME_CSS_URL); ?>button.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .detialTable {margin: 0px auto; width: 96%;}
        .detialTable tr td{padding:2px;}
        .ll{text-align: left;}
        .rr{ width: 50%;}
    </style>
</head>
<body>
<div class="return_index"><a href="javascript:history.go(-1);" class="return_link"></a><h1 id="aa">任务记录</h1></div><div class="wrap">
    <?php if($withdrawaData == null): ?><p class="no_info" >您还没有做过任务哦!</p>
        <?php else: ?>

        <div class="tx_record cf" id="diw_id" >
            <?php if(is_array($withdrawaData)): $i = 0; $__LIST__ = $withdrawaData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="record_info cf">
                    <p class="time"><?php echo ($vo["accept_time"]); ?></p>
                    <ul class="record_list cf msgid"  data-msg="<?php echo ($vo["submit_result_id"]); ?>">
                        <li class="Click_bj extract_ok">
                            <div class="lit">
                                <input value="33358270" type="hidden">
                                <label>
                                    <?php echo ($vo["title"]); ?>
                                </label>
                                <p class="record_state">
                                    单价:<?php echo ($vo["price"]); ?> 元
                                    <?php if($vo["status"] == 0): ?><span class="eb6464" style="background:#20a023;">进行中</span><?php endif; ?>
                                    <?php if($vo["status"] == 1): ?><span class="bagef8d3e" style="background:#3288d1;">完成</span><?php endif; ?>
                                    <?php if($vo["status"] == 2): ?><span class="bag3a90f4" style="background: #ff0000">放弃</span><?php endif; ?>
                                    <?php if($vo["status"] == 3): ?><span class="eb6464" style="background: #ff8100">提交中</span><?php endif; ?>
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
                    <h1 class="extract_title">任务审核进度</h1>
                    <div id="notice" class="ns-box-inner cf" style="height: 267px; overflow: auto;">
                        <div id="load" style="display: block">
                            <img src="<?php echo (HOME_IMG_URL); ?>/loading.gif" style="display: block;margin: 0 auto;" alt="">
                        </div>
                        <table class="detialTable" style="display: none" border="1" cellspacing="0px">
                            <tbody>

                            </tbody>
                        </table>
                        <div id="auditid" style="margin: 5px auto; text-align: center; display: none;">
                            <input class="button button-caution button-rounded button-jumbo" type="button" value="审核申诉" data-auditid="">
                        </div>
                    </div>
                </div>
            </div><?php endif; ?>
</div>
<div id="loading" style="display: none">
    <img src="<?php echo (HOME_IMG_URL); ?>/loading.gif" style="display: block;margin: 0 auto;" alt="">
</div>
<script>
    var index=6;
    var url='<?php echo U("Member/ajaxLoadTaskHandle","",false);?>';
    var dataUrl='<?php echo U("Member/ajaxLoadTaskAuditStatus","",false);?>';
    var appealUrl='<?php echo U("Member/ajaxAppealTask","",false);?>';
</script>
<script type="text/javascript" charset="utf-8" src="<?php echo (HOME_JS_URL); ?>tasklog.js"></script>

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