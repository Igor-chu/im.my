<?php

namespace core\base\settings;

class Settings
{
    static private $_instance;
    private $routes = [
        'admin' => [
            'name' => 'admin',
            'path' => 'core/admin/controller/',
            'hrUrl' => false,
        ],
        'settings' => [
            'path' => 'core/base/settings/'
        ],
        'plugins' => [
            'path' => 'core/plugins/',
            'hrUrl' => false
        ],
        'user' => [
            'path' => 'core/user/controller/',
            'hrUrl' => true,
            'routes' => [

            ]
        ],
        'default' => [
            'controller' => 'IndexController',
            'inputMethod' => 'inputData',
            'outputMethod' => 'outputData'
        ]
    ];

    private $teplateArr = [
        'text' => ['name', 'phone', 'adress'],
        'textarea' => ['content', 'keywords']
    ];

    private $lalala = 'lalala';

    private function __construct()
    {
    }

    private function __clone()
    {
    }

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
        return self::$_instance = new self;
    }

//склеиваем массивы на вход которого приходит класс с которым мы работаем
    public function clueProperties($class){
        //определяем массив свойств, который будем возвращать
        $baseProperties = [];
//$this ссылается на объект нашего класса
        foreach ($this as $name => $item){
            //в $property сохраняем свойство класса, которое мы передали в качестве параметра $class и вызываем метод get аргументом которого является ключ($name) массива
            $property = $class::get($name);
            //проверям являются ли массивом свойсво $property  и $item
            if (is_array($property) && is_array($item)){
                //если да, то
                $baseProperties[$name] = $this->arrayMergeRecursive($this->$name, $property);
                //переход на следующую итерацию
                continue;
            }
            if(!$property) $baseProperties[$name] = $this->$name;
        }
        return $baseProperties;

    }

//создаем рекурсивный метод, который будет склеивать массивы. (объединяет функциональные возможности array_merge и array_recursive
    public function arrayMergeRecursive(){
        //функция func_get_args() получает аргументы функции. Записываем в свойство $$arrays
        $arrays = func_get_args();
        //возвращает первый элемент массива при этом удаляя его из данного массива. В массиве $arrays останеться один элемент
        $base = array_shift($arrays);

        foreach ($arrays as $array){
            foreach ($array as $key => $value){
                //проверяем является ли $value и $base[$key] массивами
                if(is_array($value) && is_array($base[$key])){
                    $base[$key] = $this->arrayMergeRecursive($base[$key], $value);
                }else {
                    if(is_int($key)){
                        //in_array проверят существует ли значение $ value в массиве $base
                        //array_push в массиве $base запишем значение $value
                        if(!in_array($value, $base)) array_push($base, $value);
                        //уход на следующую итерацию
                        continue;
                    }
                    //перезаписываем значение в $base[$key] значением $value
                    $base[$key] = $value;
                }
            }
        }
        return $base;
    }
}