<?php


namespace Brace\Core;


class AppLoader
{

    private static $appRoot = __DIR__ . "/../../../../app/";

    private static $apps = [];

    private static $appFilesAreLoaded = false;

    public static function SetAppRoot(string $appRoot = __DIR__ . "/../../../../app/") : void
    {
        self::$appRoot = $appRoot;
    }


    /**
     * Define a Application
     *
     * <example>
     * AppBuilder::extend(function(BraceApp $app) {
     *      $app->addModule(...)
     * });
     * </example>
     *
     * @param callable $loaderFn
     * @param string $alias
     */
    public static function extend(callable $loaderFn, string $alias = "Default") : void
    {
        if ( ! isset (self::$apps[$alias]))
            self::$apps[$alias] = [];
        self::$apps[$alias][] = $loaderFn;
    }


    private static function requireFilesFromAppDir(string $appRoot) : void
    {
        if (self::$appFilesAreLoaded) {
            return; // Files are already loaded
        }
        self::$appFilesAreLoaded = true;

        if ( ! is_dir($appRoot))
            throw new \InvalidArgumentException("App directory is not a directory: '$appRoot'");
        $dir = opendir($appRoot);
        if ($dir === false)
            throw new \InvalidArgumentException("Cannot open app root directory: '$appRoot'");

        $loaderFiles = [];
        while ( false !== ($file = readdir($dir))) {
            if ( ! preg_match("/^([0-9]{2,2})_[a-z0-9.-_]+\.php$/i", $file, $mathces)) {
                continue;
            }
            $loaderFiles[] = $file;
        }
        asort($loaderFiles);
        foreach ($loaderFiles as $loaderFile) {
            require $appRoot . "/" . $loaderFile;
        }
    }

    /**
     * Load a application. Will require all php files from app folder, run
     * the init scripts and return the App.
     *
     * @param string $alias
     * @return BraceApp
     */
    public static function loadApp(string $alias = "Default") : BraceApp
    {
        self::requireFilesFromAppDir(self::$appRoot);

        if ( ! isset (self::$apps[$alias]))
            throw new \InvalidArgumentException("App '$alias' is not defined.");

        $app = null;
        foreach (self::$apps[$alias] as $loaderFn) {
            $return = $loaderFn($app);
            if ($return !== null) {
                if ( ! $return instanceof BraceApp)
                    throw new \InvalidArgumentException("Loader must return null or instance of BraceApp");
                $app = $return;
            }
        }
        return $app;
    }

}
