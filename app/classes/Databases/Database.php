<?php namespace MusicCollection\Databases;

use MusicCollection\Utils\Singleton;

/**
 * Class Database
 * @package MusicCollection\Databases
 */
class Database extends \PDO implements Singleton
{
    private string $host = DB_HOST;
    private string $username = DB_USER;
    private string $password = DB_PASS;
    private string $database = DB_NAME;

    private static ?Database $instance = null;

    /**
     * Database constructor. (private to make the Singleton pattern active)
     *
     * @throws \Exception
     */
    private function __construct()
    {
        try {
            parent::__construct("mysql:dbname=$this->database;host=$this->host", $this->username, $this->password);
            $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \Exception('DB Connection failed: ' . $e->getMessage());
        }
    }

    /**
     * @return Database
     * @throws \Exception
     */
    public static function i(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }
}
