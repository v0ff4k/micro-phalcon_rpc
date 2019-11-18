<?php

namespace app\plugins;

/**
 * Class Password
 * Make/Compare, nothing interesting.
 *
 * @package plugins
 */
class Password
{

    /**
     * Make hash from string
     * yeah, 12 - symfony style.
     *
     * @param string $str
     * @return bool|string
     */
    public static function make(string $str)
    {
        return password_hash($str, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Comparing passwords
     *
     * @param string $string
     * @param string $hashedString
     * @return bool
     */
    public static function compare(string $string, string $hashedString)
    {
        return password_verify($string, $hashedString);
    }

    /**
     * Get info about hashed string.
     *
     * @param string $hash
     * @return array [algo, algoName, options]
     */
    public static function info(string $hash)
    {

        return password_get_info($hash);
    }
}
