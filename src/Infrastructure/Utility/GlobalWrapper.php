<?php

namespace Infrastructure\Utility;

use Exception;

class GlobalWrapper
{
    /**
     * Get a global array by name.
     *
     * @param string $globalName The name of the global array.
     * @return array The global array.
     * @throws Exception If the global array does not exist.
     */
    public static function getGlobal(string $globalName): array
    {
        switch ($globalName) {
            case '_GET':
                return $_GET;
            case '_POST':
                return $_POST;
            case '_SESSION':
                return $_SESSION;
            case '_SERVER':
                return $_SERVER;
            case '_REQUEST':
                return $_REQUEST;
            case '_FILES':
                return $_FILES;
            case '_ENV':
                return $_ENV;
            case '_COOKIE':
                return $_COOKIE;
            default:
                throw new Exception("Global variable $globalName does not exist.");
        }
    }

    /**
     * Get all headers from the global request.
     *
     * @return array An associative array of all the headers.
     */
    public static function getAllHeaders(): array
    {
        return getallheaders();
    }

    /**
     * Get a value from the session.
     *
     * @param string $key The key to retrieve.
     * @return mixed|null The value associated with the key, or null if not set.
     */
    public static function getSessionValue(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Set a value in the session.
     *
     * @param string $key The key to set.
     * @param mixed $value The value to set.
     */
    public static function setSessionValue(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Unset a value in the session.
     *
     * @param string $key The key to unset.
     */
    public static function unsetSessionValue(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroy the session.
     */
    public static function destroySession(): void
    {
        session_unset();
        session_destroy();
    }

    /**
     * Set a cookie.
     *
     * @param string $name The name of the cookie.
     * @param string $value The value of the cookie.
     * @param int $expiry The time the cookie expires (in seconds).
     * @param string $path The path on the server in which the cookie will be available on.
     */
    public static function setCookie(string $name, string $value, int $expiry, string $path = '/'): void
    {
        setcookie($name, $value, $expiry, $path);
    }

    /**
     * Get a cookie value.
     *
     * @param string $name The name of the cookie to retrieve.
     * @return mixed|null The value of the cookie, or null if not set.
     */
    public static function getCookie(string $name): mixed
    {
        return $_COOKIE[$name] ?? null;
    }

    /**
     * Unset a cookie.
     *
     * @param string $name The name of the cookie to unset.
     * @param string $path The path on the server in which the cookie was available.
     */
    public static function unsetCookie(string $name, string $path = '/'): void
    {
        setcookie($name, '', time() - 3600, $path);
    }
}
