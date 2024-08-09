<?php

namespace Application\Presentation\Controllers;

abstract class BaseController
{
    /**
     * Common method that might be used in all controllers.
     *
     * @param string $message
     * @return void
     */
    protected function setFlashMessage(string $message): void
    {
        // Example method to set flash messages
        $_SESSION['flash_message'] = $message;
    }

    /**
     * Method to get the flash message.
     *
     * @return string|null
     */
    protected function getFlashMessage(): ?string
    {
        return $_SESSION['flash_message'] ?? null;
    }

    /**
     * Method to handle redirection.
     *
     * @param string $url
     * @return void
     */
    protected function redirect(string $url): void
    {
        header("Location: " . $url);
        exit;
    }
}
