<?php

namespace Infrastructure\Utility;

/**
 * Class SessionManager
 *
 * Singleton class to manage PHP sessions.
 */
class SessionManager extends Singleton
{
    /**
     * SessionManager constructor.
     *
     * Starts the session if it is not already started.
     */
    protected function __construct()
    {
        parent::__construct();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Get a value from the session.
     *
     * @param string $key The key to retrieve.
     * @return mixed|null The value associated with the key, or null if not set.
     */
    public function get(string $key)
    {
        return GlobalWrapper::getSessionValue($key);
    }

    /**
     * Set a value in the session.
     *
     * @param string $key The key to set.
     * @param mixed $value The value to set.
     */
    public function set(string $key, $value): void
    {
        GlobalWrapper::setSessionValue($key, $value);
    }

    /**
     * Unset a value in the session.
     *
     * @param string $key The key to unset.
     */
    public function unset(string $key): void
    {
        GlobalWrapper::unsetSessionValue($key);
    }

    /**
     * Destroy the session.
     */
    public function destroy(): void
    {
        GlobalWrapper::destroySession();
        self::$instances[static::class] = null;
    }

    /**
     * Set a cookie.
     *
     * @param string $name The name of the cookie.
     * @param string $value The value of the cookie.
     * @param int $expiry The time the cookie expires (in seconds). Default is 0 (end of session).
     * @param string $path The path on the server in which the cookie will be available on. Default is '/'.
     */
    public function setCookie(string $name, string $value, int $expiry = 0, string $path = '/'): void
    {
        GlobalWrapper::setCookie($name, $value, $expiry, $path);
    }

    /**
     * Get a cookie value.
     *
     * @param string $name The name of the cookie to retrieve.
     * @return mixed|null The value of the cookie, or null if not set.
     */
    public function getCookie(string $name): mixed
    {
        return GlobalWrapper::getCookie($name);
    }

    /**
     * Unset a cookie.
     *
     * @param string $name The name of the cookie to unset.
     * @param string $path The path on the server in which the cookie was available. Default is '/'.
     */
    public function unsetCookie(string $name, string $path = '/'): void
    {
        GlobalWrapper::unsetCookie($name, $path);
    }
}
