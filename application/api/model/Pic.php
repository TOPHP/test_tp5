<?php
namespace app\api\model;
use think\Model;

/**
 * Created by PhpStorm.
 * User: john
 * Date: 2017/5/16
 * Time: 16:39
 */
class Pic extends Model {

    public  function getPics(){
        return $this->select();
    }

}