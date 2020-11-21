<?php

namespace core\base\settings;

use core\base\settings\Settings;


class ShopSettings
{
    static private $_instance;
    private $baseSettings;

    private $routes = [

        'plugins' => [
            'path' => 'core/plugins/',
            'hrUrl' => false,
            'dir' => 'controller',
            'routes' => [

            ]
        ],
    ];

    private $teplateArr = [
        'text' => ['price', 'short', 'name'],
        'textarea' => ['goods_content']
    ];
//геттер
    static public function get($property){
        return self::instance()->$property;
    }
//шаблон программирования singltone
    static public function instance(){
        //проверяем хранится ли в свойствве $_instance объект класса, если да, то возвращаем этот объект
        if(self::$_instance instanceof self){
            return self::$_instance;
        }
        //если нет, то создаем объект класса  и записываем его в свойство $_instance
        self::$_instance = new self;
        //записываем в свойство $baseSettings ссылку на объект класса Settings
        self::$_instance->baseSettings = Settings::instance();
        //записываем в $baseProperies $baseSettings и вызываем метод clueProperties в качестве аргумента явлдяется текущий класс
        $baseProperties = self::$_instance->baseSettings->clueProperties(get_class());
        //
        self::$_instance->setProperty($baseProperties);

        return self::$_instance;
    }
//сеттер
    private function setProperty($properties) {
        //
        if ($properties){
            foreach ($properties as $name => $property){
                //
                $this->$name = $property;
            }
        }
    }

    private function __construct()
    {
    }

    private function __clone()
    {

    }
}