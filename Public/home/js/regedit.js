/**
 * Created by wangl on 2016/8/31.
 */
//点击开始赚钱
$(".btn_submit").on("click",function () {
    var username=$("#username").val();
    var phone=$("#phone").val();
    var password=$("#password").val();
    var password2=$("#password2").val();
    var phoneCode=$("#phoneCode").val();
    var captcha=$("#captcha").val();
    ajaxCkeck(phone,password,password2,phoneCode,username,captcha);
});


//ajax提交表单内容
function ajaxCkeck(phone,password,password2,phoneCode,username,captcha) {
    $.post(
        url,
        {
            'phone':phone,
            'password':password,
            'password2':password2,
            'phoneCode':phoneCode,
            'username':username,
            'captcha':captcha
        },
        function (data) {
            doAjaxReturn(data);
        }
    );
}

//执行ajax返回结果
function doAjaxReturn(data) {
    switch(data){
        case '500':
            alert("请POST提交数据");
            break;
        case '200':
            $("#form").submit();
            console.log("执行了200");
            break;
        default:
            var json=$.parseJSON(data);
            //手机错误提示
            console.log(json);
            for(var key in json){
                errorInfo(key,json[key]);
            }
            break
    }
}

//错误提示
function errorInfo(name,info) {
    name="#"+name;
    $(name).addClass('myInput');
    $(name).attr("placeholder",info);
    $(name).val("");
}