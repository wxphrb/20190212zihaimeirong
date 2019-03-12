<?php

// 配置文件
return [
    'AUTH_ON' => false,                               // 认证开关
    'AUTH_TYPE' => 1,                                // 认证方式，1为时时认证；2为登录认证。
    'AUTH_GROUP' => 'ren_auth_group',                 // 用户组数据表名
    'AUTH_GROUP_ACCESS' => 'ren_auth_group_access',   // 用户组明细表
    'AUTH_GROUP_RULE' => 'ren_auth_group_rule',       // 用户组明细表
    'AUTH_RULE' => 'ren_auth_rule',                   // 权限规则表
    'AUTH_USER' => 'ren_auth_manager',                     // 用户信息表
];