<?php

/*array(菜单名，菜单样式，是否显示)*/

//error_reporting(E_ALL);

/*

$acl_inc[$i]['low_leve']['global']  global是model

每个action前必须添加eq_前缀'eq_websetting'  => 'at1','at1'表示唯一标志,可独自命名,eq_后面跟的action必须统一小写

*/

$acl_inc = array();

$i = 0;

$acl_inc[$i]['low_title'] = array('控制台', 'fa fa-home', 1);

$acl_inc[$i]['low_leve']['dashboard'] = array("控制台" => array('index',

    array(

        "列表" => 'board',

    )

),

    "data" => array(

        //控制台

        'eq_index' => 'board',

    )

);

$i++;

$acl_inc[$i]['low_title'] = array('全局设置', 'fa fa-cog',1);

$acl_inc[$i]['low_leve']['system'] = array("配置管理" => array('index',

    array(

        "列表" => 'at1',

        "添加" => 'at2',

        "修改" => 'at3',

    )),

    "data" => array(

        //配置管理

        'eq_index' => 'at1',

        "eq_create" => 'at2',

        "eq_whole" => 'at3',

    )

);

$i++;

$acl_inc[$i]['low_title'] = array('地区模块', 'fa fa-globe');

// 省管理

$acl_inc[$i]["low_leve"]["cn_prov"] = array("省管理" => array("index",

    array(

        "列表" => "cn_prov1",

        "添加" => "cn_prov2",

        "修改" => "cn_prov3",

        "删除" => "cn_prov4",

    )),

    "data" => array(

        "eq_index" => "cn_prov1",

        "eq_create" => "cn_prov2",

        "eq_update" => "cn_prov3",

        "eq_renewfield" => "cn_prov3",

        "eq_delete" => "cn_prov4",

    ));

// 市管理

$acl_inc[$i]["low_leve"]["cn_city"] = array("市管理" => array("index",

    array(

        "列表" => "cn_city1",

        "添加" => "cn_city2",

        "修改" => "cn_city3",

        "删除" => "cn_city4",

    )),

    "data" => array(

        "eq_index" => "cn_city1",

        "eq_create" => "cn_city2",

        "eq_update" => "cn_city3",

        "eq_renewfield" => "cn_city3",

        "eq_delete" => "cn_city4",

    ));

// 区管理

$acl_inc[$i]["low_leve"]["cn_area"] = array("区管理" => array("index",

    array(

        "列表" => "cn_area1",

        "添加" => "cn_area2",

        "修改" => "cn_area3",

        "删除" => "cn_area4",

    )),

    "data" => array(

        "eq_index" => "cn_area1",

        "eq_create" => "cn_area2",

        "eq_update" => "cn_area3",

        "eq_renewfield" => "cn_area3",

        "eq_delete" => "cn_area4",

    ));

$i++;

$acl_inc[$i]['low_title'] = array('管理模块', 'fa fa-user');

$acl_inc[$i]['low_leve']['manager'] = array("管理员管理" => array('index',

    array(

        "列表" => 'man1',

        "添加" => 'man2',

        "修改" => 'man3',

        "删除" => 'man4',

    )),

    "data" => array(

        //配置管理

        'eq_index' => 'man1',

        'eq_create' => 'man2',

        'eq_update' => 'man3',

        'eq_renewfield' => 'man3',

        'eq_delete' => 'man4',

    )

);

// 管理组管理

$acl_inc[$i]["low_leve"]["authgroup"] = array("管理组管理" => array("index",

    array(

        "列表" => "authgroup1",

        "添加" => "authgroup2",

        "修改" => "authgroup3",

        "删除" => "authgroup4",

        "权限" => "authgroup5",

    )),

    "data" => array(

        "eq_index" => "authgroup1",

        "eq_create" => "authgroup2",

        "eq_update" => "authgroup3",

        "eq_renewfield" => "authgroup3",

        "eq_delete" => "authgroup4",

        "eq_setup" => "authgroup5",

    ));
$i++;

$acl_inc[$i]['low_title'] = array('报表', 'fa fa-photo');

// 报表

$acl_inc[$i]["low_leve"]["finance_statement"] = array("财务表报" => array("index",
    array(
        "列表" => "finance_statement1",
        "添加" => "finance_statement2",
        "修改" => "finance_statement3",
        "删除" => "finance_statement4",
    )),
    "data" => array(
        "eq_index" => "finance_statement1",
        "eq_create" => "finance_statement",
        "eq_update" => "finance_statement3",
        "eq_renewfield" => "finance_statement3",
        "eq_delete" => "finance_statement4",
    ));

$acl_inc[$i]["low_leve"]["agent_statement"] = array("代理商表报" => array("index",
    array(
        "列表" => "agent_statement1",
        "添加" => "agent_statement2",
        "修改" => "agent_statement3",
        "删除" => "agent_statement4",
    )),
    "data" => array(
        "eq_index"      => "agent_statement1",
        "eq_create"     => "agent_statement2",
        "eq_update"     => "agent_statement3",
        "eq_renewfield" => "agent_statement3",
        "eq_delete"     => "agent_statement4",
    ));


$i++;

$acl_inc[$i]['low_title'] = array('消息', 'fa fa-photo');

// 消息队列

$acl_inc[$i]["low_leve"]["new_message"] = array("新订单消息" => array("index",
    array(
        "列表" => "new_message1",
        "添加" => "new_message2",
        "修改" => "new_message3",
        "删除" => "new_message4",
    )),
    "data" => array(
        "eq_index" => "new_message1",
        "eq_wantbuy" => "new_message1",
        "eq_create" => "new_message2",
        "eq_update" => "new_message3",
        "eq_renewfield" => "new_message3",
        "eq_delete" => "new_message4",
    ));

$acl_inc[$i]["low_leve"]["apply_message"] = array("结算申请消息" => array("index",
    array(
        "列表" => "apply_message1",
        "添加" => "apply_message2",
        "修改" => "apply_message3",
        "删除" => "apply_message4",
    )),
    "data" => array(
        "eq_index" => "apply_message1",
        "eq_wantbuy" => "apply_message1",
        "eq_create" => "apply_message2",
        "eq_update" => "apply_message3",
        "eq_renewfield" => "apply_message3",
        "eq_delete" => "apply_message4",
    ));


$acl_inc[$i]["low_leve"]["send_message"] = array("提示发货消息" => array("index",

    array(

        "添加" => "send_message1",

        "修改" => "send_message2",

        "重定向" => "send_message3",

        "删除" => "send_message4",

    )),

    "data" => array(

        "eq_index" => "send_message1",

        "eq_update" => "send_message2",

        "eq_renewfield" => "send_message3",

        "eq_delete" => "send_message4",

    ));

//

$acl_inc[$i]["low_leve"]["message"] = array("申请加盟消息" => array("index",

    array(

        "列表" => "message1",

        "添加" => "message2",

        "修改" => "message3",

        "删除" => "message4",

    )),

    "data" => array(

        "eq_index"      => "message1",

        "eq_create"     => "message2",

        "eq_update"     => "message3",

        "eq_renewfield" => "message3",

        "eq_delete"     => "message4",

    ));

$acl_inc[$i]["low_leve"]["user_message"] = array("会员升级消息" => array("index",

    array(

        "列表" => "user_message1",

        "添加" => "user_message2",

        "修改" => "user_message3",

        "删除" => "user_message4",

    )),

    "data" => array(

        "eq_index" => "user_message1",

        "eq_create" => "user_message2",

        "eq_update" => "user_message3",

        "eq_renewfield" => "user_message3",

        "eq_delete" => "user_message4",

    ));

$i++;

$acl_inc[$i]['low_title'] = array('账号模块', 'fa fa-users');

// 用户管理

$acl_inc[$i]["low_leve"]["user"] = array("会员管理" => array("index",

    array(

        "列表" => "user1",

        "添加" => "user2",

        "修改" => "user3",

        "删除" => "user4",

        "认证" => "user5",

        "积分记录" => "user6",

    )),

    "data" => array(

        "eq_index" => "user1",

        "eq_create" => "user2",

        "eq_update" => "user3",

        "eq_renewfield" => "user3",

        "eq_delete" => "user4",

        "eq_approve" => "user5",

        "eq_integral_info" => "user6",

    ));
// 用户管理

$acl_inc[$i]["low_leve"]["user_tmp"] = array("新增会员管理" => array("index",

    array(

        "列表" => "user_tmp1",

        "添加" => "user_tmp2",

        "修改" => "user_tmp3",

        "删除" => "user_tmp4",

        "认证" => "user_tmp5",

        "积分记录" => "user_tmp6",

    )),

    "data" => array(

        "eq_index" => "user_tmp1",

        "eq_create" => "user_tmp2",

        "eq_update" => "user_tmp3",

        "eq_renewfield" => "user_tmp3",

        "eq_delete" => "user_tmp4",

        "eq_approve" => "user_tmp5",

        "eq_integral_info" => "user_tmp6",

    ));

$i++;

$acl_inc[$i]['low_title'] = array('商品管理', 'fa fa-cubes');

// 商品分类

$acl_inc[$i]["low_leve"]["goods_type"] = array("商品分类" => array("index",

    array(

        "列表" => "goods_type1",

        "添加" => "goods_type2",

        "修改" => "goods_type3",

        "删除" => "goods_type4",

    )),

    "data" => array(

        "eq_index" => "goods_type1",

        "eq_create" => "goods_type2",

        "eq_update" => "goods_type3",

        "eq_renewfield" => "goods_type3",

        "eq_delete" => "goods_type4",

    ));

// 商品模块

$acl_inc[$i]["low_leve"]["goods"] = array("商品管理" => array("index",

    array(

        "列表" => "goods1",

        "添加" => "goods2",

        "修改" => "goods3",

        "删除" => "goods4",

    )),

    "data" => array(

        "eq_index" => "goods1",

        "eq_show" => "goods1",

        "eq_create" => "goods2",

        "eq_update" => "goods3",

        "eq_renewfield" => "goods3",

        "eq_getattr" => "goods3",

        "eq_delete" => "goods4",

    ));

$i++;

$acl_inc[$i]['low_title'] = array('订单管理', 'fa fa-bell-o');

// 认证分类

$acl_inc[$i]["low_leve"]["to_paid"] = array("待支付订单" => array("index",

    array(

        "列表" => "to_paid1",

        "添加" => "to_paid2",

        "修改" => "to_paid3",

        "删除" => "to_paid4",

    )),

    "data" => array(

        "eq_index" => "to_paid1",

        "eq_create" => "to_paid2",

        "eq_update" => "to_paid3",

        "eq_renewfield" => "to_paid3",

        "eq_delete" => "to_paid4",

        "eq_achieve" => "to_paid3",

        "eq_reject_tk" => "reject3",

        "eq_reject_th" => "reject3",

        "eq_fahuo" => "reject3",

    ));

$acl_inc[$i]["low_leve"]["deliver"] = array("待发货订单" => array("index",

    array(

        "列表" => "deliver1",

        "添加" => "deliver2",

        "修改" => "deliver3",

        "删除" => "deliver4",

    )),

    "data" => array(

        "eq_index" => "deliver1",

        "eq_create" => "deliver2",

        "eq_update" => "deliver3",

        "eq_renewfield" => "deliver3",

        "eq_delete" => "deliver4",

        "eq_achieve" => "deliver3",

        "eq_reject_tk" => "reject3",

        "eq_reject_th" => "reject3",

        "eq_fahuo" => "reject3",

    ));
$acl_inc[$i]["low_leve"]["online"] = array("线下付款订单" => array("index",

    array(

        "列表" => "online1",

        "添加" => "online2",

        "修改" => "online3",

        "删除" => "online4",

    )),

    "data" => array(

        "eq_index" => "online1",

        "eq_create" => "online2",

        "eq_update" => "online3",

        "eq_renewfield" => "online3",

        "eq_delete" => "online4",

        "eq_achieve" => "online3",

        "eq_reject_tk" => "reject3",

        "eq_reject_th" => "reject3",

        "eq_fahuo" => "reject3",

    ));
$acl_inc[$i]["low_leve"]["refund_money"] = array("待退款订单" => array("index",

    array(

        "列表" => "refund_money1",

        "添加" => "refund_money2",

        "修改" => "refund_money3",

        "删除" => "refund_money4",

    )),

    "data" => array(

        "eq_index" => "refund_money1",

        "eq_create" => "refund_money2",

        "eq_update" => "refund_money3",

        "eq_renewfield" => "refund_money3",

        "eq_delete" => "refund_money4",

        "eq_achieve" => "refund_money3",

        "eq_reject_tk" => "reject3",

        "eq_reject_th" => "reject3",

        "eq_fahuo" => "reject3",

    ));
$acl_inc[$i]["low_leve"]["refund_good"] = array("待退货订单" => array("index",

    array(

        "列表" => "refund_good1",

        "添加" => "refund_good2",

        "修改" => "refund_good3",

        "删除" => "refund_good4",

    )),

    "data" => array(

        "eq_index" => "refund_good1",

        "eq_create" => "refund_good2",

        "eq_update" => "refund_good3",

        "eq_renewfield" => "refund_good3",

        "eq_delete" => "refund_good4",

        "eq_achieve" => "refund_good3",

        "eq_reject_tk" => "reject3",

        "eq_reject_th" => "reject3",

        "eq_fahuo" => "reject3",

    ));
$acl_inc[$i]["low_leve"]["other"] = array("其他订单" => array("index",

    array(

        "列表" => "other1",

        "添加" => "other2",

        "修改" => "other3",

        "删除" => "other4",

    )),

    "data" => array(

        "eq_index" => "other1",

        "eq_create" => "other2",

        "eq_update" => "other3",

        "eq_renewfield" => "other3",

        "eq_delete" => "other4",

        "eq_achieve" => "other3",

        "eq_reject_tk" => "reject3",

        "eq_reject_th" => "reject3",

        "eq_fahuo" => "reject3",

    ));
$acl_inc[$i]["low_leve"]["orders"] = array("全部订单" => array("index",

    array(

        "列表" => "orders1",

        "添加" => "orders2",

        "修改" => "orders3",

        "删除" => "orders4",

    )),

    "data" => array(

        "eq_index" => "orders1",

        "eq_create" => "orders2",

        "eq_update" => "orders3",

        "eq_renewfield" => "orders3",

        "eq_delete" => "orders4",

        "eq_achieve" => "orders3",

        "eq_reject_tk" => "reject3",

        "eq_reject_th" => "reject3",

        "eq_fahuo" => "reject3",

    ));


$i++;

$acl_inc[$i]['low_title'] = array('功能模块', 'fa fa-delicious');

// 导航管理

$acl_inc[$i]["low_leve"]["nav"] = array("文章管理" => array("index",

    array(

        "列表" => "nav1",

        "添加" => "nav2",

        "修改" => "nav3",

        "删除" => "nav4",

    )),

    "data" => array(

        "eq_index" => "nav1",

        "eq_create" => "nav2",

        "eq_update" => "nav3",

        "eq_hotspot" => "nav3",

        "eq_renewfield" => "nav3",

        "eq_delete" => "nav4",

        'eq_updatesort' => "nav3"

    ));

// banner管理

$acl_inc[$i]["low_leve"]["banner"] = array("banner管理" => array("index",

    array(

        "列表" => "banner1",

        "添加" => "banner2",

        "修改" => "banner3",

        "删除" => "banner4",

    )),

    "data" => array(

        "eq_index" => "banner1",

        "eq_create" => "banner2",

        "eq_update" => "banner3",

        "eq_renewfield" => "banner3",

        "eq_delete" => "banner4",

    ));


/*常见问题*/
/*
$acl_inc[$i]["low_leve"]["faq"] = array("常见问题" => array("index",

    array(

        "列表" => "faq1",

        "添加" => "faq2",

        "修改" => "faq3",

        "删除" => "faq4",

    )),

    "data" => array(

        "eq_index" => "faq1",

        "eq_create" => "faq2",

        "eq_update" => "faq3",

        "eq_renewfield" => "faq3",

        "eq_delete" => "faq4",

    ));*/

// 清洁管理

$acl_inc[$i]["low_leve"]["clear"] = array("清洁管理" => array("index",

    array(

        "列表" => "clear1",

    )),

    "data" => array(

        "eq_index" => "clear1",

    ));

// banner分类

$acl_inc[$i]["low_leve"]["banner_type"] = array("banner分类" => array("@banner",

    array(

        "添加" => "banner_type2",

        "修改" => "banner_type3",

        "删除" => "banner_type4",

    )),

    "data" => array(

        "eq_create" => "banner_type2",

        "eq_update" => "banner_type3",

        "eq_renewfield" => "banner_type3",

        "eq_delete" => "banner_type4",

    ));


$i++;

$acl_inc[$i]['low_title'] = array('线下店铺管理', 'fa fa-cubes');

// 留言列表

$acl_inc[$i]["low_leve"]["store"] = array("店铺列表" => array("index",

    array(

        "列表" => "store1",

        "添加" => "store2",

        "修改" => "store3",

        "删除" => "store4",

    )),

    "data" => array(

        "eq_index" => "store1",

        "eq_create" => "store2",

        "eq_update" => "store3",

        "eq_renewfield" => "store3",

        "eq_delete" => "store4",
        "eq_get_city" => "store4",
        "eq_get_area" => "store4",
        "eq_check_mobile" => "store4",

    ));
$i++;

$acl_inc[$i]['low_title'] = array('代理商管理', 'fa fa-cubes');

// 留言列表

$acl_inc[$i]["low_leve"]["agent"] = array("代理商列表" => array("index",

    array(

        "列表" => "agent1",

        "添加" => "agent2",

        "修改" => "agent3",

        "删除" => "agent4",

    )),

    "data" => array(

        "eq_index" => "agent1",

        "eq_create" => "agent2",

        "eq_update" => "agent3",

        "eq_renewfield" => "agent3",

        "eq_delete" => "agent4",
        "eq_get_city" => "agent4",
        "eq_get_area" => "agent4",
        "eq_check_mobile" => "agent4",

    ));
/*

$i++;

$acl_inc[$i]['low_title'] = array('工具', 'fa fa-wrench');

$acl_inc[$i]['low_leve']['formbuilder'] = array("表单构建器" => array('index',

    array(

        "列表" => 'build',

    )),

    "data" => array(

        'eq_index' => 'build',

    )

);

$acl_inc[$i]['low_leve']['generate'] = array("代码生成器" => array('index',

    array(

        "列表" => 'gener1',

    )),

    "data" => array(

        'eq_index' => 'gener1',

        'eq_run' => 'gener1',

        'eq_cmd' => 'gener1',

    )

);



// 解决方案

$acl_inc[$i]["low_leve"]["solution"] = array("解决方案" => array("index",

    array(

        "列表" => "solution1",

        "添加" => "solution2",

        "修改" => "solution3",

        "删除" => "solution4",

    )),

    "data" => array(

        "eq_index" => "solution1",

        "eq_create" => "solution2",

        "eq_update" => "solution3",

        "eq_renewfield" => "solution3",

        "eq_delete" => "solution4",

    ));
*/