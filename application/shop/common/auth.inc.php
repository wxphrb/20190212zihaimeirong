<?php
/*array(菜单名，菜单样式，是否显示)*/
//error_reporting(E_ALL);
/*
$acl_inc[$i]['low_leve']['global']  global是model
每个action前必须添加eq_前缀'eq_websetting'  => 'at1','at1'表示唯一标志,可独自命名,eq_后面跟的action必须统一小写
*/
$acl_inc =  array();
$i=0;

$acl_inc[$i]['low_title'] = array('控制台','fa fa-home',1);
$acl_inc[$i]['low_leve']['dashboard']= array( "控制台" =>array('index',
											array(
												 "列表" 		=> 'board',
												)
											),
										   "data" => array(
										   		//控制台
												'eq_index'  => 'board',
											)
							);
$i++;
$acl_inc[$i]['low_title'] =  array('全局设置','fa fa-cog');
$acl_inc[$i]['low_leve']['system']= array( "配置管理" =>array('index',
                                              array(
												 "列表" 		=> 'at1',
												 "添加" 		=> 'at2',
											  )),
										   "data" => array(
										   		//配置管理
												'eq_index'  => 'at1',
												"eq_create" 		=> 'at2',
											)
							);
$i++;
$acl_inc[$i]['low_title'] =  array('地区模块','fa fa-globe');
// 省管理
$acl_inc[$i]["low_leve"]["cn_prov"]= array( "省管理" =>array("index",
                                        array(
                                                "列表"       => "cn_prov1",
                                                "添加"       => "cn_prov2",
                                                "修改"       => "cn_prov3",
                                                "删除"       => "cn_prov4",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "cn_prov1",
                                                "eq_create"       => "cn_prov2",
                                                "eq_update"       => "cn_prov3",
                                                "eq_renewfield"   => "cn_prov3",
                                                "eq_delete"       => "cn_prov4",
                                        ));
// 市管理
$acl_inc[$i]["low_leve"]["cn_city"]= array( "市管理" =>array("index",
                                        array(
                                                "列表"       => "cn_city1",
                                                "添加"       => "cn_city2",
                                                "修改"       => "cn_city3",
                                                "删除"       => "cn_city4",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "cn_city1",
                                                "eq_create"       => "cn_city2",
                                                "eq_update"       => "cn_city3",
                                                "eq_renewfield"   => "cn_city3",
                                                "eq_delete"       => "cn_city4",
                                        ));
// 区管理
$acl_inc[$i]["low_leve"]["cn_area"]= array( "区管理" =>array("index",
                                        array(
                                                "列表"       => "cn_area1",
                                                "添加"       => "cn_area2",
                                                "修改"       => "cn_area3",
                                                "删除"       => "cn_area4",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "cn_area1",
                                                "eq_create"       => "cn_area2",
                                                "eq_update"       => "cn_area3",
                                                "eq_renewfield"   => "cn_area3",
                                                "eq_delete"       => "cn_area4",
                                        ));
$i++;
$acl_inc[$i]['low_title'] =  array('管理模块','fa fa-user');
$acl_inc[$i]['low_leve']['manager']= array( "管理员管理" =>array('index',
                                              array(
												 "列表" 		=> 'man1',
												 "添加" 		=> 'man2',
												 "修改" 		=> 'man3',
												 "删除" 		=> 'man4',
											  )),
										   "data" => array(
										   		//配置管理
												'eq_index'  => 'man1',
												'eq_create'  => 'man2',
												'eq_update'  => 'man3',
												'eq_renewfield'  => 'man3',
												'eq_delete'  => 'man4',
											)
							);
// 管理组管理
$acl_inc[$i]["low_leve"]["authgroup"]= array( "管理组管理" =>array("index",
                                        array(
                                                "列表"       => "authgroup1",
                                                "添加"       => "authgroup2",
                                                "修改"       => "authgroup3",
                                                "删除"       => "authgroup4",
                                                "权限"       => "authgroup5",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "authgroup1",
                                                "eq_create"       => "authgroup2",
                                                "eq_update"       => "authgroup3",
                                                "eq_renewfield"   => "authgroup3",
                                                "eq_delete"       => "authgroup4",
                                                "eq_setup"        => "authgroup5",
                                        ));

$i++;
$acl_inc[$i]['low_title'] =  array('单页模块','fa fa-file-text');
// 单页模块
$acl_inc[$i]["low_leve"]["info"]= array( "单页管理" =>array("index",
                                        array(
                                                "列表"       => "info1",
                                                "添加"       => "info2",
                                                "修改"       => "info3",
                                                "删除"       => "info4",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "info1",
                                                "eq_create"       => "info2",
                                                "eq_update"       => "info3",
                                                "eq_renewfield"   => "info3",
                                                "eq_delete"       => "info4",
                                        ));
$i++;
$acl_inc[$i]['low_title'] =  array('产品模块','fa fa-photo');
// 精彩案例
$acl_inc[$i]["low_leve"]["product"]= array( "产品管理" =>array("index",
                                        array(
                                                "列表"       => "product1",
                                                "添加"       => "product2",
                                                "修改"       => "product3",
                                                "删除"       => "product4",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "product1",
                                                "eq_create"       => "product2",
                                                "eq_update"       => "product3",
                                                "eq_renewfield"   => "product3",
                                                "eq_delete"       => "product4",
                                        ));
// 案例分类
$acl_inc[$i]["low_leve"]["product_type"]= array( "产品分类" =>array("@product",
                                        array(
                                                "添加"       => "product_type2",
                                                "修改"       => "product_type3",
                                                "删除"       => "product_type4",
                                        )),
                                        "data" =>array(
                                                "eq_create"       => "product_type2",
                                                "eq_update"       => "product_type3",
                                                "eq_renewfield"   => "product_type3",
                                                "eq_delete"       => "product_type4",
                                        ));
$i++;
$acl_inc[$i]['low_title'] =  array('新闻模块','fa fa-list-ul');
// 新闻分类
$acl_inc[$i]["low_leve"]["infoclass"]= array( "新闻分类" =>array("index",
                                        array(
                                                "列表"       => "infoclass1",
                                                "添加"       => "infoclass2",
                                                "修改"       => "infoclass3",
                                                "删除"       => "infoclass4",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "infoclass1",
                                                "eq_create"       => "infoclass2",
                                                "eq_update"       => "infoclass3",
                                                "eq_renewfield"   => "infoclass3",
                                                "eq_delete"       => "infoclass4",
                                        ));
// 新闻管理
$acl_inc[$i]["low_leve"]["infolist"]= array( "新闻管理" =>array("index",
                                        array(
                                                "列表"       => "infolist1",
                                                "添加"       => "infolist2",
                                                "修改"       => "infolist3",
                                                "删除"       => "infolist4",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "infolist1",
                                                "eq_create"       => "infolist2",
                                                "eq_update"       => "infolist3",
                                                "eq_renewfield"   => "infolist3",
                                                "eq_delete"       => "infolist4",
                                        ));

$i++;
$acl_inc[$i]['low_title'] =  array('会员模块','fa fa-users');
// 用户管理
$acl_inc[$i]["low_leve"]["user"]= array( "会员管理" =>array("index",
                                        array(
                                                "列表"       => "user1",
                                                "添加"       => "user2",
                                                "修改"       => "user3",
                                                "删除"       => "user4",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "user1",
                                                "eq_create"       => "user2",
                                                "eq_update"       => "user3",
                                                "eq_renewfield"   => "user3",
                                                "eq_delete"       => "user4",
                                        ));
$i++;
$acl_inc[$i]['low_title'] =  array('招聘模块','fa fa-suitcase');
// 职位管理
$acl_inc[$i]["low_leve"]["job"]= array( "职位管理" =>array("index",
                                        array(
                                                "列表"       => "job1",
                                                "添加"       => "job2",
                                                "修改"       => "job3",
                                                "删除"       => "job4",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "job1",
                                                "eq_create"       => "job2",
                                                "eq_update"       => "job3",
                                                "eq_renewfield"   => "job3",
                                                "eq_delete"       => "job4",
                                        ));
$i++;
$acl_inc[$i]['low_title'] =  array('功能模块','fa fa-delicious');
// 导航管理
$acl_inc[$i]["low_leve"]["nav"]= array( "导航管理" =>array("index",
                                        array(
                                                "列表"       => "nav1",
                                                "添加"       => "nav2",
                                                "修改"       => "nav3",
                                                "删除"       => "nav4",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "nav1",
                                                "eq_create"       => "nav2",
                                                "eq_update"       => "nav3",
                                                "eq_renewfield"   => "nav3",
                                                "eq_delete"       => "nav4",
                                        ));
// 留言列表
$acl_inc[$i]["low_leve"]["message"]= array( "留言列表" =>array("index",
                                        array(
                                                "列表"       => "message1",
                                                "添加"       => "message2",
                                                "修改"       => "message3",
                                                "删除"       => "message4",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "message1",
                                                "eq_create"       => "message2",
                                                "eq_update"       => "message3",
                                                "eq_renewfield"   => "message3",
                                                "eq_delete"       => "message4",
                                        ));
// 友情链接
$acl_inc[$i]["low_leve"]["link"]= array( "友情链接" =>array("index",
                                        array(
                                                "列表"       => "link1",
                                                "添加"       => "link2",
                                                "修改"       => "link3",
                                                "删除"       => "link4",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "link1",
                                                "eq_create"       => "link2",
                                                "eq_update"       => "link3",
                                                "eq_renewfield"   => "link3",
                                                "eq_delete"       => "link4",
                                        ));
// banner管理
$acl_inc[$i]["low_leve"]["banner"]= array( "banner管理" =>array("index",
                                        array(
                                                "列表"       => "banner1",
                                                "添加"       => "banner2",
                                                "修改"       => "banner3",
                                                "删除"       => "banner4",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "banner1",
                                                "eq_create"       => "banner2",
                                                "eq_update"       => "banner3",
                                                "eq_renewfield"   => "banner3",
                                                "eq_delete"       => "banner4",
                                        ));
// 清洁管理
$acl_inc[$i]["low_leve"]["clear"]= array( "清洁管理" =>array("index",
                                        array(
                                                "列表"       => "clear1",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "clear1",
                                        ));
// banner分类
$acl_inc[$i]["low_leve"]["banner_type"]= array( "banner分类" =>array("@banner",
                                        array(
                                                "添加"       => "banner_type2",
                                                "修改"       => "banner_type3",
                                                "删除"       => "banner_type4",
                                        )),
                                        "data" =>array(
                                                "eq_create"       => "banner_type2",
                                                "eq_update"       => "banner_type3",
                                                "eq_renewfield"   => "banner_type3",
                                                "eq_delete"       => "banner_type4",
                                        ));
$i++;
$acl_inc[$i]['low_title'] =  array('商品订单','fa fa-cubes');
// 商品分类
$acl_inc[$i]["low_leve"]["goods_type"]= array( "商品分类" =>array("index",
                                        array(
                                                "列表"       => "goods_type1",
                                                "添加"       => "goods_type2",
                                                "修改"       => "goods_type3",
                                                "删除"       => "goods_type4",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "goods_type1",
                                                "eq_create"       => "goods_type2",
                                                "eq_update"       => "goods_type3",
                                                "eq_renewfield"   => "goods_type3",
                                                "eq_delete"       => "goods_type4",
                                        ));
// 商品模块
$acl_inc[$i]["low_leve"]["goods"]= array( "商品管理" =>array("index",
                                        array(
                                                "列表"       => "goods1",
                                                "添加"       => "goods2",
                                                "修改"       => "goods3",
                                                "删除"       => "goods4",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "goods1",
                                                "eq_show"         => "goods1",
                                                "eq_create"       => "goods2",
                                                "eq_update"       => "goods3",
                                                "eq_renewfield"   => "goods3",
                                                "eq_getattr"      => "goods3",
                                                "eq_delete"       => "goods4",
                                        ));
$i++;
$acl_inc[$i]['low_title'] =  array('工具','fa fa-wrench');
$acl_inc[$i]['low_leve']['formbuilder']= array( "表单构建器" =>array('index',
                                              array(
                                                 "列表"       => 'build',
                                              )),
                                           "data" => array(
                                                'eq_index'  => 'build',
                                            )
                            );
$acl_inc[$i]['low_leve']['generate']= array( "代码生成器" =>array('index',
                                              array(
                                                 "列表"       => 'gener1',
                                              )),
                                           "data" => array(
                                                'eq_index'  => 'gener1',
                                                'eq_run'  => 'gener1',
                                                'eq_cmd'  => 'gener1',
                                            )
                            );

// 解决方案
$acl_inc[$i]["low_leve"]["solution"]= array( "解决方案" =>array("index",
                                        array(
                                                "列表"       => "solution1",
                                                "添加"       => "solution2",
                                                "修改"       => "solution3",
                                                "删除"       => "solution4",
                                        )),
                                        "data" =>array(
                                                "eq_index"        => "solution1",
                                                "eq_create"       => "solution2",
                                                "eq_update"       => "solution3",
                                                "eq_renewfield"   => "solution3",
                                                "eq_delete"       => "solution4",
                                        ));