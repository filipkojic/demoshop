<?php

namespace Application\Business\DomainModels;

/**
 * Class DomainAdmin
 *
 * Represents an admin entity in the domain model.
 */
class DomainAdmin
{
    /**
     * DomainAdmin constructor.
     *
     * @param int $id The unique identifier of the admin.
     * @param string $username The username of the admin.
     * @param string $password The hashed password of the admin.
     * @param string|null $token The token used for authentication or session management, nullable.
     */
    public function __construct(
        private int     $id,
        private string  $username,
        private string  $password,
        private ?string $token = null
    )
    {
    }

    /**
     * Get the unique identifier of the admin.
     *
     * @return int The ID of the admin.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the username of the admin.
     *
     * @return string The username of the admin.
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Get the hashed password of the admin.
     *
     * @return string The hashed password.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Get the token used for authentication or session management.
     *
     * @return string|null The token, or null if not set.
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Set the token used for authentication or session management.
     *
     * @param string|null $token The token to be set.
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }
}
