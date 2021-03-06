<?php

namespace app\controller;

use app\BaseController;
use app\model\User;
use think\facade\Db;

class Index extends BaseController
{
    public function index()
    {
        // 6.0.8
        dump(app()->version());

        Db::name('user')->where([
            'user_id' => 1
        ])->update([
            'user_status' => 2,
            'update_time' => null
        ]);

        // 能正常更新 
        // 但是没触发 onBeforeUpdate
        // UPDATE `user`  SET `user_status` = 1  WHERE  `user_id` = 1 
        User::withoutGlobalScope(['status'])->save([
            'user_status' => 1,
            'user_id' => 1
        ]);

        // 能正常更新 
        // 但是没触发 onBeforeUpdate
        // UPDATE `user`  SET `user_status` = 1  WHERE  `user_id` = 1 
        $a = User::withoutGlobalScope(['status'])->find(1);
        $a->withoutGlobalScope(['status'])->save([
            'user_status' => 1,
            'user_id' => $a->user_id
        ]);
        try {
            // 数据已经查询出来了
            // SELECT * FROM `user` WHERE  `user_id` = 1 LIMIT 1
            $a = User::withoutGlobalScope(['status'])->find(1);
            // array(3) { ["user_id"]=> int(1) ["user_name"]=> string(3) "123" ["user_status"]=> int(2) }
            var_dump($a->toArray());
        } catch (\Throwable $th) {
            var_dump('查询模型' . $th->getMessage());
        }
        try {
            // 生成的sql 无法更新数据
            // 能触发 onBeforeUpdate
            // UPDATE `user`  SET `user_status` = 1 , `update_time` = '2022-03-12 06:32:59'  WHERE  `user_status` = 1  AND `user_id` = 1 
            $a = User::withoutGlobalScope(['status'])->find(1);
            $a->user_status = 1;
            $a->save();
        } catch (\Throwable $th) {
            var_dump('model save' . $th->getMessage());
        }
        try {
            // 生成的sql 无法更新数据
            // 能触发 onBeforeUpdate
            // UPDATE `user`  SET `user_status` = 1 , `update_time` = '2022-03-12 06:34:06'  WHERE  `user_status` = 1  AND `user_id` = 1
            User::update([
                'user_status' => 1
            ], [
                'user_id' => 1
            ]);
        } catch (\Throwable $th) {
            var_dump('update' . $th->getMessage());
        }
        try {
            // 生成的sql 无法更新数据
            // 能触发 onBeforeUpdate
            // UPDATE `user`  SET `user_status` = 1 , `update_time` = '2022-03-12 06:34:38'  WHERE  `user_status` = 1
            $a->update([
                'user_status' => 1
            ]);
        } catch (\Throwable $th) {
            var_dump('model update' . $th->getMessage());
        }
        try {
            // 生成的sql 无法更新数据了
            // 能触发 onBeforeUpdate
            // UPDATE `user`  SET `user_status` = 1 , `update_time` = '2022-03-12 06:35:01'  WHERE  `user_status` = 1  AND `user_id` = 1  
            $a->save([
                'user_status' => 1
            ]);
        } catch (\Throwable $th) {
            var_dump('model save' . $th->getMessage());
        }
    }
}
