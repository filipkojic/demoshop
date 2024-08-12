<?php

namespace Application\Business\Interfaces\ServiceInterfaces;

interface LoginServiceInterface
{
    /**
     * Attempt to log in with the given username and password.
     *
     * @param string $username
     * @param string $password
     * @return bool True if login is successful, false otherwise.
     */
    public function login(string $username, string $password): bool;
}