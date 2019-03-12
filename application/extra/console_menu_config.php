<?php

// 配置文件
return [
    [
        ['控制台', 'console/dashboard/index', 'fa fa-home', ['dashboard']],
    ],
    [
        ['权限管理', 'fa fa-picture-o', ['auth_rule', 'auth_group', 'auth_manager']],
        [
            ['权限规则', 'console/auth_rule/index', ['auth_rule']],
            ['权限角色', 'console/auth_group/index', ['auth_group']],
            ['管理员列表', 'console/auth_manager/index', ['auth_manager']],
        ]
    ],
    [
        ['用户管理', 'fa fa-users', ['membercar', 'membergood', 'feedback', 'redbag']],
        [
            ['车主列表', 'console/membercar/index', ['membercar']],
            ['货主列表', 'console/membergood/index', ['membergood']],
            ['意见反馈', 'console/feedback/index', ['feedback']],
            ['红包管理', 'console/redbag/index', ['redbag']],
        ]
    ],
    [
        ['订单管理', 'fa fa-list', ['goodsource', 'carsource']],
        [
            ['车源列表', 'console/carsource/index', ['carsource']],
            // ['成交车源', 'console/carsource/deal', ['carsource']],
            ['货源列表', 'console/goodsource/index', ['goodsource']],
            // ['成交货源', 'console/goodsource/deal', ['goodsource']],
        ]
    ],
    [
        ['保证金管理', 'fa fa-list', ['carmoney', 'goodmoney']],
        [
            ['车主保证金', 'console/carmoney/index', ['carmoney']],
            ['货主保证金', 'console/goodmoney/index', ['goodmoney']],
        ]
    ],

    [
        ['提现申请', 'fa fa-list', ['carwithdraw', 'goodwithdraw']],
        [
            ['车主提现', 'console/carwithdraw/index', ['carwithdraw']],
            ['货主提现', 'console/goodwithdraw/index', ['goodwithdraw']],
        ]
    ],
    [
        ['聊天群管理', 'fa fa-list', ['chatgroup', 'chatroom', 'chataccount']],
        [
            ['聊天群列表', 'console/chatgroup/index', ['chatgroup', 'chatroom']],
            ['聊天账号', 'console/chataccount/index', ['chataccount']],
        ]
    ],
    [
        ['车辆管理', 'fa fa-list', ['carheight', 'carprefix', 'cartype']],
        [
            ['车长列表', 'console/carheight/index', ['carheight']],
            /* ['车牌列表', 'console/carprefix/index', ['carprefix']],*/
            ['车型列表', 'console/cartype/index', ['cartype']],
        ]
    ],
    [
        ['推广管理', 'fa fa-list', ['banner', 'notice']],
        [
            ['轮播列表', 'console/banner/index', ['banner']],
            ['系统消息', 'console/notice/index', ['notice']],
        ]
    ],

    [
        ['系统设置', 'fa fa-cog', ['baksql', 'webinfo', 'system', 'region', 'log']],
        [
            ['web页管理', 'console/webinfo/index', ['webinfo']],
            ['数据库备份', 'console/baksql/index', ['baksql']],
            ['地区管理', 'console/region/province', ['region']],
            ['配置管理', 'console/system/index', ['system']],
            ['操作日志', 'console/log/index', ['log']],
        ]
    ],
];