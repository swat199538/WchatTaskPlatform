<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

// 定义应用目录
define('APP_PATH','./Application/');

// 自定义资源路径
define('SITE_URL', '/project2/parttime/'); // http://question.msgyun.com/
define('ASSETS_URL', SITE_URL . 'Public');
define('HOME_CSS_URL', ASSETS_URL . '/home/css/');
define('ADMIN_CSS_URL', ASSETS_URL . '/admin/css/');
define('HOME_JS_URL', ASSETS_URL . '/home/js/');
define('ADMIN_JS_URL', ASSETS_URL . '/admin/js/');
define('HOME_PLUGIN_URL', ASSETS_URL . '/home/plugins/');
define('ADMIN_PLUGIN_URL', ASSETS_URL . '/admin/plugins/');
define('HOME_IMG_URL', ASSETS_URL . '/home/img/');
define('ADMIN_IMG_URL', ASSETS_URL . '/admin/img/');
define('HOME_URL', ASSETS_URL . '/home/');
define('REGEDIT_URL', 'http://localhost/project2/parttime/index.php/Wchat/Home/regedit');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单