<?php
/**
 * Created by PhpStorm.
 * User: milo
 * Date: 12/14/2016
 * Time: 4:11 PM
 */
defined('CORE_PATH') or exit();
return array(
    'API'=>array(
        'type'=>'json'
    ),
    'DB'=>array(
        'Mysql'=>array(
            'dsn'=>'mysql:host=localhost;',
            'dbName'=>'milo',
            'username'=>'root',
            'password'=>'milo2007',
            'charset'=>'utf8',
            'port'=>'3306'
        ),
        'Redis'=>array(

        ),
        'Mongodb'=>array(

        ),
        'Sqlite'=>array(

        ),
        'Memcached'=>array(

        ),
    ),
    'EXT'=>array(
    ),
    'SLD'=>array(

    ),
    'UP_TYPE'=>array(
       'type'=>array(
           'gif',
           'jpeg',
           'jpg',
           'png'
       ),
       'size'=>204800
    )
);