<?php
/**
 * Created by PhpStorm.
 * User: young
 * Date: 2/27/14
 * Time: 12:02 PM
 */
class AutoLoader {

    static private $classNames = array();

    /**
     * Store the filename (sans extension) & full path of all ".php" files found
     */
    public static function registerDirectory($dirName) {
echo $dirName.'<br />';
        $di = new DirectoryIterator($dirName);
        var_dump($di);
        foreach ($di as $file) {
            var_dump($file);
            if ($file->isDir() && !$file->isLink() && !$file->isDot()) {
                // recurse into directories other than a few special ones
                self::registerDirectory($file->getPathname());
            } elseif (substr($file->getFilename(), -4) === '.php') {
                // save the class name / path of a .php file found
                $className = substr($file->getFilename(), 0, -4);
                echo $className;
                AutoLoader::registerClass($className, $file->getPathname());

            }
        }
    }

    public static function registerClass($className, $fileName) {
        AutoLoader::$classNames[$className] = $fileName;
    }

    public static function loadClass($className) {
        if (isset(AutoLoader::$classNames[$className])) {
            require_once(AutoLoader::$classNames[$className]);
        }
    }

}

spl_autoload_register(array('AutoLoader', 'loadClass'));

?>