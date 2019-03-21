<?php
/**
 * Created by PhpStorm.
 * User: lilong
 * Date: 2019/3/21
 * Time: 10:20
 */

namespace Chunyang\Auth\Models;


use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'user_name', 'user_rank', "cgwts_time", "city",
        "country", "district", "dzfp", "fkfs", "hz_zq", "is_mhj_hz",
        "is_only_buy", "is_special", "is_zq", "ls_review", "ls_zpgly",
        "mhj_number", "mobile", "msn", "org_cert_validity", "passwd_question",
        "province", "question", "qyhy", "shipping_id", "shipping_name",
        "wldwid", "wldwid1", "xkz_time", "yljg_time", "yyzz_time", "zq_has", "zs_time",
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $appends = [
        'bm_id', 'role', 'is_cs'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 从数据查询会员信息
     */
    public function info()
    {
        return $this->hasOne(self::class);
    }

    /**
     * @return mixed
     * 部门id
     */
    public function getBmIdAttribute()
    {
        return $this->bumen->bm_id;
    }

    /**
     * @return int
     * 会员角色 1-终端,0-商业
     */
    public function getRoleAttribute()
    {
        return in_array($this->user_rank, [2, 5]) ? 1 : 0;
    }

    /**
     * @return int
     * 是否测试会员
     */
    public function getIsCsAttribute()
    {
        return in_array($this->user_id, [13960]) ? 1 : 0;
    }

    public function getPayPointsAttribute()
    {
        if (!$this->relationLoaded('info')) {
            $this->load('info');
        }
        return $this->info->pay_points;
    }
}