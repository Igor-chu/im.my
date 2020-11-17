<?php
//защищаем файл config.php от показа пользователю
defined('VG_ACCESS') or die ('Access denied');
//создаем конст в кот будет хранится полный путь к сайту
const SITE_URL = 'http//im.my';
//создаем константу в которой будет хранится корень пути
const PATH = '/';
//создаем конст подключение к базе данных
const HOST = 'localhost';
//создаем конст имени пользователя
const USER = 'root';
//создаем консту пароля
const PASS = '';
//создаем консту имени базы данных
const DB_NAME = 'im';

