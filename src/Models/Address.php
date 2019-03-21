<?php
/**
 * Created by PhpStorm.
 * User: lilong
 * Date: 2019/3/21
 * Time: 11:31
 */

namespace Chunyang\Auth\Models;


use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        "user_id", "address", "address_id", "best_time",
        "city", "consignee", "country", "district", "email",
        "is_active", "is_zjs", "mobile", "province", "sign_building",
        "tel", "town", "zipcode",
    ];
}