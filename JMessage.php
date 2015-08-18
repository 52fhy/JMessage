<?php

/**
 * JMessage.php
 *
 * 极光IM API 为开发者提供 IM 相关功能的 HTTP API。
 *
 * 极光IM（英文名 JMessage）致力于帮助 App 解决 IM 聊天问题。其核心能力在于 IM 聊天本身。
 * 其他的附属功能是可选的。 开发者可选择只是单纯注册用户，然后让这些用户之间互发消息，而不使用其他附加功能
 *
 * $Author: 飞鸿影~
 * 2015年7月22日 下午4:30:00
 */
class JMessage
{

    private $appkey;
    private $masterkey;
    private $url;

    public function __construct()
    {
        $this->appkey = '';
        $this->masterkey = '';
        $this->url = 'https://api.im.jpush.cn';

        if (empty ($this->appkey) || empty ($this->masterkey)) {
            return false;
        }
    }

    /**
     * 1.用户注册与登录
     */

    /**
     * 注册单个用户
     *
     * @param $options ['username'] 用户名
     * @param $options ['password'] 密码
     */
    public function openRegister($options)
    {
        $options = array($options);
        return $this->accreditRegister($options);
    }

    /**
     * 批量注册用户到极光IM 服务器，一次批量注册最多支持500个用户
     *
     * @param $options ['username'] 用户名
     * @param $options ['password'] 密码 。极光IM服务器会MD5加密保存
     * 传二维数组
     *
     * @example  [{"username": "dev_fang", "password": "password"}, {"username": "dev_fang", "password": "password"}]
     */
    public function accreditRegister($options)
    {
        $url = $this->url . "/v1/users/";
        $result = $this->postCurl($url, $options);
        return $result;
    }

    /**
     * 管理员注册
     * @param $options ['username'] 用户名
     * @param $options ['password'] 密码 。极光IM服务器会MD5加密保存
     *
     * @example {"username": "dev_fang", "password": "password"}
     */
    public function adminRegister($options)
    {
        $url = $this->url . "/v1/admins/";
        $result = $this->postCurl($url, $options);
        return $result;
    }

    /**
     * 获取应用管理员列表
     * @param $options ['start'] 起始记录位置 从0开始
     * @param $options ['count'] 查询条数 最多支持500条
     */
    public function getAdminsList($options = array())
    {
        $start = empty($options['start']) ? 0 : $options['start'];
        $count = empty($options['count']) ? 20 : $options['count'];

        $url_suffix = sprintf('/v1/admins?start=%d&count=%d', $start, $count);
        $url = $this->url . $url_suffix;
        $result = $this->postCurl($url, '', '', 'GET');
        return $result;
    }


    /**
     * 2.消息相关
     */


    /**
     * 发送消息
     * @param string $from_user 发送者的username
     * @param string $username 接收者,single填username, group 填gid
     * @param string $content 消息内容
     * @param string $target_type 发送目标类型 single - 个人，group - 群组
     * @param array $ext 扩展信息
     */
    public function sendMsg($username, $content, $target_type = "single", $ext = array(), $from_user = "admin")
    {
        $url = $this->url . "/v1/messages";

        $options = array(
            "version" => 1, //版本号
            "target_type" => $target_type,  //发送目标类型 single - 个人，group - 群组
            "target_id" => $username, //目标id single填username, group 填gid
            "from_type" => "admin", //发消息着身份 当前只限admin
            "from_id" => $from_user,    //发送者的username
            "msg_type" => "text",   //发消息类型 当前只限text
            "msg_body" => array(    //消息体
                "extras" => $ext, //选填的json对象 开发者可以自定义extras里面的key value
                "text" => $content    //消息内容
            )
        );

        $result = $this->postCurl($url, $options);
        return $result;
    }


    /**
     * 3.用户维护
     */

    /**
     * 获取指定用户详情
     *
     * @param $username 用户名
     */
    public function getUserDetails($username)
    {
        $url = $this->url . "/v1/users/" . $username;
        $result = $this->postCurl($url, '', '', "GET");
        return $result;
    }

    /**
     * 获取指定用户详情
     * @see getUserDetails
     */
    public function UserDetails($username)
    {
        return $this->getUserDetails($username);
    }

    /**
     * 更新指定用户信息
     *
     * @param $username 用户名
     * @param array $options 更新内容，例支持nickname,avatar,birthday,gender,region,address
     */
    public function updateUser($username, $options)
    {
        $url = $this->url . "/v1/users/" . $username;
        $result = $this->postCurl($url, $options, '', "PUT");
        return $result;
    }

    /**
     * 重置用户密码
     *
     * @param string $username 用户名
     * @param string $newpassword 新密码
     */
    public function editPassword($username, $newpassword)
    {
        $url = $this->url . "/v1/users/" . $username . '/password';

        $options = array('new_password' => $newpassword);

        $result = $this->postCurl($url, $options, '', "PUT");
        return $result;
    }

    /**
     * 删除用户
     *
     * @param $username 用户名
     */
    public function deleteUser($username)
    {
        $url = $this->url . "/v1/users/" . $username;
        $result = $this->postCurl($url, '', '', "DELETE");
        return $result;
    }

    /**
     * 获取用户列表
     * @param $options ['start'] 起始记录位置 从0开始
     * @param $options ['count'] 查询条数 最多支持500条
     */
    public function getAllUsers($options = array())
    {
        $start = empty($options['start']) ? 0 : $options['start'];
        $count = empty($options['count']) ? 20 : $options['count'];

        $url_suffix = sprintf('/v1/users/?start=%d&count=%d', $start, $count);
        $url = $this->url . $url_suffix;

        $result = $this->postCurl($url, '', '', "GET");
        return $result;
    }



    /**
     * 4.群组维护
     */

    /**
     * 创建群组
     * @param string $option ['name']  群组名称(0~64Byte)
     * @param string $option ['desc']  群组描述(0~250Byte)
     * @param string $option ['owner_username']  群主的username(4-128Byte)
     * @param array $option ['members_username']  群组成员（可选）
     * @param datetime $option ['ctime']  创建时间（可选）
     * @param datetime $option ['mtime']  最后修改时间（可选）
     * @param int $option ['level']  群组的等级 1 - 最大人数40，2 - 最大人数100，3 - 最大人数 200， 4 最大人数 500（可选）,默认3
     * @return mixed
     */
    public function createGroups($option)
    {
        $url = $this->url . "/v1/groups/";
        $result = $this->postCurl($url, $option);
        return $result;
    }

    /**
     * 获取群组详情
     *
     * @param $group_id
     */
    public function getGroupDetails($group_id)
    {
        $url = $this->url . "/v1/groups/" . $group_id;
        $result = $this->postCurl($url, '', '', "GET");
        return $result;
    }

    /**
     * 获取群组详情
     * @see getGroupDetails()
     */
    public function chatGroupsDetails($group_id)
    {
        return $this->getGroupDetails($group_id);
    }

    /**
     * 更新群组信息
     *
     * @param $group_id 群组id
     * @param string $option ['name']  群组名称(0~64Byte)
     * @param string $option ['desc']  群组描述(0~250Byte)
     */
    public function updateGroupsDetails($group_id, $options)
    {
        $url = $this->url . "/v1/groups/" . $group_id;
        $result = $this->postCurl($url, $options, '', "PUT");
        return $result;
    }

    /**
     * 删除群组
     *
     * @param $group_id 群组id
     */
    public function deleteGroups($group_id)
    {
        $url = $this->url . "/v1/groups/" . $group_id;
        $result = $this->postCurl($url, '', '', "DELETE");
        return $result;
    }

    /**
     * 添加群组成员
     *
     * @param $group_id 群组id
     * @param $username 用户名，多个用户使用数组
     */
    public function addGroupsUser($group_id, $username)
    {
        if (is_string($username)) $username = array($username);

        return $this->updateGroupsUser($group_id, $username, 'add');
    }

    /**
     * 删除群组成员
     *
     * @param $group_id 群组id
     * @param $username 用户名，多个用户使用数组
     */
    public function delGroupsUser($group_id, $username)
    {
        if (is_string($username)) $username = array($username);

        return $this->updateGroupsUser($group_id, $username, 'remove');
    }

    /**
     * 更新群组成员
     * 批量增加与删除某 gid 群组的成员。群组成员将收到增加与删除成员的通知
     *
     * @example 单独使用时：
     *   {"add":["test1", "test2"],"remove":["test3", "test4"]}
     */
    public function updateGroupsUser($group_id, $options, $action = null)
    {
        $url = $this->url . "/v1/groups/" . $group_id . '/members';

        if (is_null($action)) {
            $data = $options;
        } else {
            switch ($action) {
                case 'add' :
                    $data = array('add' => $options);
                    break;
                case 'remove' :
                    $data = array('remove' => $options);
                    break;
                default:
                    exit('action not allowed');
                    break;
            }
        }

        $result = $this->postCurl($url, $data, '', "POST");
        return $result;
    }

    /**
     * 获取指定群组成员列表
     *
     * @param $group_id 群组id
     */
    public function getGroupsUser($group_id)
    {
        $url = $this->url . "/v1/groups/" . $group_id . '/members';
        $result = $this->postCurl($url, '', '', "GET");
        return $result;
    }

    /**
     * 获取某用户的群组列表
     *
     * @param $username 用户名
     */
    public function getGroupsByUser($username)
    {
        $url = $this->url . "/v1/users/" . $username . '/groups';
        $result = $this->postCurl($url, '', '', "GET");
        return $result;
    }

    /**
     * 获取当前应用的群组列表
     * @param $options ['start'] 起始记录位置 从0开始
     * @param $options ['count'] 查询条数 最多支持500条
     */
    public function getAllGroups($options = array())
    {
        $start = empty($options['start']) ? 0 : $options['start'];
        $count = empty($options['count']) ? 50 : $options['count'];

        $url_suffix = sprintf('/v1/groups/?start=%d&count=%d', $start, $count);
        $url = $this->url . $url_suffix;

        $result = $this->postCurl($url, '', '', "GET");
        return $result;
    }

    /**
     * 获取app中所有的群组
     * @see getAllGroups()
     */
    public function chatGroups($options)
    {
        return $this->getAllGroups($options);
    }


    /**
     * 获取HTTP HEADER
     */
    private function getHttpAuthHeader($header = array())
    {
        $basic = base64_encode($this->appkey . ":" . $this->masterkey);
        $header_array = array();
        $header_array[] = "Authorization: Basic " . $basic;
        $header_array[] = "Content-type: application/json; charset=utf-8";

        if (!empty($header)) {
            return array_merge($header_array, $header);
        }

        return $header_array;
    }

    /**
     * CURL Post
     */
    private function postCurl($url, $option, $header = array(), $type = 'POST')
    {
        $header = $this->getHttpAuthHeader($header);

        $ssl = stripos($url, 'https://') === 0 ? true : false;

        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        if ($ssl) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        }
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); //在HTTP请求中包含一个"User-Agent: "头的字符串。	
        curl_setopt($curl, CURLOPT_HEADER, 0); //启用时会将头文件的信息作为数据流输出。

        if (!empty ($option)) {
            $options = json_encode($option);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $options); // Post提交的数据包
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环

        if (!empty($header)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header); // 设置HTTP头
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);

        $result = curl_exec($curl); // 执行操作

        $res['status'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $res = json_decode($result, true);
        /*   if(empty($res)){
               $res['result'] = $result;
           }*/

        curl_close($curl); // 关闭CURL会话

        return $res;
    }
}
