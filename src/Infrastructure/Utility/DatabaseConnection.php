<?php

namespace Infrastructure\Utility;

use PDO;
use PDOException;

/**
 * Class DatabaseConnection
 *
 * Manages a single instance of the PDO connection using the Singleton pattern.
 */
class DatabaseConnection extends Singleton
{
    private ?PDO $connection = null;

    protected function __construct()
    {
        parent::__construct();
        try {
            $dbHost = getenv('DB_HOST');
            $dbPort = getenv('DB_PORT');
            $dbDatabase = getenv('DB_DATABASE');
            $dbUsername = getenv('DB_USERNAME');
            $dbPassword = getenv('DB_PASSWORD');

            $this->connection = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbDatabase", $dbUsername, $dbPassword);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit;
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
