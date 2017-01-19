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
    'LOG'=>'',
    'DB'=>array(
        'Mysql'=>array(
            'dsn'=>'mysql:host=localhost;',
            'dbName'=>'vagrant',
            'username'=>'vagrant',
            'password'=>'vagrant',
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
    'UP_TYPE'=>array(
       'Image'=>array(
           'gif',
           'jpeg',
           'jpg',
           'png'
       ),
       'Text'=>array(
        'txt',
        'Log',
        'doc',
        'docx',
        'zip'
       ),
       'Exe'=>array(
           'bat',
           'exe'
       )
    )
);