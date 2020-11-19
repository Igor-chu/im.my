<?php

namespace core\base\controllers;

use core\base\settings\Settings;
use core\base\settings\ShopSettings;

class RouteController
{
    static private $_instance;

    private function __clone()
    {

    }
//шаблон программирования singltone
    static public function getInstance(){
        //проверяем хранится ли в свойствве $_instance объект класса  то возвращаем этот объект
        if(self::$_instance instanceof self){
            return self::$_instance;
        }
        //если не, то создаем объект класса  и записываем его в свойство $_instance
        return self::$_instance = new self;
    }
    private function __construct()
    {
        $s = Settings::instance();
        $s1 = ShopSettings::instance();

        exit();
    }
}