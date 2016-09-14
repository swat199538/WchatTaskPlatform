<?php
return array(
	//'配置项'=>'配置值'
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'shuadang',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'pt_',    // 数据库表前缀
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8

    'SHOW_PAGE_TRACE' => true, // 是否开启调试打印
    'MODULE_ALLOW_LIST' => array('Admin', 'Home','Channel','Wchat'), // 允许使用的模块
    'DEFAULT_MODULE' => 'Home', // 默认模块
    'DEFAULT_CONTROLLER' => 'Index', // 默认控制器
    'DEFAULT_ACTION' => 'index', // 默认方法
    'URL_CASE_INSENSITIVE' => true, // 输写路由时，是否区分大小写。
    'URL_MODEL' => 1, // 使用pathinfo模式
    'URL_ROUTER_ON' => false, // 是否开启路由重写功能
    'URL_ROUTE_RULES' => array(), // 自定义路由规则
    'TMPL_ENGINE_TUYPE' => 'Think|Smarty', // 使用的模板引擎设置
    'TMPL_CACHE_ON' => false, // 是否开启模板缓存
    'DEFAULT_FILTER' => 'trim,removeXss', // I函数接收参数时的过滤

    'TMPL_LAYOUT_ITEM' => '{__CONTENT__}',
    'LAYOUT_ON' => true,
    'LAYOUT_NAME' => 'Layouts/layout',

    'TMPL_TEMPLATE_SUFFIX'  =>  '.html',
    /**
     * 自定义配置
     */
    'HOME_PAGE_NUM' => 10, // 前台分页时每页显示的条数
    'ADMIN_PAGE_NUM' => 10, // 后台分页时每页显示的条数

);