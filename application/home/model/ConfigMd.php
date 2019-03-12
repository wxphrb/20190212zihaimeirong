<?php
namespace app\home\model;

use app\home\model\Root;


class ConfigMd extends Root {

    protected $table = 'mx_webconfig';


    public function configList ($varname="") {

		return   $this->select();
	}
}
