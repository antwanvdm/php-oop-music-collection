<?php namespace MusicCollection\Routing;

/**
 * Class Route
 * @package MusicCollection\Routing
 */
class Route
{
    /**
     * @var string[]
     */
    public array $params = [];
    public ?string $name = null;

    /**
     * Route constructor.
     *
     * @param string $method
     * @param string $path
     * @param string $className
     * @param string $action
     * @throws \Exception
     */
    public function __construct(
        public string $method,
        public string $path,
        public string $className,
        public string $action
    ) {
        if (preg_match_all("/\{([a-zA-Z0-9]+)\}/", $path, $matches)) {
            foreach ($matches[1] as $match) {
                if (in_array($match, $this->params)) {
                    throw new \Exception("Route for '$path' cannot have the same parameter name ($match) more than once");
                }
                $this->params[] = $match;
            }
        }
    }

    /**
     * @param string $name
     */
    public function name(string $name): void
    {
        $this->name = $name;
    }
}
