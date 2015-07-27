# JMessage API client library for PHP

极光IM支持的PHP版本服务器端SDK

根据极光IM的[IM REST API](http://docs.jpush.io/server/rest_api_im/)写成PHP类。

## 简要概述  

* 本API提供简单的接口去调用(IM REST API](http://docs.jpush.io/server/rest_api_im/)


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
    }
```

## 文档
* [IM REST API](http://docs.jpush.io/server/rest_api_im/)

