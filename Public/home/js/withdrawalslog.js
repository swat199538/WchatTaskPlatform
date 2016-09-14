/**
 * Created by wangl on 2016/9/5.
 */
var status=0;
var bindStatus=0;

//判断文档是否滚动到底部
$(window).css("overflow","hidden");
$(window).scroll(function(){
    if ($(window).scrollTop()+$(window).height()+1>=$(document).height()) {
        if (status==0){
            $("#loading").css('display','block');
            loadLogData(index);
            status=1;
        }
    }
});

//关闭详情页面
$("#close").click(function () {
    $("#msg_div").css("display","none");
    $(".detialTable").css("display","none");
    $("#load").css("display","block");
    $(".detialTable tbody tr").remove();
});

bindAndGetData();

//为msgid绑定click并获取msgid的值
function bindAndGetData() {
    //查看资金详情
    $(".msgid").unbind();
    $(".msgid").on("click",function () {
        var id=$(this).data('msg');
        $("#msg_div").css("display","block");
        loadLogDetial(id);
    })
}

function loadLogData(index) {
    $.post(
        url,
        {
            'index':index
        },
        function (data) {
            data=$.parseJSON(data)
            console.log(data);
            jointDom(data);
        }
    );
}

function loadLogDetial(id) {
    $.post(
        dataUrl,
        {
            'id':id
        },
        function (data) {
            data=$.parseJSON(data)
            console.log(data);
            jointDetialDom(data);
        }
    );
}

/*
操作AJAX获取的的数据
 */
function jointDom(data) {
    if (data['data']!=null) {
        var tempDom=null;
        var withdrawals_type_val=null;
        var audit_status_val=null;
        for (var i=0;i<data['data'].length;i++){
            //判断是什么方式提现
            switch (data['data'][i]['withdrawals_type']){
                case '1':
                    withdrawals_type_val='微信钱包';
                    break;
                case '2':
                    withdrawals_type_val='支付宝';
                    break;
                case '3':
                    withdrawals_type_val='银行卡';
                    break;
            }
            //判断提现状态
            switch (data['data'][i]['audit_status']){
                case '1':
                    audit_status_val='<span class="bagef8d3e">待审核</span>';
                    break;
                case '3':
                    audit_status_val='<span class="eb6464">审核未过</span>';
                    break;
                case '2':
                    switch (data['data'][i]['status']){
                        case '1':
                            audit_status_val='<span class="bagef8d3e">付款中</span>';
                            break;
                        case '2':
                            audit_status_val='<span class="bag3a90f4">已到账</span>';
                            break;
                        case '3':
                            audit_status_val='<span class="eb6464">付款失败</span>';
                            break;
                    }
                    break;
            }
            tempDom='<div class="record_info cf">' +
                ' <p class="time">'+data['data'][i]['create_time']+'</p>'+
                '<ul class="record_list cf msgid" data-msg="'+data['data'][i]['id']+'">'+
                '<li class="Click_bj extract_ok">'+
                '<div class="lit">'+
                '<input value="33358270" type="hidden">'+
                '<label>'+withdrawals_type_val+'</label>'+
                '<p class="record_state">'+data['data'][i]['amount']+'元'+
                audit_status_val+
                '</p></div></li></ul></div>';
            //插入到文档树
            $("#loading").css('display','none');
            $("#diw_id").append(tempDom);
        }
        //为新的dom绑定时间
        bindAndGetData();
        index=data['index'];
        status=0;
    }else {
        $("#loading").css('display','none');
    }
}

function jointDetialDom(data) {
    if (data=='404'){
        $("#load").css("display","none");
        $("#notice").append("<div style='text-align: center;'>查询数据出错稍候再试</div>");
    } else {
        $("#load").css("display","none");
        $(".detialTable tbody").append(
            '<tr><td class="ll">申请时间:</td>'+
            '<td class="rr">'+data['create_time']+'</td></tr>'+
            '<tr><td class="ll">提现流水号:</td>'+
            '<td class="rr">'+data['sn']+'</td></tr>'+
            '<tr><td class="ll">银行卡所属银行:</td>'+
            '<td class="rr">'+data['payee_bank']+'</td></tr>'+
            '<tr><td class="ll">收款账户:</td>'+
            '<td class="rr">'+data['payee_account']+'</td></tr>'+
            '<tr><td class="ll">收款人实名:</td>'+
            '<td class="rr">'+data['payee']+'</td></tr>'+
            '<tr><td class="ll">提现金额:</td>'+
            '<td class="rr">'+data['amount']+'</td></tr>'+
            '<tr><td class="ll">手续费:</td>'+
            '<td class="rr">'+data['fee']+'</td></tr>'+
            '<tr><td class="ll">银行反馈流水号:</td>'+
            '<td class="rr">'+data['bank_sn']+'</td></tr>'+
            '<tr><td class="ll">付款卡所属银行:</td>'+
            '<td class="rr">'+data['payer_bank']+'</td></tr>'+
            '<tr><td class="ll">付款银行帐号:</td>'+
            '<td class="rr">'+data['payer_account']+'</td></tr>'+
            '<tr><td class="ll">付款人实名:</td>'+
            '<td class="rr">'+data['payer']+'</td></tr>'
        );
            //替换null
            $(".rr").each(function () {
                $(this).html($(this).text().replace('null',''));
            });
        //$(".rr").html($(".rr").text().replace('null','无'));
        $(".detialTable").css("display","block");

    }
}