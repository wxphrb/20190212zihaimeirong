<?php

namespace app\console\model;


use think\Model;

class Webtype extends Model
{
    public function webconfig()
    {
        return $this->hasMany('Webconfig', 'vargroup', "id");
    }
}