<?php

namespace app\api\controller;

use anerg\Alidayu\SmsGateWay;
use app\api\model\Pic;
use think\Controller;
use think\Request;

class Index extends Controller
{
    public function index()
    {
        return "hhaaaa";
    }

    public function getpic()
    {
//        $key = '12345678';
//        $plain = '0123ABCD!@#$中文';
//        $sign = new signUtils2();
//        return $sign->encrypt($key, $plain);

        $pic = new Pic();
        $pics = $pic->getPics();
        foreach ($pics as $pic) {
            $data[] = array(
                "wallPaperId" => $pic['id'],
                "wallPaperType" => '',
                "wallPaperName" => '',
                "wallPaperDesigner" => '',
                "wallPaperUrl" => $this->getPicUrl($pic['url'], false),
                "wallPaperDesc" => '',
                "wallPaperUrls" => $this->getPicUrl($pic['url'], true),

            );
        }
        return $this->show($data);
    }

    public function getPicUrl($name, $isSmall)
    {
        $request = Request::instance();
        $root = Request::instance()->root();
        if ($isSmall) {
            $statc = $root . '/static/pic/small/' . $name;
        } else {
            $statc = $root . '/static/pic/' . $name;
        }
        return $request->domain() . $statc;
    }

    public function decryptData($data)
    {
        $keys = "627746bcfe761bdeab1093147cd86e78";
        $ivs = "01234567";
        $des = new DES3($keys, $ivs);
        $ret = $des->encrypt($data);
        return $ret;
    }

    public function show($data, $status = 10000, $msg = "succ", $isDes = true)
    {

        return $isDes ? $this->decryptData(json_encode(array(
            "curpage" => "1",
            "status" => $status,
            "msg" => $msg,
            "data" => $data,
        ))) : json_encode(array(
            "curpage" => "1",
            "status" => $status,
            "msg" => $msg,
            "data" => $data,
        ));
    }

    public function sendSms($mobile = 15510002127)//18500314781)
    {
        $code = mt_rand(1000, 9999);
        $AliSMS = new SmsGateWay();
        $AliSMS->send($mobile, ['code' => $code], 'SMS_76420053');

//        $code = mt_rand(1000, 9999);
//        $code = "9999";
//        $AliSMS = new SmsGateWay();
//
//        $AliSMS->send($mobile, ['code' => $code, 'name' => "张敏", 'time' => "15:30"], 'SMS_76420053');
//
//        if ($AliSMS) {
//            return "发送成功";
//        } else {
//            return "发送失败";
//        }
//        $c = new TopClient;
//        $c->appkey = "appkey";
//        $c->secretKey = "secret";
//        $req = new AlibabaAliqinFcSmsNumSendRequest;
//        $req->setExtend("123456");
//        $req->setSmsType("normal");
//        $req->setSmsFreeSignName("阿里大于");
//        $req->setSmsParam("{\"code\":\"1234\",\"product\":\"alidayu\"}");
//        $req->setRecNum("13000000000");
//        $req->setSmsTemplateCode("SMS_585014");
//        $resp = $c->execute($req);
    }

    public function login($username, $password)
    {
        if (!$username || !$password) {
            return $this->show("", 400, "用户名或密码不能为空");
        }
        if (strcmp($password, "123456")) {
            return $this->show("");
        }
        return $this->show("", 404, "用户名或密码不正确");
    }

    /**
     * status 200 有网更新
     *        300 WiFi下更新
     *        100 强制更新
     *        其他不更新
     * @param int $version
     * @return string
     */
    public function upload($version = 0)
    {
        if ($version<3){
            return;
        }
        if ($version > 0) {
            $status = 200;
        }
        return json_encode(array(
            "status" => $status,
            "versioncode" => "3",
            "content" => $this->content("3.0.0","2017-7-28","12.8","这个版本非常好用！"),
            "url" =>Request::instance()->domain()."/1.apk"
        ));
    }

    private function content($vname,  $pdate,$fsize, $desc)
    {
        return "\n版本号  ： " . $vname . "\n更新时间  ：" . $pdate . "\n文件大小  ： " . $fsize . "\n\n" . $desc;
    }


}
