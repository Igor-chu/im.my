<?php

namespace core\base\controller;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use core\base\settings\ShopSettings;

class RouteController
{
    static private $_instance;
    //свойство маршрутов
    protected $routes;
    //
    protected $controller;
    //свойство в котором будет хранится метод, который будет собирать данные из базы данных
    protected $inputMethod;
    //свойство в котором будет храниться имя метода, который будет отвечать за подключение вида
    protected $outputMethod;
    //
    protected $parameters;

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
        //из суперглобального массива получаем строку запроса и записываем в $adress_str
        $adress_str = $_SERVER['REQUEST_URI'];
        //strrpos Возвращает позицию последнего вхождения подстроки в строке
        //strlen — Возвращает длину строки
        if(strrpos($adress_str, '/') === strlen($adress_str) - 1 && strrpos($adress_str, '/') !== 0) {
            //rtrim — Удаляет пробелы (или другие символы) из конца строки
            //если '/' стоит в конце строки, то перенаправляем пользователя на ссылку без этого символа (если неудачно, то возвращаем код ответа 301)
            $this->redirect(rtrim($adress_str, '/'), 301);
        }
        //substr — Возвращает подстроку $_SERVER['PHP_SELF'] начиная с 0 и до
        $path = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], 'index.php' ));
        //PATH константа из config.php
        if($path === PATH){
            //записываем в свойство $routes настройки из класса Settings
            $this->routes = Settings::get('routes');

            //бросаем исключение, если в $routes отсутствуют настройки
            if(!$this->routes) throw new RouteException('Сайт находится на техническом обслуживании');

            //если в строке запроса присутствует admin, то работаем с админкой
            //strpos — Возвращает позицию первого вхождения подстроки
            if(strpos($adress_str, $this->routes['admin']['alias']) === strlen(PATH)){
                //записываем в $url строку запроса предварительно обрезав '/admin/'
                $url = explode('/', substr($adress_str, strlen(PATH . $this->routes['admin']['alias']) + 1));
                //проверяем существует что-либо после '/admin/' т.е. существует ли плагин и существует ли дирректория по заданному имени (
                //is_dir — Определяет, является ли имя файла директорией
                if($url[0] && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'] . $url[0])){
                    //array_shift() извлекает первое значение массива array и возвращает его, сокращая размер array на один элемент
                    $plugin = array_shift($url);
                    //записываем в $pluginSettings путь файла настроек  для плагина предварительно сформировав его
                    $pluginSettings = $this->routes['settings']['path'] . ucfirst($plugin . 'Settings');
                    //проверяем существует ли файл по заданному пути
                    if(file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettings . 'php')){
                        //переопределяем $pluginSettings, потому что не можем создать объект с "/", поэтому заменяем все'/' на '\'
                        $pluginSettings = str_replace('/', '\\', $pluginSettings);
                        //перезапишем в $this->routes новые настрой с учетом плагина
                        $this->routes = $pluginSettings::get('routes');
                    }
                    //
                    $dir = $this->routes['plugins']['dir'] ? '/' . $this->routes['plugins']['dir'] . '/' : '/';
                    //вслучае наличия, заменяем // на /
                    $dir = str_replace('//', '/', $dir);

                    $this->controller = $this->routes['plugins']['path'] . $plugin . $dir;
                    //записываем в свойство $hrURL значение из класса Settings
                    $hrUrl = $this->routes['plugins']['hrURL'];
                    //форируем ячейку маршрута
                    $route = 'plugins';

                }else{
                    //записываем в свойство $controller значение из класса Settings
                    $this->controller = $this->routes['admin']['path'];
                    //записываем в свойство $hrURL значение из класса Settings
                    $hrUrl = $this->routes['admin']['hrURL'];
                    //форируем ячейку маршрута
                    $route = 'admin';


                }
                //если нет, то работаем с пользовательским контроллером
            }else{
                //преобразуем строку запроса ($adress_str) в массив, обрезая ее (substr) с первого элемента (strlen(PATH))
                $url = explode('/', substr($adress_str, strlen(PATH)));
                //записываем значение из класса Settings
                $hrUrl = $this->routes['user']['hrUrl'];
                //определяем откуда подключать контроллер
                $this->controller = $this->routes['user']['path'];
                //создаем маршрут
                $route = 'user';
            }
            //создаем метод который будет
            $this->createRoute($route, $url);
            //
            if($url[1]){
                //записываем количество элементов в массиве $url
                $count = count($url);

                $key = '';
                //если в переменной $hrUrl - false
                if(!$hrUrl){
                    //
                    $i = 1;
                }else{
                    $this->parameters['alias'] = $url[1];
                    $i = 2;
                }

                for( ; $i < $count; $i++){
                    if(!$key){
                        //записываем в $key первый элемент строки запроса
                        $key = $url[$i];
                        //записываем в свойство $parameters  с ключом $key
                        $this->parameters[$key] = '';
                    }else{
                        $this->parameters[$key] = $url[$i];
                        $key = '';
                    }
                }
            }
            exit();
        }else{
            try{
                //бросаем исключение
                throw new \Exception('Некорректная дирректория сайта');
            }
            catch (\Exception $e){
                //ловим исключение и выводим сообщение
                exit($e->getMessage());
            }
        }
    }

    //
    private function createRoute($var, $arr){
        $route = [];

        if(!empty($arr[0])){
            if($this->routes[$var]['routes'][$arr[0]]){
                //
                $route = explode('/', $this->routes[$var]['routes'][$arr[0]]);
                //формируем имя сонтроллера
                $this->controller .= ucfirst($route[0]. 'Controller');
            }else{
                //
                $this->controller .= ucfirst($arr[0]. 'Controller');
            }
        }else{
            $this->controller .= $this->routes['default']['controller'];
        }
        //
        $this->inputMethod = $route[1] ? $route[1] : $this->routes['default']['inputMethod'];
        $this->outputMethod = $route[2] ? $route[2] : $this->routes['default']['outputMethod'];

        return;
    }
}