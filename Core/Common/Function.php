<?php
/**
 * Created by PhpStorm.
 * User: milo
 * Date: 11/8/2016
 * Time: 4:15 PM
 */
/**
 * @param $var
 */
defined('CORE_PATH') or exit();
function Conf()
{
    return \Core\libs\Conf::getInstance();
}
function Model()
{
    return \Core\libs\Model::getInstance();
}
function Route()
{
    return \Core\libs\Route::getInstance();
}
function VerifyCode()
{
    return \Core\Libs\VerifyCode::getInstance();
}
function Template()
{
    return \Core\libs\Template::getInstance();
}