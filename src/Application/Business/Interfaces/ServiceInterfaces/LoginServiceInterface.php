<?php

namespace Application\Business\Interfaces\ServiceInterfaces;

/**
 * Interface LoginServiceInterface
 *
 * This interface defines the contract for login service.
 */
interface LoginServiceInterface
{

    /**
     * Check if the user is logged in by verifying the presence of 'user_id' in the session.
     *
     * @return bool True if the user is logged in, false otherwise.
     */
    public function isUserLoggedIn(): bool;

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
