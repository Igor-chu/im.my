<?php
//защищаем файл internal_settings.php от показа пользователю (почему define? define не может хранить массивы)
defined('VG_ACCESS') or die ('Access denied');
//создаем конст в которой будут храниться шаблоны пользовательской части
const TEMPLATE = 'templates/default/';
//создаем константу которая будет отвечать за пути к административной части сайта
const ADMIN_TEMPLATE = 'core/admin/view/';
//созаем константу безопасности (заставить пользователей перелогинится, изменив значение)
const COOKIE_VERSION = '1.0.0';
//создаем константу в кот ключ шифрования для алгоритма шифрования
const CRYPT_KEY = '';
//создаем константу в кот будет ограничение времени бездействия администратора
const COOKIE_TIME = 60;
//создаем константу в кот будет время блокировки пользователя попытавшегося подбирать пароли
const BLOCK_TIME = 3;
//константа постраничной навигации (отображать 8 товаров)
const QTY = 8;
//константа ссылок (отображать 3 ссылки левее и правее активной)
const QTY_LINKS = 3;
//хранятся пути к CSS и JS файлам необходимым для работы административной части сайта
const ADMIN_CSS_JS = [
    'styles' => [],
    'scripts' => []
];
//хранятся пути к CSS и JS файлам необходимым для работы пользовательской части сайта
const USER_CSS_JS = [
    'styles' => [],
    'scripts' => []
];
use core\base\exceptions\RouteException;

function autoloadMainClasses($class_name){
    //заменяем символы \ на / в $class_name
    $class_name = str_replace('\\', '/', $class_name);
    //проверяем подключен ли данный файл, если нет то генерируем исключение (@ значит не выводить ошибки)
    if(!@include_once $class_name . '.php'){
        throw new RouteException ('Не верное имя файла для подключения - ' . $class_name);
    }
}
//Регистрирует заданную функцию в качестве реализации метода __autoload()
spl_autoload_register('autoloadMainClasses');
