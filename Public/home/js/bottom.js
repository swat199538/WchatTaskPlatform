/**
 * Created by wangl on 2016/9/1.
 */
/**
 * 弹出菜单
 */
$(".Click_bj").on("click",function () {
    var that=$(this);
    if (that.hasClass("on")){
        that.removeClass("on");
    } else {
        that.addClass("on");
    }
});

