<?php
use think\Exception;
use think\Log;
use think\Config;
use think\Db;
use think\Loader;
use think\Request;

class Generate
{
    use \traits\controller\Jump;
    
    private $module;
    private $name;
    private $dir;
    private $namespaceSuffix;
    private $nameLower;
    private $data;
    // 控制器黑名单
    private $blacklistName = [
        'Base',
        'Common',
        'Dashboard',
        'FormBuilder',
        'Login',
        'System',
        'Manager',
    ];
    // 数据表黑名单
    private $blacklistTable = [
        'authgroup',
        'manager',
    ];

    public function run($data)
    {
        
        // 检查目录是否可写
        $pathCheck = APP_PATH . $data['module'] . DS;
        if (!self::checkWritable($pathCheck)) {
            $this->error("目录没有权限不可写，请执行一下命令修改权限：<br>chmod -R 755 " . realpath($pathCheck), 403);
        }
        $this->data = $data;
        $this->module = $data['module'];
        $controllers = explode(".", $data['controller']);
        $this->name = array_pop($controllers);
        $this->nameLower = Loader::parseName($this->name);

        // 分级控制器目录和命名空间后缀
        if ($controllers) {
            $this->dir = strtolower(implode(DS, $controllers) . DS);
            $this->namespaceSuffix = "\\" . strtolower(implode("\\", $controllers));
        } else {
            $this->dir = "";
            $this->namespaceSuffix = "";
        }

        // 数据表表名
        $tableName = str_replace(DS, '_', $this->dir) . $this->nameLower;

        // 判断是否在黑名单中
        if (in_array($data['controller'], $this->blacklistName)) {
            $this->error('该控制器不允许创建');
        }

        // 判断是否在数据表黑名单中
        if (isset($data['table']) && $data['table'] && in_array($tableName, $this->blacklistTable)) {
            $this->error('该数据表不允许创建');
        }

        // 创建目录
        $dir_list = ["view" . DS . $this->dir . $this->nameLower];
        $dir_list[] = "model" . DS . $this->dir;
        $dir_list[] = "validate" . DS . $this->dir;
        $dir_list[] = "controller" . DS . $this->dir;
        $this->buildDir($dir_list);
        // 文件路径
        $pathView = APP_PATH . $this->module . DS . "view" . DS . $this->dir . $this->nameLower . DS;
        $pathTemplate = APP_PATH . $this->module . DS . "view" . DS . "generate" . DS . "template" . DS;
        $fileName = APP_PATH . "%MODULE%" . DS . "%NAME%" . DS . $this->dir . $this->name . ".php";
        $code = $this->parseCode();
        // 执行方法
        $this->buildAll($pathView, $pathTemplate, $fileName, $tableName, $code, $data);
        
    }

    /**
     * 检查当前模块目录是否可写
     * @return bool
     */
    public static function checkWritable($path = '')
    {
        try {
            $path = $path ? $path : APP_PATH . $this->module . DS;
            $testFile = $path . "bulid.test";
            if (!file_put_contents($testFile, "test")) {
                return false;
            }
            // 解除锁定
            unlink($testFile);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 生成所有文件
     */
    private function buildAll($pathView, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        // 创建文件
        $this->buildIndex($pathView, $pathTemplate, $fileName, $tableName, $code, $data);
        $this->buildCreate($pathView, $pathTemplate, $fileName, $tableName, $code, $data);
        $this->buildController($pathView, $pathTemplate, $fileName, $tableName, $code, $data);
        $this->buildValidate($pathView, $pathTemplate, $fileName, $tableName, $code, $data);
        $this->buildModel($pathView, $pathTemplate, $fileName, $tableName, $code, $data);
        if (isset($data['create_table']) && $data['create_table']) {
            $this->buildTable($pathView, $pathTemplate, $fileName, $tableName, $code, $data);
        }
        // 建立配置文件--菜单
        $this->buildConfig($pathView, $pathTemplate, $fileName, $tableName, $code, $data);
       
    }


    /**
     * 创建目录
     */
    private function buildDir($dir_list)
    {
        foreach ($dir_list as $dir) {
            $path = APP_PATH . $this->module . DS . $dir;
            if (!is_dir($path)) {
                // 创建目录
                mkdir($path, 0755, true);
            }
        }
    }

    /**
     * 创建 create.html 文件
     */
    private function buildCreate($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        $template = file_get_contents($pathTemplate . "create.tpl");
        $file = $path . "create.html";

        return file_put_contents($file, str_replace(
            ["[MODULE]", "[NAME]","[ROWS]", "[SET_VALUE]","[SET_FILEINPUT]","[SET_UEDITOR]"],
            [$this->module, $this->name, $code['create'], implode("\n", array_merge($code['set_checked'], $code['set_selected'])), $code['set_fileinput'],$code['set_ueditor']],
            $template));
    }

    /**
     * 创建 index.html 文件
     */
    private function buildIndex($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {

        $th = implode("\n", $code['th']);;
        $td = implode("\n", $code['td']);;

        $template = file_get_contents($pathTemplate . "index.tpl");
        $file = $path . "index.html";

        return file_put_contents($file, str_replace(
                ["[MODULE]", "[NAME]", "[TH]", "[TD]"],
                [$this->module, $this->name, $th, $td],
                $template
            )
        );
    }

    /**
     * 创建控制器文件
     */
    private function buildController($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        $template = file_get_contents($pathTemplate . "Controller.tpl");
        $file = str_replace(
            ['%MODULE%', '%NAME%'],
            [$this->module, 'controller'],
            $fileName
        );

        return file_put_contents($file, str_replace(
                ["[MODULE]", "[TITLE]", "[NAME]", "[NAMESPACE]"],
                [$this->module, $this->data['title'], $this->name, $this->namespaceSuffix],
                $template
            )
        );
    }

    /**
     * 创建模型文件
     */
    private function buildModel($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        // 直接生成空模板
        $template = file_get_contents($pathTemplate . "Model.tpl");
        // 获取模型的路径，根据配置文件读取
        $module = $this->module;
        $file = str_replace(
            ['%MODULE%', '%NAME%'],
            [$module, 'model'],
            $fileName
        );
        $autoTimestamp = '';
        if (isset($this->data['auto_timestamp']) && $this->data['auto_timestamp']) {
            $autoTimestamp = '// 开启自动写入时间戳字段' . "\n"
                . tab(1) . 'protected $autoWriteTimestamp = \'int\';';
        }

        return file_put_contents($file, str_replace(
                ["[MODULE]", "[TITLE]", "[NAME]", "[NAMESPACE]", "[TABLE]", "[AUTO_TIMESTAMP]"],
                [$module, $this->data['title'], $this->name, $this->namespaceSuffix, $tableName, $autoTimestamp],
                $template
            )
        );
    }

    /**
     * 创建验证器
     */
    private function buildValidate($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        $template = file_get_contents($pathTemplate . "Validate.tpl");
        // 获取验证器的路径，根据配置文件读取
        $module = $this->module;
        $file = str_replace(
            ['%MODULE%', '%NAME%'],
            [$module, 'validate'],
            $fileName
        );

        return file_put_contents($file, str_replace(
                ["[MODULE]", "[TITLE]", "[NAME]", "[NAMESPACE]", "[RULE]"],
                [$module, $this->data['title'], $this->name, $this->namespaceSuffix, $code['validate']],
                $template
            )
        );
    }

    /**
     * 创建数据表
     */
    private function buildTable($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        // 一定别忘记表名前缀
        $tableName = Config::get("database.prefix") . $tableName;
        // 在 MySQL 中，DROP TABLE 语句自动提交事务，因此在此事务内的任何更改都不会被回滚，不能使用事务
        // http://php.net/manual/zh/pdo.rollback.php
        $tableExist = false;
        // 判断表是否存在
        $ret = Db::query("SHOW TABLES LIKE '{$tableName}'");
        // 表存在
        if ($ret && isset($ret[0])) {
            //不是强制建表但表存在时直接return
            if (!isset($this->data['create_table_force']) || !$this->data['create_table_force']) {
                return true;
            }
            Db::execute("RENAME TABLE {$tableName} to {$tableName}_build_bak");
            $tableExist = true;
        }
        $auto_create_field = ['id', 'status', 'orderid','isdelete', 'create_time', 'update_time'];
        // 强制建表和不存在原表执行建表操作
        $fieldAttr = [];
        $key = [];
        if (in_array('id', $auto_create_field)) {
            $fieldAttr[] = tab(1) . "`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '{$this->data['title']}主键'";
        }
        foreach ($this->data['field'] as $field) {
            if (!in_array($field['name'], $auto_create_field)) {
                // 字段属性
                $fieldAttr[] = tab(1) . "`{$field['name']}` {$field['type']}"
                    . ($field['extra'] ? ' ' . $field['extra'] : '')
                    . (isset($field['not_null']) && $field['not_null'] ? ' NOT NULL' : '')
                    . (strtolower($field['default']) == 'null' ? '' : " DEFAULT '{$field['default']}'")
                    . ($field['comment'] === '' ? '' : " COMMENT '{$field['comment']}'");
            }
            if($field['name']=='status'){
                $fieldAttr[] = tab(1) . "`status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态，1-正常 | 0-禁用'";
            }
            if($field['name']=='orderid'){
                $fieldAttr[] = tab(1) . "`orderid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序'";
            }
            if($field['name']=='isdelete'){
                $fieldAttr[] = tab(1) . "`isdelete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态，1-删除 | 0-正常'";
            }
            // 索引
            if (isset($field['key']) && $field['key'] && $field['name'] != 'id') {
                $key[] = tab(1) . "KEY `{$field['name']}` (`{$field['name']}`)";
            }
        }
        // 如果创建模型则自动生成create_time，update_time字段
        if (isset($this->data['auto_timestamp']) && $this->data['auto_timestamp']) {
            // 自动生成 create_time 字段，相应自动生成的模型也开启自动写入create_time和update_time时间，并且将类型指定为int类型
            // 时间戳使用方法见：http://www.kancloud.cn/manual/thinkphp5/138668
            $fieldAttr[] = tab(1) . "`create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'";
            $fieldAttr[] = tab(1) . "`update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'";
        }
        // 默认自动创建主键为id
        $fieldAttr[] = tab(1) . "PRIMARY KEY (`id`)";

        // 会删除之前的表，会清空数据，重新创建表，谨慎操作
        $sql_drop = "DROP TABLE IF EXISTS `{$tableName}`";
        // 默认字符编码为utf8，表引擎默认InnoDB，其他都是默认
        $sql_create = "CREATE TABLE `{$tableName}` (\n"
            . implode(",\n", array_merge($fieldAttr, $key))
            . "\n)ENGINE=" . (isset($this->data['table_engine']) ? $this->data['table_engine'] : 'InnoDB')
            . " DEFAULT CHARSET=utf8 COMMENT '{$this->data['title']}'";

        // 写入执行的SQL到日志中，如果不是想要的表结构，请到日志中搜索BUILD_SQL，找到执行的SQL到数据库GUI软件中修改执行，修改表结构
        Log::write("BUILD_SQL：\n{$sql_drop};\n{$sql_create};", Log::SQL);
        // execute和query方法都不支持传入分号 (;)，不支持一次执行多条 SQL
        try {
            Db::execute($sql_drop);
            Db::execute($sql_create);
            Db::execute("DROP TABLE IF EXISTS `{$tableName}_build_bak`");
        } catch (\Exception $e) {
            // 模拟事务操作，滚回原表
            if ($tableExist) {
                Db::execute("RENAME TABLE {$tableName}_build_bak to {$tableName}");
            }

            $this->error($e->getMessage());
        }
    }

    /**
     * 创建配置文件
     */
    private function buildConfig($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        $title = $data['title'];
        $control = $this->parse_name($data['controller']);

        $content ="\n".'// '.$title. "\n"
        . '$acl_inc[$i]["low_leve"]["'.$control.'"]= array( "'.$title.'" =>array("index",'. "\n"
        .tab(10).'array('. "\n"
        .tab(12).'"列表"       => "'.$control.'1",'. "\n"
        .tab(12).'"添加"       => "'.$control.'2",'. "\n"
        .tab(12).'"修改"       => "'.$control.'3",'. "\n"
        .tab(12).'"删除"       => "'.$control.'4",'. "\n"
        .tab(10).')),'. "\n"
        .tab(10).'"data" =>array('. "\n"
        .tab(12).'"eq_index"        => "'.$control.'1",'. "\n"
        .tab(12).'"eq_create"       => "'.$control.'2",'. "\n"
        .tab(12).'"eq_update"       => "'.$control.'3",'. "\n"
        .tab(12).'"eq_renewfield"   => "'.$control.'3",'. "\n"
        .tab(12).'"eq_delete"       => "'.$control.'4",'. "\n"
        .tab(10).'));';

        $file = APP_PATH.'common'. DS . 'common' . DS  .'auth.inc.php';

        return file_put_contents($file, $content, FILE_APPEND);
    }

    private function parse_name($name, $type=0) {
        if ($type) {
            return ucfirst(preg_replace("/_([a-zA-Z])/e", "strtoupper('\\1')", $name));
        } else {
            return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
        }
    }


    /**
     * 创建文件的代码
     * @return array
     * return [
     * 'search'          => $search,
     * 'th'              => $th,
     * 'td'              => $td,
     * 'edit'            => $editField,
     * 'set_checked'     => $setChecked,
     * 'set_selected'    => $setSelected,
     * 'search_selected' => $searchSelected,
     * 'filter'          => $filter,
     * 'validate'        => $validate,
     * ];
     */
    private function parseCode()
    {
        // 生成 th 的代码
        $th = ['<th>ID</th>'];
        // 生成 td 的代码
        $td = ['<td><div class="ckbox ckbox-primary"><input type="checkbox" id="checkbox{$vo.id}" name="checkid[]" value="{$vo.id}" /><label for="checkbox{$vo.id}">{$vo.id}</label></div></td>'];
        // 生成 create.html 文件的代码
        $createField = '';
        // radio类型的表单控件编辑状态使用javascript赋值
        $setChecked = [];
        // select类型的表单控件编辑状态使用javascript赋值
        $setSelected = [];
        // 上传图片js引入
        $setFileinput = '{block name="fileinput"}{/block}';
        // 编辑器js引入
        $setUeditor = '{block name="ueditor"}{/block}';
        // 搜索时被选中的值
        $searchSelected = '';
        // 控制器过滤器
        $filter = '';
        // 生成验证器文件的代码
        $validate = '';
        if (isset($this->data['form']) && $this->data['form']) {
            foreach ($this->data['form'] as $form) {
                // 状态选择的自动设置为单选框
                if ($form['name'] == 'status') {
                    $form['title'] = '状态';
                    $form['type'] = 'radio';
                    $form['option'] = '1:启用#0:禁用';
                    $form['default'] = '1';
                }
                if ($form['name'] == 'orderid') {
                    $form['title'] = '排序';
                }
                $options = $this->parseOption($form['option']);


                if (isset($form['list']) && $form['list']==1) {
                    // th
                    $th[] = '<th>' . $form['title'] . "</th>";
                    $show_action = '';
                    if($form['name'] == "status"){
                        $show_action = '|show_status=$vo.id';
                    }
                    if($form['name'] == "picurl"){
                        $show_action = '|show_img';
                    }
                    $td[] = '<td>{$vo.' . $form['name'] . $show_action . '}</td>';
                }
                
                // 像id这种白名单字段不需要自动生成到编辑页
                if (!in_array($form['name'], ['id', 'isdelete', 'orderid', 'create_time', 'update_time'])) {
                    // 使用 Validform 插件前端验证数据格式，生成在表单控件上的验证规则
                    $validateForm = '';
                    if (isset($form['require'])) {
                        $validateForm = ' required';
                        $validate .= tab(2) . '"' . $form['name'] . '|' . $form['title'] . '" => "'
                            .  'require' . '",' . "\n";
                    }
                    $createField .= tab(2) . '<div class="form-group">' . "\n"
                        . tab(3) . '<label class="col-sm-3 control-label">'
                        . (isset($form['require']) && $form['require'] ? '<span>*</span>' : '')
                        . $form['title'] . '：</label>' . "\n"
                        . tab(3) . '<div class="col-sm-6';
                    if($form['type']=='radio' || $form['type']=='checkbox'){
                         $createField .=' wh-inline';
                    }
                    $createField .='">' . "\n";
                    switch ($form['type']) {
                        case "radio":
                        case "checkbox":
                            if ($form['type'] == "radio") {
                                // radio类型的控件进行编辑状态赋值，checkbox类型控件请自行根据情况赋值
                                $setChecked[] = tab(1) . '$("[name=\'' . $form['name'] . '\'][value=\'{$vo.' . $form['name'] . ' ?? \'' . $form['default'] . '\'}\']").attr("checked", true);';
                            } else {
                                $setChecked[] = tab(1) . 'var checks = \'' . $form['default'] . '\'.split(",");' . "\n"
                                    . tab(1) . 'if (checks.length > 0){' . "\n"
                                    . tab(2) . 'for (var i in checks){' . "\n"
                                    . tab(3) . '$("[name=\'' . $form['name'] . '[]\'][value=\'"+checks[i]+"\']").attr("checked", true);' . "\n"
                                    . tab(2) . '}' . "\n"
                                    . tab(1) . '}';
                            }

                            // 默认只生成一个空的示例控件，请根据情况自行复制编辑
                            $name = $form['name'] . ($form['type'] == "checkbox" ? '[]' : '');

                            switch ($options[0]) {
                                case 'string':
                                    $createField .= $this->getCheckbox($form, $name, $validateForm, $options[1], '', 0);
                                    break;
                                case 'var':
                                    $createField .= tab(4) . '{foreach name="$Think.config.conf.' . $options[1] . '" item=\'v\' key=\'k\'}' . "\n"
                                        . $this->getCheckbox($form, $name, $validateForm, '{$v}', '{$k}', '{$k}')
                                        . tab(4) . '{/foreach}' . "\n";
                                    break;
                                case 'array':
                                    foreach ($options[1] as $option) {
                                        $createField .= $this->getCheckbox($form, $name, $validateForm, $option[1], $option[0], $option[0]);
                                    }
                                    break;
                            }
                            break;
                        case "select":
                            // select类型的控件进行编辑状态赋值
                            $setSelected[] = tab(1) . '$("[name=\'' . $form['name'] . '\']").find("[value=\'{$vo.' . $form['name'] . ' ?? \'' . $form['default'] . '\'}\']").attr("selected", true);';
                            $createField .= tab(5) . '<select name="' . $form['name'] . '" class="select2"' . $validateForm . '>' . "\n"
                                . implode("\n", $this->getOption($options, $form, false, 6)) . "\n"
                                . tab(5) . '</select>' . "\n";
                            break;
                        case "textarea":
                            $createField .= tab(4) . '<textarea class="form-control" placeholder="" name="' . $form['name'] . '" >'
                                . '{$vo.' . $form['name'] . ' ?? \'' . $form['default'] . '\'}'
                                . '</textarea>' . "\n";
                            break;
                        case "ueditor":
                            $setUeditor = '{block name="ueditor"}{__block__}{/block}';
                            $setSelected[] = tab(1) . 'var ue = UE.getEditor("container", { initialFrameWidth: null });';
                            $createField .= tab(4) . '<textarea class="form-control" placeholder="" name="' . $form['name'] . '" id="container">'
                                . '{$vo.' . $form['name'] . ' ?? \'' . $form['default'] . '\'}'
                                . '</textarea>' . "\n";
                            break;
                        case "fileinput":
                            $setFileinput = '{block name="fileinput"}{__block__}{/block}';
                            $setSelected[] = tab(1) . 'fileinputOne("fileone","picurl");';
                            $createField .= tab(4) . '<input id="fileone" type="file" class="file-loading">'. "\n"
                                . tab(4) . '<input type="hidden" id="picurl" placeholder="' . $form['title'] . '" name="' . $form['name'] . '" '
                                . 'value="' . '{$vo.' . $form['name'] . ' ?? \'' . $form['default'] . '\'}' . '" '
                                . '>' . "\n";
                            break;
                        case "date":
                            $createField .= tab(4) . '<input type="text" class="form-control" '
                                . 'placeholder="' . $form['title'] . '" name="' . $form['name'] . '" '
                                . 'value="' . '{$vo.' . $form['name'] . ' ?? \'' . $form['default'] . '\'}' . '" '
                                . '{literal} onClick="WdatePicker({dateFmt:\'yyyy-MM-dd  HH:mm:ss\'})"{/literal} '
                                . $validateForm . '>' . "\n";
                            break;
                        case "text":
                        case "password":
                        case "number":
                        case "url":
                        default:
                            $createField .= tab(4) . '<input type="' . $form['type'] . '" class="form-control" '
                                . 'placeholder="' . $form['title'] . '" name="' . $form['name'] . '" '
                                . 'value="' . '{$vo.' . $form['name'] . ' ?? \'' . $form['default'] . '\'}' . '" '
                                . $validateForm . '>' . "\n";
                            break;
                    }
                    $createField .= tab(3) . '</div>' . "\n"
                        . tab(2) . '</div>' . "\n";
                }
                else if($form['name'] =='orderid'){
                    $createField .= tab(2) . '<div class="form-group">' . "\n"
                                    . tab(3) . '<label class="col-sm-3 control-label">'
                                    . $form['title'] . '：</label>' . "\n"
                                    . tab(3) . '<div class="col-sm-6">' . "\n"
                                    . tab(4) . '<input type="number" class="form-control" '
                                    . 'placeholder="' . $form['title'] . '" name="' . $form['name'] . '" '
                                    . 'value="' . '{$Request.param.id|get_orderid}' . '" '
                                    . ' required>' . "\n"
                                    . tab(3) . '</div>' . "\n"
                                    . tab(2) . '</div>' . "\n";

                }
            }
        }

        // 如果创建模型则自动生成create_time，update_time字段
        if (isset($this->data['auto_timestamp']) && $this->data['auto_timestamp']) {

            $th[] = '<th>创建时间</th>';
            $td[] = '<td>{$vo.create_time|date="Y-m-d H:i:s",###}</td>';
            $th[] = '<th>上次更新</th>';
            $td[] = '<td>{$vo.update_time|date="Y-m-d H:i:s",###}</td>';
            $createField .= tab(2) . '{if condition="$Request.action eq \'update\'"}' . "\n"
                         . tab(2) . '<div class="form-group">' . "\n"
                         . tab(3) . '<label class="col-sm-3 control-label">'
                         . '创建时间：</label>' . "\n"
                         . tab(3) . '<div class="col-sm-6">' . "\n"
                         . tab(4) . '<label class="control-label"> '
                         . '{$vo.create_time|date=\'Y-m-d H:i:s\',###}</label>'. "\n"
                         . tab(3) . '</div>' . "\n"
                         . tab(2) . '</div>' . "\n"
                         . tab(2) . '<div class="form-group">' . "\n"
                         . tab(3) . '<label class="col-sm-3 control-label">'
                         . '上次更新：</label>' . "\n"
                         . tab(3) . '<div class="col-sm-6">' . "\n"
                         . tab(4) . '<label class="control-label"> '
                         . '{$vo.update_time|date=\'Y-m-d H:i:s\',###}</label>'. "\n"
                         . tab(3) . '</div>' . "\n"
                         . tab(2) . '</div>' . "\n"
                         . tab(2) . '{/if} ' . "\n";
        }

        if ($filter) {
            $filter = 'protected function filter(&$map)' . "\n"
                . tab(1) . '{' . "\n"
                . $filter
                . tab(1) . '}';
        }
        // 自动屏蔽查询条件isdelete字段
        if (!isset($this->data['menu']) ||
            (isset($this->data['menu']) &&
                !in_array("delete", $this->data['menu']) &&
                !in_array("recyclebin", $this->data['menu'])
            )
        ) {
            $filter = 'protected static $isdelete = false;' . "\n\n" . tab(1) . $filter;
        }
        if ($validate) {
            $validate = 'protected $rule = [' . "\n" . $validate . '    ];';
        }

        return [
            'th'              => $th,
            'td'              => $td,
            'create'          => $createField,
            'set_checked'     => $setChecked,
            'set_selected'    => $setSelected,
            'set_fileinput'   => $setFileinput,
            'set_ueditor'     => $setUeditor,
            'search_selected' => $searchSelected,
            'filter'          => $filter,
            'validate'        => $validate,
        ];
    }

    /**
     * 生成复选框、单选框
     */
    private function getCheckbox($form, $name, $validateForm, $title, $value = '', $key = 0, $tab = 4)
    {
        if($form['type']=="radio"){
             return tab($tab) . '<div class="rdio rdio-primary">' . "\n"
                . tab($tab + 1) . '<input type="' . $form['type'] . '" name="' . $name . '" '
                . 'id="' . $form['name'] . '-' . $key . '" value="' . $value . '"' . $validateForm . '>' . "\n"
                . tab($tab + 1) . '<label for="' . $form['name'] . '-' . $key . '">' . $title . '</label>' . "\n"
                . tab($tab) . '</div>' . "\n";

        }

        else{
            return tab($tab) . '<div class="ckbox ckbox-primary">' . "\n"
                . tab($tab + 1) . '<input type="' . $form['type'] . '" name="' . $name . '" '
                . 'id="' . $form['name'] . '-' . $key . '" value="' . $value . '"' . $validateForm . '>' . "\n"
                . tab($tab + 1) . '<label for="' . $form['name'] . '-' . $key . '">' . $title . '</label>' . "\n"
                . tab($tab) . '</div>' . "\n";
            }
    }

    /**
     * 获取下拉框的option
     */
    private function getOption($options, $form, $empty = true, $tab = 3)
    {
        switch ($options[0]) {
            case 'string':
                return [tab($tab) . '<option value="">' . $options[1] . '</option>'];
                break;
            case 'var':
                $ret = [];
                if ($empty) {
                    $ret[] = tab($tab) . '<option value="">所有' . $form['title'] . '</option>';
                }
                $ret[] = tab($tab) . '{foreach name="$Think.config.conf.' . $options[1] . '" item=\'v\' key=\'k\'}';
                $ret[] = tab($tab + 1) . '<option value="{$k}">{$v}</option>';
                $ret[] = tab($tab) . '{/foreach}';

                return $ret;
                break;
            case 'think_var':
                $ret = [];
                if ($empty) {
                    $ret[] = tab($tab) . '<option value="">所有' . $form['title'] . '</option>';
                }
                $ret[] = tab($tab) . '{foreach name="$' . $options[1] . '" item=\'v\'}';
                $ret[] = tab($tab + 1) . '<option value="{$v.id}">{$v.name}</option>';
                $ret[] = tab($tab) . '{/foreach}';

                return $ret;
                break;
            case 'array':
                $ret = [];
                foreach ($options[1] as $option) {
                    $ret[] = tab($tab) . '<option value="' . $option[0] . '">' . $option[1] . '</option>';
                }

                return $ret;
                break;
        }
    }

    /**
     * 格式化选项值
     */
    private function parseOption($option, $string = false)
    {
        if (!$option) return ['string', $option];
        if (preg_match('/^\{\$(.*?)\}$/', $option, $match)) {
            // {$vo.item} 这种格式传入的变量
            return ['think_var', $match[1]];
        } elseif (preg_match('/^\{(.*?)\}$/', $option, $match)) {
            // {vo.item} 这种格式传入的变量
            return ['var', $match[1]];
        } else {
            if ($string) {
                return ['string', $option];
            }
            // key:val#key2:val2#val3#... 这种格式
            $ret = [];
            $arrVal = explode('#', $option);
            foreach ($arrVal as $val) {
                $keyVal = explode(':', $val, 2);
                if (count($keyVal) == 1) {
                    $ret[] = ['', $keyVal[0]];
                } else {
                    $ret[] = [$keyVal[0], $keyVal[1]];
                }
            }

            return ['array', $ret];
        }
    }

}
