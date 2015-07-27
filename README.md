# JMessage API client library for PHP

极光IM支持的PHP版本服务器端SDK

根据极光IM的[IM REST API](http://docs.jpush.io/server/rest_api_im/)写成PHP类。

## 简要概述  

* 本API提供简单的接口去调用[IM REST API](http://docs.jpush.io/server/rest_api_im/)


## 引入代码
```
require_once 'JMessage.php';
```

## 例子
```
    public function test()
    {
       $jim = new JMessage();
       var_dump($jim->openRegister(array('username' => 'test1', 'password' => '123456')));
       var_dump($jim->getUserDetails('test1'));
       
       //发送消息
       var_dump($jim->sendMsg('test1', '天气不错哦'));
       var_dump($jim->sendMsg('10008151', '下午好', 'group'));
    }
```

## 文档
* [IM REST API](http://docs.jpush.io/server/rest_api_im/)

## Bug反馈
请在Issues里提交。


## 版本更新

* [Release页面](https://github.com/52fhy/JMessage/releases)有详细的版本发布记录与下载。
