<?php

namespace Guangzhong\Xhgui;

/**
 * Loads and reads config file.
 */
class Config
{
    private static $_config = [];

    /**
     * all the currently loaded configuration.
     *
     * @param array $config
     *
     * @return void
     */
    public static function load($config)
    {
        self::$_config = array_merge(self::$_config, $config);
    }

    /**
     * Read a config value.
     *
     * @param string $name The name of the config variable
     *
     * @return The value or null.
     */
    public static function read($name)
    {
        if (isset(self::$_config[$name])) {
            return self::$_config[$name];
        }

        return null;
    }

    /**
     * Get all the configuration options.
     *
     * @return array
     */
    public static function all()
    {
        return self::$_config;
    }

    /**
     * Write a config value.
     *
     * @param string $name The name of the config variable
     * @param mixed  $value The value of the config variable
     *
     * @return void
     */
    public static function write($name, $value)
    {
        self::$_config[$name] = $value;
    }

    /**
     * Clear out the data stored in the config class.
     *
     * @return void
     */
    public static function clear()
    {
        self::$_config = [];
    }

    /**
     * Called during profiler initialization
     *
     * Allows arbitrary conditions to be added configuring how
     * Xhgui profiles runs.
     *
     * @return boolean
     */
    public static function shouldRun()
    {
        return mt_rand(0, 100) < (int)self::read('profiler.enable');
    }
}
