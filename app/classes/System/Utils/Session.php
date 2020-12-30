<?php namespace System\Utils;

/**
 * Class Session
 * @package System\Utils
 */
class Session
{
    private array $data;

    /**
     * Initialize object
     */
    public function __construct()
    {
        $this->data = $_SESSION;
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
     * @return string|int|array|object|bool|mixed
     */
    public function get(string $key)
    {
//        if (!isset($this->data[$key])) {
//            return false;
//        }
//
//        try {
//            $data = unserialize($this->data[$key]);
//        } catch (\Exception $e) {
//            $data = $this->data[$key];
//        }
//
//        return $data;
        return $this->data[$key] ?? false;
    }

    /**
     * Set a var from in the global session
     *
     * @param string $key
     * @param mixed $data
     */
    public function set(string $key, $data): void
    {
//        $this->removeNonSerializable($data);
        $this->data[$key] = $data;
        $_SESSION[$key] = $this->data[$key];
    }

    /**
     * @param $data
     */
//    private function removeNonSerializable(&$data)
//    {
//        if (is_object($data)) {
//            foreach ($data as $dataKey => $dataValue) {
//                try {
//                    serialize($data->$dataKey);
//                } catch (\Exception $e) {
//                    if (is_object($data->$dataKey) && count(get_object_vars($data->$dataKey)) === 0) {
//                        unset($data->$dataKey);
//                    } elseif (is_object($data->$dataKey)) {
//                        $this->removeNonSerializable($data->$dataKey);
//                    } elseif (is_array($data->$dataKey)) {
//                        foreach ($data->$dataKey as $obj) {
//                            if (is_object($obj)) {
//                                $this->removeNonSerializable($obj);
//                            }
//                        }
//                    }
//                }
//            }
//        }
//    }

    /**
     * Delete a var from the global session
     *
     * @param $key
     */
    public function delete(string $key): void
    {
        unset($this->data[$key], $_SESSION[$key]);
    }

    /**
     * Destroy the session
     */
    public function destroy(): void
    {
        session_destroy();
    }
}
