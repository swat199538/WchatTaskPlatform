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
    $(".errorInform").remove();
    $("#auditid").css('display','none');
});

bindAndGetData();

//为msgid绑定click并获取msgid的值
function bindAndGetData() {
    //查看资金详情
    $(".msgid").unbind();
    $(".msgid").on("click",function () {
        var id=$(this).data('msg');

        if(id==''||id=='undefined'){
            $("#msg_div").css("display","block");
            $("#load").css("display","none");
            $("#notice").append("<div class='errorInform' style='text-align: center;'>此任务还没有提交</div>");
        } else {
            $("#msg_div").css("display","block");
            if(bindStatus==0){
                bindStatus=1;
                loadLogDetial(id);
            }
        }

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

        for (var i=0;i<data['data'].length;i++){
            var tempDom=null;
            var status=null;
            switch (data['data'][i]['status']){
                case '0':
                    status='<span class="eb6464" style="background:#20a023;">进行中</span>';
                    break;
                case '1':
                    status='<span class="bagef8d3e" style="background:#3288d1;">完成</span>';
                    break;
                case '2':
                    status='<span class="bag3a90f4" style="background: #ff0000">放弃</span>';
                    break;
                case '3':
                    status='<span class="eb6464" style="background: #ff8100">提交中</span>';
                    break;
                case '4':
                    status='<span class="eb6464" style="background: #8bc34a">申诉中</span>';
                    break;
            }
            tempDom='<div class="record_info cf">'+
            '<p class="time">'+data['data'][i]['accept_time']+'</p>'+
            '<ul class="record_list cf msgid"  data-msg="'+data['data'][i]['id']+'">'+
            '<li class="Click_bj extract_ok"><div class="lit"><input value="33358270" type="hidden"><label>'
            +data['data'][i]['title']+'</label><p class="record_state">单价:'+data['data'][i]['price']+'元'+status
            +'</p></div></li></ul></div>'
            ;
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
        $("#notice").append("<div class='errorInform' style='text-align: center;'>查询数据出错稍候再试</div>");
        bindStatus=0;
    } else {
        $("#load").css("display","none");
        var status=null;
        switch(data['status']){
            case '1':
                status='待审核';
                break;
            case '2':
                status='审核驳回';
                $("#auditid").css('display','block');
                $("#auditid input").data('auditid',data['id']);
                break;
            case '3':
                status='审核通过';
                break;
            case '4':
                status='任务提交中';
                break;
            case '5':
                status='任务申诉中';
                break;
            case '6':
                status='申诉驳回';
                break;
            case '7':
                status='申诉成功';
                break;
        }
        $(".detialTable tbody").append(
            '<tr><td class="ll">审核状态</td><td class="rr">'+status+'</td></tr>' +
            '<tr><td class="ll">审核时间</td><td class="rr">'+data['audit_time']+'</td></tr>'+
            '<tr><td class="ll">审核备注</td><td class="rr">'+data['remark']+'</td></tr>'
        );
            //替换null
            $(".rr").each(function () {
                $(this).html($(this).text().replace('null',''));
            });
        //$(".rr").html($(".rr").text().replace('null','无'));
        $(".detialTable").css("display","block");
        bindStatus=0;
    }
}

//申诉任务
$("#auditid input").on('click',function () {
   var id=($('#auditid input').data('auditid'));
    $.post(
        appealUrl,
        {
            'id':id
        },
        function (data) {
            if (data=='200'){
                $("#auditid").css('display','none');
                alert('申诉成功提交成功');
            }else {
                alert(data);
            }
        }
    );
});