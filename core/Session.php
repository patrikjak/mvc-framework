<?php


namespace app\core;


class Session
{
    protected const FLASH_KEY = 'flash_messages';

    public function __construct()
    {
        session_start();
        $flash_messages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flash_messages as $key => &$flash_message) {
            $flash_message['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flash_messages;
    }

    public function setFlash($key, $message)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    /**
     *
     * Get flash message for given key
     *
     * @param $key
     * @return false|string
     */
    public function getFlash($key)
    {
        return isset($_SESSION[self::FLASH_KEY][$key]['value']) ? $_SESSION[self::FLASH_KEY][$key]['value'] : false;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroy session variables
     */
    public function __destruct()
    {
        $flash_messages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flash_messages as $key => &$flash_message) {
            if ($flash_message['remove']) {
                unset($flash_messages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flash_messages;
    }
}