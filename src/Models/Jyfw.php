<?php
/**
 * Created by PhpStorm.
 * User: lilong
 * Date: 2019/3/21
 * Time: 11:29
 */

namespace Chunyang\Auth\Models;


use Illuminate\Database\Eloquent\Model;

class Jyfw extends Model
{
    protected $fillable = [
        'shangplx', 'sxrq'
    ];
}