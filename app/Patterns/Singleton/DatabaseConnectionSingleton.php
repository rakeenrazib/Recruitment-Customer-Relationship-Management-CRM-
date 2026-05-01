<?php

namespace App\Patterns\Singleton;

use PDO;
use PDOException;
use RuntimeException;

class DatabaseConnectionSingleton
{
    /**
     * Core Singleton characteristic:
     * the class stores exactly one static instance of itself.
     */
    private static ?self $instance = null;

    /**
     * The shared database connection that every caller receives.
     */
    private PDO $connection;

    /**
     * Private constructor prevents direct object creation with "new".
     * That is one of the key identifying features of Singleton.
     */
    private function __construct()
    {
        $this->connection = $this->createConnectionFromConfiguration();
    }

    /**
     * Global access point for the single DatabaseConnectionSingleton object.
     */
    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * This returns the single PDO connection managed by the Singleton.
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Prevent cloning so nobody can accidentally create a second instance.
     */
    public function __clone(): void
    {
        throw new RuntimeException('DatabaseConnectionSingleton cannot be cloned.');
    }

    /**
     * Prevent unserialization for the same reason as clone prevention.
     */
    public function __wakeup(): void
    {
        throw new RuntimeException('DatabaseConnectionSingleton cannot be unserialized.');
    }

    /**
     * Build the PDO connection using the active Laravel database configuration.
     * We keep this logic inside the Singleton so the single instance fully
     * controls how the connection is created and reused.
     */
    private function createConnectionFromConfiguration(): PDO
    {
        $defaultConnection = config('database.default');
        $connectionConfig = config("database.connections.{$defaultConnection}");

        if (! is_array($connectionConfig) || ! isset($connectionConfig['driver'])) {
            throw new RuntimeException('Database connection configuration could not be loaded.');
        }

        return match ($connectionConfig['driver']) {
            'sqlite' => $this->createSqliteConnection($connectionConfig),
            'mysql', 'mariadb' => $this->createMysqlConnection($connectionConfig),
            default => throw new RuntimeException("Driver [{$connectionConfig['driver']}] is not supported by DatabaseConnectionSingleton."),
        };
    }

    private function createSqliteConnection(array $connectionConfig): PDO
    {
        $database = $connectionConfig['database'] ?? database_path('database.sqlite');
        $dsn = 'sqlite:'.$database;

        return $this->newConfiguredPdo($dsn);
    }

    private function createMysqlConnection(array $connectionConfig): PDO
    {
        $host = $connectionConfig['host'] ?? '127.0.0.1';
        $port = $connectionConfig['port'] ?? '3306';
        $database = $connectionConfig['database'] ?? '';
        $charset = $connectionConfig['charset'] ?? 'utf8mb4';
        $username = $connectionConfig['username'] ?? '';
        $password = $connectionConfig['password'] ?? '';

        $dsn = "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";

        return $this->newConfiguredPdo($dsn, $username, $password);
    }

    private function newConfiguredPdo(string $dsn, ?string $username = null, ?string $password = null): PDO
    {
        try {
            return new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            throw new RuntimeException('DatabaseConnectionSingleton failed to create the PDO connection.', 0, $exception);
        }
    }
}
