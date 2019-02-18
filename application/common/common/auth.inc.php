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
/*
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
*/
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

$acl_inc[$i]['low_title'] = array('金融模块', 'fa fa-file-text');

// 单页模块

$acl_inc[$i]["low_leve"]["info"] = array("金融方案" => array("index",

    array(

        "列表" => "info1",

        "添加" => "info2",

        "修改" => "info3",

        "删除" => "info4",

    )),

    "data" => array(

        "eq_index" => "info1",

        "eq_create" => "info2",

        "eq_update" => "info3",

        "eq_renewfield" => "info3",

        "eq_delete" => "info4",

    ));
/*
$i++;

$acl_inc[$i]['low_title'] = array('供求平台', 'fa fa-photo');

// 精彩案例

$acl_inc[$i]["low_leve"]["supply"] = array("我要求购" => array("index",
    array(
        "列表" => "supply1",
        "添加" => "supply2",
        "修改" => "supply3",
        "删除" => "supply4",
    )),
    "data" => array(
        "eq_index" => "supply1",
        "eq_wantbuy" => "supply1",
        "eq_create" => "supply2",
        "eq_update" => "supply3",
        "eq_renewfield" => "supply3",
        "eq_delete" => "supply4",
    ));

$acl_inc[$i]["low_leve"]["supplys"] = array("我要供应" => array("index",
    array(
        "列表" => "supplys1",
        "添加" => "supplys2",
        "修改" => "supplys3",
        "删除" => "supplys4",
    )),
    "data" => array(
        "eq_index" => "supplys1",
        "eq_wantbuy" => "supplys1",
        "eq_create" => "supplys2",
        "eq_update" => "supplys3",
        "eq_renewfield" => "supplys3",
        "eq_delete" => "supplys4",
    ));



// 案例分类

$acl_inc[$i]["low_leve"]["product_type"] = array("产品分类" => array("@product",

    array(

        "添加" => "product_type2",

        "修改" => "product_type3",

        "删除" => "product_type4",

    )),

    "data" => array(

        "eq_create" => "product_type2",

        "eq_update" => "product_type3",

        "eq_renewfield" => "product_type3",

        "eq_delete" => "product_type4",

    ));

*/

$i++;

$acl_inc[$i]['low_title'] = array('车辆品牌管理', 'fa fa-globe');


// 认证管理

$acl_inc[$i]["low_leve"]["car_brand"] = array("品牌列表" => array("index",

    array(

        "列表" => "bbc_question1",

        "添加" => "bbc_question2",

        "修改" => "bbc_question3",

        "删除" => "bbc_question4",

    )),

    "data" => array(

        "eq_index" => "bbc_question1",

        "eq_create" => "bbc_question2",

        "eq_update" => "bbc_question3",

        "eq_hot_state" => "bbc_question3",

        "eq_renewfield" => "bbc_question3",

        "eq_delete" => "bbc_question4",
        "eq_update_brand" => "bbc_question4",

    ));
$i++;

$acl_inc[$i]['low_title'] = array('车辆车系管理', 'fa fa-globe');


// 认证管理

$acl_inc[$i]["low_leve"]["car_chexi"] = array("车系列表" => array("index",

    array(

        "列表" => "chexi1",

        "添加" => "chexi2",

        "修改" => "chexi3",

        "删除" => "chexi4",

    )),

    "data" => array(

        "eq_index" => "chexi1",

        "eq_create" => "chexi2",

        "eq_update" => "chexi3",

        "eq_hot_state" => "chexi3",

        "eq_chexi_channel" => "chexi3",
        "eq_update_car_chexi" => "chexi3",
        "eq_renewfield" => "chexi3",

        "eq_delete" => "chexi4",

    ));

$i++;

$acl_inc[$i]['low_title'] = array('车辆类型管理', 'fa fa-list-ul');

// 认证分类

$acl_inc[$i]["low_leve"]["car_type"] = array("类型分类" => array("index",

    array(

        "列表" => "car_type1",

        "添加" => "car_type2",

        "修改" => "car_type3",

        "删除" => "car_type4",

    )),

    "data" => array(

        "eq_index" => "car_type1",

        "eq_create" => "car_type2",

        "eq_update" => "car_type3",

        "eq_renewfield" => "car_type3",

        "eq_delete" => "car_type4",

    ));


$i++;

$acl_inc[$i]['low_title'] = array('车辆模块', 'fa fa-suitcase');

// 职位管理

$acl_inc[$i]["low_leve"]["new_car"] = array("新车管理" => array("index",

    array(

        "列表" => "new_car1",

        "添加" => "new_car2",

        "修改" => "new_car3",

        "删除" => "new_car4",

        "商家商品列表" => "new_car5",

    )),

    "data" => array(

        "eq_index" => "new_car1",

        "eq_create" => "new_car2",

        "eq_update" => "new_car3",

        "eq_renewfield" => "new_car3",

        "eq_delete" => "new_car4",

        "eq_goodslist" => "new_car5",
        "eq_orderlist" => "new_car5",
        "eq_batch_status" => "new_car5",
        "eq_update_new_car" => "new_car5",

    ));


$acl_inc[$i]["low_leve"]["old_car"] = array("二手车管理" => array("index",

    array(

        "列表" => "old_car1",

        "添加" => "old_car2",

        "修改" => "old_car3",

        "删除" => "old_car4",

        "选择二手车属性" => "old_car6",
        "查看详细参数" => "old_car7",

    )),

    "data" => array(

        "eq_index" => "old_car1",

        "eq_create" => "old_car2",

        "eq_update" => "old_car3",

        "eq_renewfield" => "old_car3",
        "eq_renewfieldtwo" => "old_car3",

        "eq_delete" => "old_car4",

        "eq_goodslist" => "old_car5",

        "eq_orderlist" => "old_car5",

        "eq_vincode" => "old_car6",

        "eq_getcarlist" => "old_car6",
        "eq_getchexilist" => "old_car6",
        "eq_showinfo" => "old_car7",

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
//客户风采
$acl_inc[$i]["low_leve"]["kehu"] = array("客户风采" => array("index",

    array(

        "列表" => "kehu1",

        "添加" => "kehu2",

        "修改" => "kehu3",

        "删除" => "kehu4",

    )),

    "data" => array(

        "eq_index" => "kehu1",

        "eq_create" => "kehu2",

        "eq_update" => "kehu3",

        "eq_hotspot" => "kehu3",

        "eq_renewfield" => "kehu3",

        "eq_delete" => "kehu4",

        'eq_updatesort' => "kehu3"

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
$acl_inc[$i]["low_leve"]["chehang"] = array("车行管理" => array("index",

    array(

        "列表" => "chehang1",

        "添加" => "chehang2",

        "修改" => "chehang3",

        "删除" => "chehang4",

    )),

    "data" => array(

        "eq_index" => "chehang1",

        "eq_create" => "chehang2",

        "eq_update" => "chehang3",

        "eq_renewfield" => "chehang3",

        "eq_delete" => "chehang4",

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

$acl_inc[$i]['low_title'] = array('车辆咨询', 'fa fa-cubes');

// 留言列表

$acl_inc[$i]["low_leve"]["message"] = array("新车咨询" => array("index",

    array(

        "列表" => "message1",

        "添加" => "message2",

        "修改" => "message3",

        "删除" => "message4",

    )),

    "data" => array(

        "eq_index" => "message1",

        "eq_create" => "message2",

        "eq_update" => "message3",

        "eq_renewfield" => "message3",

        "eq_delete" => "message4",

    ));

// 留言列表

$acl_inc[$i]["low_leve"]["old_message"] = array("二手车咨询" => array("index",

    array(

        "列表" => "old_message1",

        "添加" => "old_message2",

        "修改" => "old_message3",

        "删除" => "old_message4",

    )),

    "data" => array(

        "eq_index" => "old_message1",

        "eq_create" => "old_message2",

        "eq_update" => "old_message3",

        "eq_renewfield" => "old_message3",

        "eq_delete" => "old_message4",

    ));
$i++;

$acl_inc[$i]['low_title'] = array('卖车管理', 'fa fa-cubes');

// 留言列表

$acl_inc[$i]["low_leve"]["sell_car"] = array("卖车列表" => array("index",

    array(

        "列表" => "sell_car1",

        "添加" => "sell_car2",

        "修改" => "sell_car3",

        "删除" => "sell_car4",

    )),

    "data" => array(

        "eq_index" => "sell_car1",

        "eq_create" => "sell_car2",

        "eq_update" => "sell_car3",

        "eq_renewfield" => "sell_car3",

        "eq_delete" => "sell_car4",

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