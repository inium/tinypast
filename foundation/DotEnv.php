<?php
/**
 * Simple DotEnv static class
 *
 * @author inlee <einable@gmail.com>
 */
namespace Foundation;

class DotEnv
{
    /**
     * Load .env file
     *
     * @param string $path      .env file stored path
     */
    public static function load($path = '.env')
    {
        $fp = fopen($path, "r");

        while (!feof($fp)) {
            $str = trim(fgets($fp));

            if (strlen($str) > 0) {
                if ($str[0] != '#') {
                    putenv(trim($str));
                }
            }
        }
    }

    /**
     * Get env value
     *
     * @param string $name      environment name
     * @param string $default   default return value if $name is not exists
     * @return string
     */
    public static function get($name, $default = null)
    {
        $val = getenv($name);
        if (!$val) {
            $val = $default;
        }

        return $val;
    }

    /**
     * Set environment value
     *
     * @param string $name      Environment name
     * @param string $value     Envrionment value
     */
    public static function set($name, $value)
    {
        putenv("{$name}={$value}");
    }
}
