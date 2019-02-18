<?php

namespace app\index\controller;

use think\Db;

use think\Request;

use think\Url;

use think\Controller;

use think\Session;

use app\index\model\ConfigMd;

use app\index\model\Share;

class Base extends Controller

{
	/**

	 * [_initialize description]初始化方法

	 * @return [type] [description] 权限和菜单控制

	 */

	protected function _initialize() {

        $server_name = request()->domain();
		//获取公共配置信息
		$Config		 = new  ConfigMd;
		
        $config_list = $Config->configList();
		foreach ( $config_list as $k => $v ) {
			
            $this->assign($v['varname'], $v['varvalue']);
            if($v['varname']=='web_name'){
                $web_title = $v['varvalue'];
            }
			if($v['varname']=='logo'){
                $share_img = $server_name.$v['varvalue'];
            }
        }
		$jssdk = new Share("wxa1eb42f645e78e0a", "2550ac678ec61ed3123cb843990eb915");
		$signPackage = $jssdk->GetSignPackage();
        $this->assign('url', $server_name);
        $this->assign('share_img', $share_img);
        $this->assign('share', $signPackage);
        $this->assign('share_title', $web_title);
        $this->assign('web_title', $web_title);
        $this->assign('share_desc', '');
        $this->assign('bottom', 0);

	}
    public function getchexi(Request $request){
        $res = Request::instance()->param();
        if(!empty($res)){
            $brand_id = $res['brand'];
            $data =  db('car_chexi')->where(['p_pinpai_id'=>$brand_id,'status'=>1])->select();
            return $data;
        }else{
            return ['data'=>''];
        }
    }
	public function _empty($name)
    {
		$this->error($name.'这个操作不存在');
    }
    static function car_param_list(){
        $data = [
            [
                'title'=>'基本参数',
                'data'=>[
                    ['value'=>'p_niankuan','name'=>'年款'],
                    ['value'=>'p_shangshishijian','name'=>'上市时间'],
                    ['value'=>'p_changshangzhidaojia_yuan','name'=>'厂商指导价（元）'],
                    ['value'=>'p_chemenshu_ge','name'=>'车门数（个）'],
                    ['value'=>'p_zuoweishu_ge','name'=>'座位数（个）'],
                    ['value'=>'p_youxiangrongji_l','name'=>'油箱容积（L）'],
                    ['value'=>'p_xinglixiangrongji_l','name'=>'行李箱容积（L）'],
                    ['value'=>'p_zuigaochesu_km_h','name'=>'最高时速（km/h）'],
                    //['value'=>'p_shiceyouhao_l_100km','name'=>'实际油耗'],
                ]

            ],
            [
                'title'=>'车身配置',
                'data'=>[
                    ['value'=>'p_zhang_kuan_gao_mm','name'=>'长宽高（mm）'],
                    ['value'=>'p_qianlunju_mm','name'=>'前轮距（mm）'],
                    ['value'=>'p_houlunju_mm','name'=>'后轮距（mm）'],
                    ['value'=>'p_zuixiaolidejianxi_mm','name'=>'最小离地间隙（mm）'],
                    ['value'=>'p_zhouju_mm','name'=>'轴距（mm）'],
                    ['value'=>'p_jibie','name'=>'车身级别'],
                    //['value'=>'p_cheshenjiegou','name'=>'车身结构'],
                    ['value'=>'p_zhengbeizhiliang_kg','name'=>'整备质量（kg）'],
                ]
            ],
            [
                'title'=>'发动机配置',
                'data'=>[
                    ['value'=>'p_fadongjixinghao','name'=>'发动机型号'],
                    ['value'=>'p_fadongji','name'=>'发动机'],
                    ['value'=>'p_pailiang_ml','name'=>'排量（mL）'],
                    ['value'=>'p_jinqixingshi','name'=>'进气形式'],
                    ['value'=>'p_qigangpailiexingshi','name'=>'气缸排列形式'],
                    ['value'=>'p_qigangshu_ge','name'=>'气缸数（个）'],
                    ['value'=>'p_meigangqimenshu_ge','name'=>'每缸气门数（个）'],
                    ['value'=>'p_yasuobi','name'=>'压缩比'],
                    ['value'=>'p_gangjing_mm','name'=>'缸径（mm）'],
                    ['value'=>'p_xingcheng_mm','name'=>'行程（mm）'],
                    ['value'=>'p_zuidamali_ps','name'=>'最大马力（Ps）'],
                    ['value'=>'p_zuidagonglv_kw','name'=>'最大功率（kW）'],
                    ['value'=>'p_zuidagonglvzhuansu_rpm','name'=>'最大功率转速（rpm）'],
                    ['value'=>'p_zuidaniuju_n_m','name'=>'最大扭矩(N·m)'],
                    ['value'=>'p_zuidaniujuzhuansu_rpm','name'=>'最大扭矩转速(rpm)'],
                    ['value'=>'p_fadongjiteyoujishu','name'=>'发动机特有技术'],
                    ['value'=>'p_ranliaoxingshi','name'=>'燃料形式'],
                    ['value'=>'p_ranyoubiaohao','name'=>'燃油标号'],
                    ['value'=>'p_gongyoufangshi','name'=>'供油方式'],
                    ['value'=>'p_huanbaobiaozhun','name'=>'排放标准'],
                ]
            ],
            [
                'title'=>'变速箱配置',
                'data'=> [
                    ['value'=>'p_biansuxiang','name'=>'变速箱'],
                    ['value'=>'p_dangweigeshu','name'=>'挡位个数'],
                    ['value'=>'p_biansuxiangleixing','name'=>'变速箱类型'],
                ]
            ],
            [
                'title'=>'底盘转向',
                'data'=>[
                    ['value'=>'p_qudongfangshi','name'=>'驱动方式'],
                    ['value'=>'p_qianxuanjialeixing','name'=>'前悬架类型'],
                    ['value'=>'p_houxuanjialeixing','name'=>'后悬架类型'],
                    ['value'=>'p_chetijiegou','name'=>'车体结构'],
                    ['value'=>'p_zhulileixing','name'=>'助力类型']
                ]
            ],
            [
                'title'=>'车轮制动',
                'data'=>[
                    ['value'=>'p_qianzhidongqileixing','name'=>'前制动器类型'],
                    ['value'=>'p_houzhidongqileixing','name'=>'后制动器类型'],
                    ['value'=>'p_zhuchezhidongleixing','name'=>'驻车制动类型'],
                    ['value'=>'p_qianluntaiguige','name'=>'前轮胎规格'],
                    ['value'=>'p_houluntaiguige','name'=>'后轮胎规格'],
                    ['value'=>'p_beitaiguige','name'=>'备胎规格']
                ]
            ],
            [
                'title'=>'安全装备',
                'data'=>[
                    ['value'=>'p_zhu_fujiashizuoanquanqinang','name'=>'主/副驾驶座安全气囊'],
                    ['value'=>'p_qian_houpaiceqinang','name'=>'前/后排侧气囊'],
                    ['value'=>'p_qian_houpaitoubuqinang_qilian','name'=>'前/后排头部气囊(气帘)'],
                    ['value'=>'p_xibuqinang','name'=>'膝部气囊'],
                    ['value'=>'p_taiyajiancezhuangzhi','name'=>'胎压监测装置'],
                    ['value'=>'p_lingtaiyajixuxingshi','name'=>'零胎压继续行驶'],
                    ['value'=>'p_anquandaiweixitishi','name'=>'安全带未系提示'],
                    ['value'=>'p_isofixertongzuoyijiekou','name'=>'ISOFIX儿童座椅接口'],
                    ['value'=>'p_fadongjidianzifangdao','name'=>'发动机电子防盗'],
                    ['value'=>'p_cheneizhongkongsuo','name'=>'车内中控锁'],
                    ['value'=>'p_yaokongyaoshi','name'=>'遥控钥匙'],
                    ['value'=>'p_wuyaoshiqidongxitong','name'=>'无钥匙启动系统'],
                    ['value'=>'p_wuyaoshijinruxitong','name'=>'无钥匙进入系统']
                ]
            ],
            [
                'title'=>'操控配置',
                'data'=>[
                    ['value'=>'p_absfangbaosi','name'=>'ABS防抱死'],
                    ['value'=>'p_zhidonglifenpei_ebd_cbcdeng','name'=>'制动力分配(EBD/CBC等)'],
                    ['value'=>'p_shachefuzhu_eba_bas_badeng','name'=>'刹车辅助(EBA/BAS/BA等)'],
                    ['value'=>'p_qianyinlikongzhi_asr_tcs_trcdeng','name'=>'牵引力控制(ASR/TCS/TRC等)'],
                    ['value'=>'p_cheshenwendingkongzhi_esc_esp_dscdeng','name'=>'车身稳定控制(ESC/ESP/DSC等)'],
                    ['value'=>'p_shangpofuzhu','name'=>'上坡辅助'],
                    ['value'=>'p_zidongzhuche','name'=>'自动驻车'],
                    ['value'=>'p_doupohuanjiang','name'=>'陡坡缓降'],
                    ['value'=>'p_kebianxuanjia','name'=>'可变悬架'],
                    ['value'=>'p_kongqixuanjia','name'=>'空气悬架']
                ]
            ],
            [
                'title'=>'外部配置',
                'data'=>[
                    ['value'=>'p_diandongtianchuang','name'=>'电动天窗'],
                    ['value'=>'p_quanjingtianchuang','name'=>'全景天窗'],
                    ['value'=>'p_yundongwaiguantaojian','name'=>'运动外观套件'],
                    ['value'=>'p_lvhejinlunquan','name'=>'铝合金轮圈'],
                    ['value'=>'p_diandongxihemen','name'=>'电动吸合门'],
                    ['value'=>'p_cehuamen','name'=>'侧滑门'],
                    ['value'=>'p_diandonghoubeixiang','name'=>'电动后备厢'],
                    ['value'=>'p_ganyinghoubeixiang','name'=>'感应后备厢'],
                    ['value'=>'p_chedingxinglijia','name'=>'车顶行李架'],
                    ['value'=>'p_kongqixuanjia','name'=>'空气悬架']
                ]
            ],
            [
                'title'=>'内部配置',
                'data'=> [
                    ['value'=>'p_pizhifangxiangpan','name'=>'皮质方向盘'],
                    ['value'=>'p_fangxiangpantiaojie','name'=>'方向盘调节'],
                    ['value'=>'p_fangxiangpandiandongtiaojie','name'=>'方向盘电动调节'],
                    ['value'=>'p_duogongnengfangxiangpan','name'=>'多功能方向盘'],
                    ['value'=>'p_fangxiangpanhuandang','name'=>'方向盘换挡'],
                    ['value'=>'p_fangxiangpanjiare','name'=>'方向盘加热'],
                    ['value'=>'p_dingsuxunhang','name'=>'定速巡航'],
                    ['value'=>'p_qian_houzhucheleida','name'=>'前/后驻车雷达'],
                    ['value'=>'p_daocheshipinyingxiang','name'=>'倒车视频影像'],
                    ['value'=>'p_xingchediannaoxianshiping','name'=>'行车电脑显示屏'],
                    ['value'=>'p_quanyejingyibiaopan','name'=>'全液晶仪表盘'],
                ]
            ],
            [
                'title'=>'座椅配置',
                'data'=> [
                    ['value'=>'p_zuoyicaizhi','name'=>'座椅材质'],
                    ['value'=>'p_zuoyigaoditiaojie','name'=>'座椅高低调节'],
                    ['value'=>'p_yaobuzhichengtiaojie','name'=>'腰部支撑调节'],
                    ['value'=>'p_jianbuzhichengtiaojie','name'=>'肩部支撑调节'],
                    ['value'=>'p_zhu_fujiashizuodiandongtiaojie','name'=>'主/副驾驶座电动调节'],
                    ['value'=>'p_houpaizuoyidiandongtiaojie','name'=>'后排座椅电动调节'],
                    ['value'=>'p_qian_houpaizuoyijiare','name'=>'前/后排座椅加热'],
                    ['value'=>'p_qian_houpaizuoyitongfeng','name'=>'前/后排座椅通风'],
                ]
            ],
            [
                'title'=>'多媒体配置',
                'data'=> [
                    ['value'=>'p_gpsdaohangxitong','name'=>'GPS导航系统'],
                    ['value'=>'p_zhongkongtaicaisedaping','name'=>'中控台彩色大屏'],
                    ['value'=>'p_lanya_chezaidianhua','name'=>'蓝牙/车载电话'],
                    ['value'=>'p_waijieyinyuanjiekou','name'=>'外接音源接口'],
                    ['value'=>'p_yangshengqipinpai','name'=>'扬声器品牌'],
                    ['value'=>'p_yangshengqishuliang','name'=>'扬声器数量'],
                ]
            ],
            [
                'title'=>'灯光配置',
                'data'=> [
                    ['value'=>'p_jinguangdeng','name'=>'近光灯'],
                    ['value'=>'p_yuanguangdeng','name'=>'远光灯'],
                    ['value'=>'p_ledrijianxingchedeng','name'=>'LED日间行车灯'],
                    ['value'=>'p_zishiyingyuanjinguang','name'=>'自适应远近光'],
                    ['value'=>'p_zidongtoudeng','name'=>'自动头灯'],
                    ['value'=>'p_zhuanxiangfuzhudeng','name'=>'转向辅助灯'],
                    ['value'=>'p_zhuanxiangtoudeng','name'=>'转向头灯'],
                    ['value'=>'p_qianwudeng','name'=>'前雾灯'],
                    ['value'=>'p_dadenggaodukediao','name'=>'大灯高度可调'],
                    ['value'=>'p_dadengqingxizhuangzhi','name'=>'大灯清洗装置'],
                ]
            ],
            [
                'title'=>'玻璃/后视镜',
                'data'=> [
                    ['value'=>'p_qian_houdiandongchechuang','name'=>'前/后电动车窗'],
                    ['value'=>'p_chechuangfangjiashougongneng','name'=>'车窗防夹手功能'],
                    ['value'=>'p_houshijingdiandongtiaojie','name'=>'后视镜电动调节'],
                    ['value'=>'p_zishiyingyuanjinguang','name'=>'后视镜加热'],
                    ['value'=>'p_nei_waihoushijingzidongfangxuanmu','name'=>'内/外后视镜自动防眩目'],
                    ['value'=>'p_houshijingdiandongzhedie','name'=>'后视镜电动折叠'],
                    ['value'=>'p_zheyangbanhuazhuangjing','name'=>'遮阳板化妆镜'],
                    ['value'=>'p_houyushua','name'=>'后雨刷'],
                    ['value'=>'p_ganyingyushua','name'=>'感应雨刷'],
                ]
            ],
            [
                'title'=>'空调/冰箱',
                'data'=> [
                    ['value'=>'p_kongtiaokongzhifangshi','name'=>'空调控制方式'],
                    ['value'=>'p_houpaidulikongtiao','name'=>'后排独立空调'],
                    ['value'=>'p_houzuochufengkou','name'=>'后座出风口'],
                    ['value'=>'p_wendufenqukongzhi','name'=>'温度分区控制'],
                    ['value'=>'p_nei_waihoushijingzidongfangxuanmu','name'=>'内/外后视镜自动防眩目'],
                    ['value'=>'p_cheneikongqitiaojie_huafenguolv','name'=>'车内空气调节/花粉过滤'],
                    ['value'=>'p_chezaibingxiang','name'=>'车载冰箱'],
                ]
            ],
            [
                'title'=>'高科技配置',
                'data'=> [
                    ['value'=>'p_zidongbocheruwei','name'=>'自动泊车入位'],
                    ['value'=>'p_fadongjiqitingjishu','name'=>'发动机启停技术'],
                    ['value'=>'p_bingxianfuzhu','name'=>'并线辅助'],
                    ['value'=>'p_chedaopianliyujingxitong','name'=>'车道偏离预警系统'],
                    ['value'=>'p_zhudongshache_zhudonganquanxitong','name'=>'主动刹车/主动安全系统'],
                    ['value'=>'p_zhengtizhudongzhuanxiangxitong','name'=>'整体主动转向系统'],
                    ['value'=>'p_yeshixitong','name'=>'夜视系统'],
                    ['value'=>'p_zhongkongyejingpingfenpingxianshi','name'=>'中控液晶屏分屏显示'],
                    ['value'=>'p_zishiyingxunhang','name'=>'自适应巡航'],
                    ['value'=>'p_quanjingshexiangtou','name'=>'全景摄像头'],
                ]
            ],
        ];
        return $data;
    }
    static function old_car_param_list(){
        $data = [
            [
            'title'=>'详细配置',
            'data'=> [
                 ['value'=>'brandName','name'=>'品牌名称'],
                 //['value'=>'groupCode','name'=>'车组编码'],
                 ['value'=>'groupName','name'=>'车组名称'],
                 ['value'=>'vehicleName','name'=>'车型名称'],
                 ['value'=>'importFlag','name'=>'国产/进口'],
                 ['value'=>'standardName','name'=>'车型俗称'],
                 ['value'=>'vehicleColor','name'=>'车身颜色'],
                 ['value'=>'displacement','name'=>'排量'],
                 ['value'=>'effluentStandard','name'=>'排放标准'],
                 ['value'=>'yearPattern','name'=>'年款'],
                 ['value'=>'uploadDate','name'=>'出厂日期'],
                 ['value'=>'standardname2','name'=>'公告号'],
                 ['value'=>'purchasePrice','name'=>'新车购置价'],
                 ['value'=>'listPrice','name'=>'厂商指导价'],
                 ['value'=>'gearboxType','name'=>'变速箱类型'],
                 ['value'=>'gearNum','name'=>'变速器档数'],
                 ['value'=>'supplyOil','name'=>'供油方式'],
                 ['value'=>'fuelJetType','name'=>'燃油喷射形式'],
                 ['value'=>'engineModel','name'=>'发动机型号'],
                 ['value'=>'drivenType','name'=>'驱动形式'],
                 ['value'=>'engineModel','name'=>'发动机型号'],
                 //['value'=>'remark','name'=>'备注'],
                 //['value'=>'hasConfig','name'=>'是否有更多配置'],
                 ['value'=>'familyName','name'=>'车系名称'],
                 ['value'=>'seat','name'=>'座位数'],
                 ['value'=>'cfgLevel','name'=>'配置等级'],
                 ['value'=>'marketDate','name'=>'上市年份'],
                 ['value'=>'vehicleSize','name'=>'外形尺寸'],
                 ['value'=>'wheelbase','name'=>'轴距'],
                 ['value'=>'fullWeight','name'=>'整备质量（千克）'],
                 ['value'=>'power','name'=>'功率'],
                 ['value'=>'bodyType','name'=>'车身结构'],
            //['value'=>'vehicleId','name'=>'车型代码，唯一标识'],
            //['value'=>'vehIsSeri','name'=>'是否商用车'],
             ]
                ]
        ];
        return $data;
    }


}