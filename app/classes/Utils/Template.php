<?php namespace MusicCollection\Utils;

use MusicCollection\Routing\Router;
use MusicCollection\Translation\Translator;

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
            $t = [$this, 'getString'];
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

    /**
     * A simple template function to prevent breaking errors. If a key doesn't exist, it will just show the provided key
     *
     * @param string $key
     * @return string
     */
    private function getString(string $key): string
    {
        try {
            $transLateKeys = explode('.', $key);
            $value = $this->t->{array_shift($transLateKeys)};

            foreach ($transLateKeys as $transLateKey) {
                $value = $value->{$transLateKey};
            }

            if (is_string($value) === false) {
                throw new \Exception();
            }
        } catch (\Exception $e) {
            return $key;
        }

        return $value;
    }
}
