<?php namespace MusicCollection\Utils;

/**
 * Class Session
 * @package MusicCollection\Utils
 */
class Session implements Singleton
{
    /**
     * @var array<string, mixed>
     */
    private array $data;

    /**
     * @var Session|null
     */
    private static ?Session $instance = null;

    /**
     * Initialize object
     */
    private function __construct()
    {
        $this->data = $_SESSION;
    }

    /**
     * @return Session
     */
    public static function i(): Session
    {
        if (self::$instance === null) {
            self::$instance = new Session();
        }

        return self::$instance;
    }

    /**
     * Check if a var exists in the current session state
     *
     * @param string $key
     * @return bool
     */
    public function keyExists(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Retrieve a var from the session array
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Set a var from in the global session
     *
     * @param string $key
     * @param mixed $data
     */
    public function set(string $key, mixed $data): void
    {
        $this->data[$key] = $data;
        $_SESSION[$key] = $this->data[$key];
    }

    /**
     * Delete a var from the global session
     *
     * @param string $key
     */
    public function delete(string $key): void
    {
        unset($this->data[$key], $_SESSION[$key]);
    }

    /**
     * Delete all vars from the global session
     */
    public function deleteAll(): void
    {
        $this->data = [];
        session_unset();
    }

    /**
     * Destroy the session
     */
    public function destroy(): void
    {
        session_destroy();
    }
}
