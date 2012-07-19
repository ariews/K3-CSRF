<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Inspiration from https://github.com/synapsestudios/kohana-csrf/blob/develop/classes/csrf.php
 */

class Kohana_CSRF
{
    /**
     * Get session name prefix
     * 
     * @return  type
     */
    private static function key($name)
    {
        return Kohana::$config->load('csrf.prefix') . '-' . $name;
    }

    /**
     * Create or get $token
     *
     * @param string $name
     * @return string
     */
    public static function get($name = 'default')
    {
        $key    = self::key($name);
        $token  = Session::instance()->get($key);

        if(NULL === $token)
        {
            $token = Text::random('alnum', rand(20, 30));
            Session::instance()->set($key, $token);
        }

        return $token;
    }

    /**
     * Clear $roken from session
     *
     * @param string $name
     */
    public static function clear($name = 'default')
    {
        Session::instance()->delete(self::key($name));
    }

    /**
     * Token validation
     *
     * @param array $values
     * @param string $name
     * @param bool $purge
     * @return bool
     */
    public static function check (array $values, $name = 'default', $purge = true)
    {
        $token = self::get($name);

        if (TRUE === $purge)
        {
            self::clear($name);
        }

        $key = self::key($name);

        return ( isset($values[$key]) && $values[$key] === $token );
    }
}
