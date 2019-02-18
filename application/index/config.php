<?php
$public = '';
return [
    'view_replace_str' => [
        '__CSS__' => '/index/css',
        '__STYLE__' => '/index/style',

        '__JS__' => '/index/js',
		
        '__IMG__' => '/index/images',
		
		'__ROOT__'    => $public.'/',

		'__CONSOLE__' => $public.'/static/console',

		'__PLUGIN__'  => $public.'/static/plugin',

		'__HOME__'    => '/index/css',

		'__UPLOADS__'    => $public.'/',
    ],
	'app_trace'              => false,
	'app_debug'				 => false,
	'show_error_msg'		 => false,
    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'index',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],
	'empty_controller'   => 'EmptyController',

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',

        'layout_on'     =>  true,

        'layout_name'   =>  'layout',
    ],
];
