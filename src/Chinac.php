<?php

namespace Lambq\Chinac;

use Lambq\Chinac\Util\ChinacOpenApi;
use Lambq\Chinac\Util\ChinacFileApi;

class Chinac
{
    protected $accessKeyId = null;
    protected $accessKeySecret = null;

    protected $client;
    public function __construct()
    {
        $this->accessKeyId      = config('chinac.accessKeyId');
        $this->accessKeySecret  = config('chinac.accessKeySecret');
        $this->client           = new ChinacOpenApi($this->accessKeyId, $this->accessKeySecret);
    }

    public function setHttp($action, $method = '', $request)
    {
        if ($method == 'POST')
        {
            // POST请求示例(查询云手机)
            $res = $this->client
                ->setAction($action)
                ->setHttpMethod('POST')                 //请求方式，默认GET
                ->setRequestParams($request) //请求参数
                ->do();
            print_r($res);
        } else {
            $res = $this->client
                ->setAction($action)
                ->setRequestParams($request) //请求参数
                ->do();
            print_r($res);
        }
    }

    // GET请求示例(查询云主机)
    public function updateFile()
    {
        /***文件上传示例***/
        // 获取文件服务器地址
        $res = $this->client->setAction('GetCpfsUrl')->setHttpMethod('POST')->setRequestParams(['Region'=>'cn-cloudPhone2'])->do();
        $url = json_decode($res['Info'], 1)['data']['WebFsUrl'];

        // 获取上传令牌
        $res = $this->client->setAction('GetUploadToken')->setHttpMethod('POST')->setRequestParams(['Region'=>'cn-cloudPhone2'])->do();
        $token = json_decode($res['Info'], 1)['data']['Token'];

        // 上传文件
        $file = new ChinacFileApi($this->accessKeyId, $this->accessKeySecret);
        $file->setFileUrl($url)->setToken($token)->setUploadFileName('test.txt');
        try {
            $res = $file->do();
            $fileId = json_decode($res['Info'], 1)['ResponseData']['FileId'];
            print_r($fileId);
        } catch (\Throwable $e) {
            print_r($e->getMessage());
        }
    }
}