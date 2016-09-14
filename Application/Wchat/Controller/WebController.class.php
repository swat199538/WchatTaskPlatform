<?php
/**
 * Created by PhpStorm.
 * User: wangl
 * Date: 2016/8/11
 * Time: 14:39
 */
namespace Wchat\Controller;

use Components\Base\WchatController;

class WebController extends WchatController
{
    public function index()
    {
        $json=$this->getJsonData();

        $userInfo=$this->getJsonUser($json['access_token'],$json['openid']);

        dd($userInfo);

    }

    private function getJsonData()
    {
        $code=I('get.code');
        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx3bb18b674d365a35&secret=3ab7ad2728ef4a3b26b8e299a9eb6e99&code=$code&grant_type=authorization_code";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $data=curl_exec($ch);
        curl_close($ch);
        return $data=json_decode($data,true);
    }

    /**获取用户微信资料JSON格式
     * @param $access_token
     * @param $openid
     * @return mixed
     */
    private function getJsonUser($access_token,$openid)
    {
        $url="https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN ";

        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $data=curl_exec($ch);
        curl_close($ch);
        return $data=json_decode($data,true);

    }

}