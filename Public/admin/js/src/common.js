/**
 * Created by king on 16/2/5.
 */


/**
 * 模态框确定执行删除
 */
function delOk(delNode, modelNode, modelOkNode) {
    var del = delNode;
    var that = null;
    var model = modelNode;
    var href = null;
    var flg = false;

    // 模态框显示时的回调
    model.on('show.bs.modal', function (e) {

    });

    // 模态框点击确定时的回调
    model.on('hide.bs.modal', function (e) {

        if (true === flg) {
            window.location.href = that.data('del');
            return true;
        }
    });

    // 模态框点击取消时的回调
    modelOkNode.click(function() {
        flg = true;
        model.modal('hide');
    });

    if (del.length > 0) {

        del.on('click', function() {
            that = $(this);
            model.modal('toggle');
            return false;
        });
    }
}

$(function() {
    delOk(
        $(".del"),
        $("#delModal"),
        $(".modal-footer > .btn-danger")
    );

    /*if ($(".datetimepicker").length > 0) {
        $(".datetimepicker").datetimepicker({
            "format" : "yyyy-mm-dd hh:ii",
            "autoclose" : true,
            "language" : "zh-CN",
            "startView" : 2,
            "minuteStep" : 5,
        });
    }*/

    // 配置权限时的全选效果
    if ($("#role_lists").length) {

        $(".check_level1").on("click", function() {
            var that = $(this);
            var nodeEle = that.parents(".table-responsive").find("input[name='access[]']");

            if ($(this).is(":checked")) {
                nodeEle.prop("checked", "checked");
            } else {
                nodeEle.removeAttr("checked");
            }

        });

        $(".check_level2").on("click", function() {
            var that = $(this);
            var nodeEle = $(this).parents("h3").next().find("input[name='access[]']");

            if ($(this).is(":checked")) {
                nodeEle.prop("checked", "checked");
            } else {
                nodeEle.removeAttr("checked");
            }
        });

        $(".check_level3").on("click", function() {
            var that = $(this);
            var nodeEle = $(this).parents("tr").next().find("input[name='access[]']");

            if ($(this).is(":checked")) {
                nodeEle.prop("checked", "checked");
            } else {
                nodeEle.removeAttr("checked");
            }
        });

    }

});
