<?php namespace MusicCollection\Handlers\Utils;

use MusicCollection\Utils\URL;

/**
 * Wrapper for super globals (except Session)
 * @package MusicCollection\Handlers\Utils
 */
class Request
{
    /**
     * @var array<string, mixed>
     */
    private array $post;
    /**
     * @var array<string, string>
     */
    private array $get;
    /**
     * @var array<string, array<string, string>>
     */
    private array $files;
    /**
     * @var array<string, string>
     */
    private array $server;

    public function __construct()
    {
        $this->post = $_POST;
        $this->get = $_GET;
        $this->files = $_FILES;
        $this->server = $_SERVER;
    }

    /**
     * @param string $key
     * @return array<string, int|float|string>|int|float|string
     */
    public function input(string $key): array|int|float|string
    {
        //I simply hacked a checkbox/select situation :(
        if (!$this->hasInput($key)) {
            return [];
        }

        //And if 1 or more items values are selected
        $value = $this->post[$key];
        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = htmlentities($val);
            }

            return $value;
        }

        //Or just a single non checkbox field
        return htmlentities($value);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasInput(string $key): bool
    {
        return array_key_exists($key, $this->post);
    }

    /**
     * @param string $key
     * @param string $default
     * @return string
     */
    public function query(string $key, string $default = ''): string
    {
        return $this->hasQuery($key) ? htmlentities($this->get[$key]) : $default;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasQuery(string $key): bool
    {
        return array_key_exists($key, $this->get);
    }

    /**
     * @param string $key
     * @return string[]|null
     */
    public function file(string $key): array|null
    {
        return $this->files[$key] ?? null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasFile(string $key): bool
    {
        return array_key_exists($key, $this->files);
    }

    /**
     * @return string
     */
    public function currentPath(): string
    {
        return URL::getCurrentPath($this->get['_url']);
    }

    /**
     * @return string
     */
    public function previousPath(): string
    {
        return htmlentities($this->server['HTTP_REFERER']);
    }

    /**
     * @return string|null
     */
    public function requestedContentType(): string|null
    {
        return isset($this->server['CONTENT_TYPE']) ? htmlentities($this->server['CONTENT_TYPE']) : null;
    }

    /**
     * @return string
     */
    public function requestedMethod(): string
    {
        return htmlentities($this->server['REQUEST_METHOD']);
    }
}
