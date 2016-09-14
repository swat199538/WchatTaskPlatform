<?php
/**
 * 表单中的radio输入框checked设置
 *
 * @param string $data 表单输入的值
 * @param string $val 默认值
 * @param bool $firstChoice 是否是首选项
 * @return string
 */
function radioChecked($val, $data, $firstChoice = false)
{
    if (false !== $firstChoice) {
        if ($val == $data || !isset($data)) {
            $ch = 'checked';
        } else {
            $ch = '';
        }
    } else {
        if ($val == $data && isset($data)) {
            $ch = 'checked';
        } else {
            $ch = '';
        }
    }

    return $ch;
}

/**
 * 过滤字符串
 *
 * @param $val
 * @return string
 */
function removeXss($val) {
     static $obj = null; // 类似于单例

     if (null === $obj) {
         vendor('htmlpurifier.library.HTMLPurifier#auto');
         $config = HTMLPurifier_Config::createDefault();
         $config->set('HTML.TargetBlank', true);
         $obj = new HTMLPurifier($config);
    }

    return $obj->purify($val);
}

function dd($val)
{
    header("Content-type: text/html; charset=utf-8");
    echo '<pre>';
    print_r($val);
    echo '</pre>';
    exit;
}

/*//检查结束时间是否比开始时间小
function checkEndTime()
{
    return false;
}*/

/**检查是否是非负整数
 * @param $add_quantity
 * @return bool
 */
function checkQuantity($add_quantity)
{
    if(!preg_match('/^[1-9]*[0-9][0-9]*$/',$add_quantity)) {
        return false;
    } else {
        return true;
    }
}

/**检查人数是否大于1
 * @param $add_quantity
 * @return bool
 */
function checkPopulation($add_quantity)
{
    if($add_quantity<1) {
        return false;
    } else {
        return true;
    }
}

/**检查人数限制是否合法
 * @param $rate
 * @return bool
 */
function checkRate($rate)
{
    if(preg_match('/^[1-9]d*|0$/',$rate)) {
       if ($rate<0) {
           return false;
       }  else {
           return true;
       }
    } else {
        return false;
    }
}

/**
 * 检查单价是否合法
 */
function checkGrowPrice($growPrice)
{
    if ($growPrice>0) {
        if (is_numeric($growPrice)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * 检查状态是否合法
 */
function checkStatus($status)
{
    if($status==1 || $status==2 || $status==3 || $status==4 || $status==5 || $status==6) {
        return true;
    } else if ($status==null) {
        return true;
    } else {
        return false;
    }
}

/**
 * 检查重复密码是否相同
 */
function checkRatePassWord()
{
    $password1=I("post.password");
    $password2=I("post.password2");
    if ($password1===$password2) {
        return true;
    } else {
        return false;
    }
}

/**
 * 检查手机验证码
 */
function checkPhoneCode()
{
    $info=$_SESSION['mobile_code'];
    $data=I('post.');
    if ($info['phone']==$data['phone']) {
        if ($info['code']==$data['phoneCode']) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function creatInviteCode($id)
{
    //随机种子
    $seed='abcdefghijklmnopqrstuvwxwzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string=null;
    $string2=null;
    for ($i=0;$i<4;$i++){
        $index=rand(0,51);
        $index2=rand(0,51);
        $char=$seed[$index];
        $char2=$seed[$index2];
        $string.=$char;
        $string2.=$char2;
    }
    return $string.$id.time().$string2;
}
