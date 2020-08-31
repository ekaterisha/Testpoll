<?php namespace Testpoll\rin;

 class Base {
    private static $mysqli;
    private static $controllers;
    private static $instance;  // экземпляр объекта
    private function __construct(){ 
        /* ... @return Base */ }  // Защищаем от создания через new Base
    private function __clone()    { /* ... @return Base */ }  // Защищаем от создания через клонирование
    private function __wakeup()   { /* ... @return Base */ }  // Защищаем от создания через unserialize
    public static function getInstance() {    // Возвращает единственный экземпляр класса. @return Base
        if ( empty(self::$instance) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public static function router() { 
        $r = isset($_GET["r"]) ? $_GET["r"] : 'Testing'; 
        if (!isset(self::$controllers)) {
            $name_controller = __NAMESPACE__.'\Controllers\\'.$r.'Controller';
            self::$controllers = new $name_controller;
        }

        $action = isset($_GET["action"]) ? $_GET["action"] : 'read';

        switch ($action) {

            case 'create':
                self::$controllers->create();
            break;
            case 'read':
                self::$controllers->read();
            break;
            case 'check':
                self::$controllers->check();
            break;

        }
    }
    public static function mysqli(){
        if (!isset(self::$mysqli)){
            self::$mysqli = new \mysqli("192.168.1.232", "rina", "1234", "tests");
        }
        return self::$mysqli;
    }
 }


?>