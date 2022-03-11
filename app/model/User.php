<?php

namespace app\model;

use app\BaseModel;
use think\Exception;

class User extends BaseModel
{
    // 设置主键名
    protected $pk = 'user_id';
    // 设置废弃字段
    protected $disuse = [];
    // 设置字段信息
    protected $schema = [
        'user_id'          => 'int',
        'user_name'        => 'string',
        'user_status'      => 'int',
    ];
    // 全局的查询范围
    protected $globalScope = ['status'];
    // 状态查询范围
    public function scopeStatus($query, $status = 1)
    {
        $query->where('user_status', $status);
    }

    public static function onBeforeUpdate($model)
    {
        dump('执行 onBeforeUpdate');
        // throw new Exception("执行 onBeforeUpdate", 1);
    }
}
