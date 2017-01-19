<?php
/**
 * 数据库处理加强
 * 内存数据库的处理
 * Ｎｏｓｑｌ数据库的处理
 * 日志的处理
 * Ｃｏｍｐｏｓｅｒ加入第三方库的处理
 * 二维码生成
 * ＥＸＣＥＬ处理
 * 邮件发送goknew bemait
 * "phpoffice/phpexcel": "*",
    "phpunit/phpunit": "*",
    "endroid/qrcode": "*",
    "nette/mail": "*",
    "phpmailer/phpmailer": "^5.2",
    "predis/predis": "*"
 */
define('APP_NAME','App');
define('DEBUG',false);
define('URL_SECRET',false);
require ('./Core/MiloCore.class.php');
\Core\MiloCore::run();





