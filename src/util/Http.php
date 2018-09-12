<?php

namespace Ghlin\DingtalkEapp\Util;

use Httpful\Request;

Class Http
{
    public static function get($path, $params)
    {
        $url = self::joinParams($path, $params);
        $response = Request::get($url)->send();
        if ($response->hasErrors())
        {
            var_dump($response);
        }
        if ($response->body->errcode != 0)
        {
            var_dump($response->body);
        }
        return $response->body;
    }
    
    
    public static function post($path, $params, $data)
    {
        $url = self::joinParams($path, $params);
        $response = Request::post($url)
            ->body($data)
            ->sendsJson()
            ->send();
        if ($response->hasErrors())
        {
            var_dump($response);
        }
        if ($response->body->errcode != 0)
        {
            var_dump($response->body);
        }
        return $response->body;
    }
    
    
    private static function joinParams($path, $params)
    {
        $url = config('dingtalkeapp.oapi_host') . $path;
        if (count($params) > 0)
        {
            $url = $url . "?";
            foreach ($params as $key => $value)
            {
                $url = $url . $key . "=" . $value . "&";
            }
            $length = count($url);
            if ($url[$length - 1] == '&')
            {
                $url = substr($url, 0, $length - 1);
            }
        }
        return $url;
    }
}