/**
 * Created by wangl on 2016/9/14.
 */

/**
 * 弹出支付宝界面
 * */
$("#zfb_binding").on("click",function () {
    $(".msg_binding_alipay").css('display','block');
})
/**
 *绑定事件关闭支付宝界面
 **/
$("#closeApay").on("click",function () {
    closeAlipay();
})
$("#pay_cal").on("click",function () {
    closeAlipay();
})

/**
 * 关闭支付宝界面
 */
function closeAlipay() {
    $(".msg_binding_alipay").css('display','none');
}