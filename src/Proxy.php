<?php
/**
 * Created by PhpStorm.
 * User: lilong
 * Date: 2019/3/21
 * Time: 9:17
 */

namespace Chunyang\Auth;


/**
 * Class Proxy
 * @package Chunyang\Auth
 * @method static HttpProxy uniqid()
 */

class Proxy extends \Illuminate\Support\Facades\Facade
{
    public static function getFacadeAccessor()
    {
        return HttpProxy::class;
    }
}