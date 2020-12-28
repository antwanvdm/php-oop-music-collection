<?php namespace System\Handlers;

use System\Translation\Translator;
use System\Utils\Session;
use System\Utils\Logger;
use System\Routing\Router;

/**
 * Class BaseHandler
 * @package System\Handlers
 *
 * Dynamic properties to enable auto complete:
 * @property Session $session
 * @property Logger $logger
 * @property Router $router
 * @property Translator $t
 */
abstract class BaseHandler
{
    protected string $templatePath;
    protected array $properties = [];
    private array $data = [];
    protected array $errors = [];

    /**
     * BaseHandler constructor.
     *
     * @param Session $session
     * @param Logger $logger
     * @param Router $router
     */
    public function __construct(Session $session, Logger $logger, Router $router, Translator $t)
    {
        $this->session = $session;
        $this->logger = $logger;
        $this->router = $router;
        $this->t = $t;

        if (method_exists($this, "initialize")) {
            $this->initialize();
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value)
    {
        $this->properties[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->properties[$name];
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return self
     * @throws \Exception
     */
    public function __call(string $name, array $arguments): self
    {
        //Use the dynamic action name to set the template path
        $className = (new \ReflectionClass($this))->getShortName();
        $this->templatePath = str_replace('handler', '', strtolower($className)) . '/' . $name;

        //Actual __call magic to call child protected method if it exists
        if (method_exists($this, $name)) {
            call_user_func_array([$this, $name], $arguments);
        } else {
            throw new \Exception("Route does not exist or invalid function ($name) was called");
        }

        return $this;
    }

    /**
     * Use output buffers to capture template data from require statement and store in data
     *
     * @param array $vars
     * @throws \RuntimeException
     */
    protected function renderTemplate(array $vars = []): void
    {
        if (array_key_exists('content', $vars)) {
            throw new \RuntimeException('Key "content" is forbidden as template variable');
        }
        extract($vars);
        ob_start();
        try {
            $route = [$this->router, 'getFullPathByName'];
            /** @noinspection PhpIncludeInspection */
            require_once INCLUDES_PATH . 'templates/' . $this->templatePath . '.php';
        } catch (\Exception $e) {
            $this->logger->error($e);
            ob_get_clean();
            throw new \RuntimeException('Something went wrong in the template');
        }
        $this->data['content'] = ob_get_clean();
        $this->data = array_merge($this->data, $vars);
    }

    /**
     * Return the rendered master template HTML
     *
     * @return string
     * @noinspection PhpUnused
     */
    public function getHTML(): string
    {
        extract($this->data);
        ob_start();
        require_once INCLUDES_PATH . 'templates/master.php';
        return ob_get_clean();
    }
}
