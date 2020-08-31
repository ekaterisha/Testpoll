<?php namespace Testpoll;
ini_set('display_errors', 1);

class Loader
{
    public static $root_dir = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;

    public static function test_load($class){
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $file = self::$root_dir.$class.'.php';
        if (file_exists($file)) {
            include_once($file);
        }
    }
}

spl_autoload_register(__NAMESPACE__.'\Loader::test_load');

rin\Base::router();

?>