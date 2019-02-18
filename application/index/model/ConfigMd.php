<?php
namespace app\index\model;

use app\index\model\Root;


class ConfigMd extends Root {

    protected $table = 'mx_webconfig';


    public function configList ($varname="") {

		return   $this->select();
	}
}
