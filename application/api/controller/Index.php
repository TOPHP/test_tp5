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

    public function show($data)
    {

        return $this->decryptData(json_encode(array(
            "curpage" => "1",
            "status" => "10000",
            "msg" => "1",
            "data" => $data,
        )));
    }

    public function sendSms($mobile = 15510002127)//18500314781)
    {

        $code = mt_rand(1000, 9999);
        $code = "9999";
        $AliSMS = new SmsGateWay();

        $AliSMS->send($mobile, ['code' => $code, 'name' => "张敏", 'time' => "15:30"], 'SMS_67305968');

        if ($AliSMS) {
            return "发送成功";
        } else {
            return "发送失败";
        }
        $c = new TopClient;
        $c->appkey = "appkey";
        $c->secretKey = "secret";
        $req = new AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend("123456");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("阿里大于");
        $req->setSmsParam("{\"code\":\"1234\",\"product\":\"alidayu\"}");
        $req->setRecNum("13000000000");
        $req->setSmsTemplateCode("SMS_585014");
        $resp = $c->execute($req);
    }
}
