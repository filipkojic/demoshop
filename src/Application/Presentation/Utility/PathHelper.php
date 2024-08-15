<?php

namespace Application\Presentation\Utility;

/**
 * Class PathHelper
 *
 * A utility class that provides methods to retrieve paths to various important files and directories in the application.
 * This class helps to avoid hardcoding file paths throughout the application, promoting cleaner and more maintainable code.
 */
class PathHelper
{
    /**
     * Get the full path to a view file.
     *
     * @param string $viewName The name of the view file.
     * @return string The full path to the view file.
     */
    public static function view(string $viewName): string
    {
        return __DIR__ . '/../../Presentation/Views/' . $viewName;
    }

    /**
     * Get the full path to the .env file.
     *
     * @return string The full path to the .env file.
     */
    public static function env(): string
    {
        return __DIR__ . '/../../../../';
    }

}

