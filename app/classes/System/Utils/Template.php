<?php namespace System\Utils;

use System\Routing\Router;
use System\Translation\Translator;

/**
 * Class Template
 * @package System\Utils
 */
class Template
{
    private Translator $t;
    private Router $router;
    private Logger $logger;
    private array $vars = [];

    /**
     * Template constructor.
     *
     * @param Translator $t
     * @param Router $router
     * @param Logger $logger
     */
    public function __construct(Translator $t, Router $router, Logger $logger)
    {
        $this->t = $t;
        $this->router = $router;
        $this->logger = $logger;
    }

    /**
     * @param array $vars
     * @param string $templatePath
     * @return string
     * @throws \RuntimeException
     */
    public function render(array $vars, string $templatePath): string
    {
        if (!file_exists(INCLUDES_PATH . 'templates/' . $templatePath . '.php')) {
            throw new \RuntimeException("Template $templatePath does not exist");
        }

        $this->vars = $vars;
        extract($vars);
        ob_start();

        try {
            //Make functions available for templates
            $route = [$this->router, 'getFullPathByName'];
            $yield = [$this, 'getChildTemplate'];
            /** @noinspection PhpIncludeInspection */
            require_once INCLUDES_PATH . 'templates/' . $templatePath . '.php';
        } catch (\Exception $e) {
            $this->logger->error($e);
            ob_get_clean();

            throw new \RuntimeException('Something went wrong in the template');
        }

        return ob_get_clean();
    }

    /**
     * @param string $path
     * @return string
     */
    private function getChildTemplate(string $path): string
    {
        return $this->render($this->vars, $path);
    }
}
