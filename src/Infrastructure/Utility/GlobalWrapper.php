<?php

namespace Infrastructure\Utility;

/**
 * Class GlobalWrapper
 *
 * A utility class for safely accessing and manipulating global PHP variables.
 * This class provides methods to retrieve, set, and unset values from common
 * global arrays such as $_GET, $_POST, $_SESSION, $_SERVER, and $_COOKIE.
 * Additionally, it offers methods to manage HTTP headers and cookies.
 */
class GlobalWrapper
{
    /**
     * Get the $_GET array.
     *
     * @return array The $_GET array.
     */
    public static function getGet(): array
    {
        return $_GET;
    }

    /**
     * Get the $_POST array.
     *
     * @return array The $_POST array.
     */
    public static function getPost(): array
    {
        return $_POST;
    }

    /**
     * Get the $_SESSION array.
     *
     * @return array The $_SESSION array.
     */
    public static function getSession(): array
    {
        return $_SESSION;
    }

    /**
     * Get the $_SERVER array.
     *
     * @return array The $_SERVER array.
     */
    public static function getServer(): array
    {
        return $_SERVER;
    }

    /**
     * Get the $_REQUEST array.
     *
     * @return array The $_REQUEST array.
     */
    public static function getRequest(): array
    {
        return $_REQUEST;
    }

    /**
     * Get the $_FILES array.
     *
     * @return array The $_FILES array.
     */
    public static function getFiles(): array
    {
        return $_FILES;
    }

    /**
     * Get the $_ENV array.
     *
     * @return array The $_ENV array.
     */
    public static function getEnv(): array
    {
        return $_ENV;
    }

    /**
     * Get the $_COOKIE array.
     *
     * @return array The $_COOKIE array.
     */
    public static function getCookie(): array
    {
        return $_COOKIE;
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
    public static function getCookieValue(string $name): mixed
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
