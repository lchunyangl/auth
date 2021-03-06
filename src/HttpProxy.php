<?php
/**
 * Created by PhpStorm.
 * User: lilong
 * Date: 2019/3/21
 * Time: 15:55
 */

namespace Chunyang\Auth;


use Chunyang\Auth\Models\Address;
use Chunyang\Auth\Models\Bumen;
use Chunyang\Auth\Models\Jyfw;
use Chunyang\Auth\Models\User;
use GuzzleHttp\Client;

class HttpProxy
{
    protected $client;

    protected $timeout = 10;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $credentials
     * @return bool
     * 登录
     */
    public function login(array $credentials)
    {
        $token = null;
        try {
            $response = $this->client->post(config('web.proxy_url') . '/UserLogin/login', $this->addTimeout(json_encode($credentials)));
            $response = $this->decodeJson($response);
            if (isset($response['code']) && $response['code'] === '000000') {
                $token = $response['content'];
            }
        } catch (\Exception $e) {
            abort($e->getCode());
        }
        return $token;
    }

    /**
     * @param $token
     * @return User|null
     * 获取认证会员信息
     */
    public function user($token)
    {
        if (is_null($token)) {
            return null;
        }

        $user = null;

        try {
            $response = $this->client->get(config('web.proxy_url') . '/SingleSignOn/find/' . $token, $this->addTimeout());
            $response = $this->decodeJson($response);
            if (isset($response['code']) && $response['code'] === '000000') {
                //dd($response['content']);
                $user = new User($response['content']);
                $user->setRelation('address', new Address($response['content']['user_address']));
                $user->setRelation('bumen', new Bumen(['bm_id' => $response['content']['bm_id']]));
                $jyfw = collect();
                foreach ($response['content']['jyfw'] as $item) {
                    $jyfw->push(new Jyfw($item));
                }
                $user->setRelation('jyfw', $jyfw);
            }
        } catch (\Exception $e) {
            abort($e->getCode());
        }
        return $user;
    }

    /**
     * @param $token
     * 注销
     */
    public function logout($token)
    {
        try {
            $this->client->delete(config('web.proxy_url') . '/SingleSignOn/logout/' . $token, $this->addTimeout());
        } catch (\Exception $e) {
            abort($e->getCode());
        }
    }

    /**
     * @return |null
     * 获取全局唯一键
     */
    public function uniqid()
    {
        $uniqid = null;
        try {
            $response = $this->client->get(config('web.proxy_url') . '/SequenceCreate/getLongId', $this->addTimeout());
            $response = $this->decodeJson($response);
            if (isset($response['code']) && $response['code'] === '000000') {
                $uniqid = $response['content'];
            }
        } catch (\Exception $e) {
            abort($e->getCode());
        }
        return $uniqid;
    }

    /**
     * @param $response
     * @return mixed
     * 格式化返回数据
     */
    protected function decodeJson($response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param $options
     * @return array
     * 添加超时时间
     */
    protected function addTimeout($string = null)
    {
        $collect = collect([
            'body' => $string,
            'timeout' => $this->timeout
        ]);
        return $collect->toArray();
    }
}