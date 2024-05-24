<?php
namespace App\Admin\Model;

use think\Model;

class AdminUserModel extends Model
{
    // 表名
    protected $table = 'admin_users';

    //定义为毫秒时间戳
    protected $dateFormat = 'U';

    //获取created_at字段时处理
    public function getCreatedAtAttribute($value)
    {
        return $value / 1000;
    }

    //获取updated_at字段时处理
    public function getUpdatedAtAttribute($value)
    {
        return $value / 1000;
    }

    protected $casts = [
        'id' => 'string',   //把id返回字符串
    ];
    /**
     * 用户的角色
     */
    public function roles()
    {
        return $this->belongsToMany('App\Model\AdminRoleModel', 'admin_role_permission', 'role_id', 'permission_id');

    }
}