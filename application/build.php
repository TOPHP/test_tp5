<?php
/**
 * Created by PhpStorm.
 * User: john
 * Date: 2017/6/22
 * Time: 14:06
 */
return [
    // 定义test模块的自动生成
    'test' => [
        '__dir__'    => ['controller', 'model', 'view'],
        'controller' => ['User', 'UserType'],
        'model'      => ['User', 'UserType'],
        'view'       => ['index/index', 'index/test'],
    ],
];