<?php

namespace Application\Business\Interfaces\ServiceInterfaces;

interface LoginServiceInterface
{
    /**
     * Attempt to log in with the given username and password.
     *
     * @param string $username
     * @param string $password
     * @param bool $keepLoggedIn
     * @return bool The indicator of successfully login.
 */
    public function login(string $username, string $password, bool $keepLoggedIn): bool;
}