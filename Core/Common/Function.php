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
function Model($conf)
{
    return \Core\libs\Model::getInstance($conf);
}
function Route()
{
    return \Core\libs\Route::getInstance();
}
function RandomImage()
{
    return \Core\Libs\RandomImage::getInstance();
}
function Template()
{
    return \Core\libs\Template::getInstance();
}