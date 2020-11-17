<?php
//создаем константу
define('VG_ACCESS', true);
//отправляем заголовок браузеру (отправляется до вывода какой-либо информации)
header('Content-Type:text/html;charset=utf-8');
//стартуем сессию (создается временный файл на сервере)
session_start();
//подключаем файл настроек
require_once 'config.php';
//
require_once 'core/base/settings/internal_settings.php';

use core\base\exceptions\RouteException;
use core\base\controllers\RouteController;

try{
    //RouteController::getInstance()->route();
    RouteController::getInstance();
}
catch (RouteException $e){
    exit($e->getMessage());
}