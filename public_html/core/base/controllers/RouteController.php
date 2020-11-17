<?php

namespace core\base\controllers;
use core\base\settings\Settings;

class RouteController
{
    static private $_instance;

    private function __clone()
    {

    }
//шаблон программирования singltone
    static public function getInstance(){
        //если в свойствве $_instance храниться объект класса то возвращаем
        if(self::$_instance instanceof self){
            return self::$_instance;
        }
        //создаем объект
        return self::$_instance = new self;
    }
    private function __construct()
    {
        $s = Settings::get('routes');
        exit();
    }
}