<?php namespace MusicCollection\Responses;

use MusicCollection\Routing\Router;
use MusicCollection\Translation\Translator;
use MusicCollection\Utils\Logger;
use MusicCollection\Utils\Session;

/**
 * Handle the View logic of rendering (child) templates
 * @package MusicCollection\Responses
 */
class View extends Response
{
    protected string $masterTemplate = 'master';

    /**
     * Template constructor.
     *
     * @param Router $router
     */
    public function __construct(protected Router $router)
    {
    }

    public function __toString(): string
    {
        return $this->template($this->data, $this->masterTemplate);
    }

    /**
     * Use output buffers to capture template data from require statement and store in data
     *
     * @param string $path
     * @param array<string, mixed> $data
     * @return View
     * @throws \RuntimeException
     */
    public function render(string $path, array $data = []): self
    {
        if (array_key_exists('content', $data)) {
            throw new \RuntimeException('Key "content" is forbidden as template variable');
        }
        $this->data['content'] = $this->template($data, $path);
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    /**
     * @param array<string, mixed> $data
     * @param string $templatePath
     * @return string
     * @throws \RuntimeException
     */
    private function template(array $data, string $templatePath): string
    {
        $templatePath = str_replace('.', '/', $templatePath);
        if (!file_exists(INCLUDES_PATH . 'templates/' . $templatePath . '.php')) {
            throw new \RuntimeException("Template $templatePath does not exist");
        }

        $this->data = $data;
        extract($data);
        ob_start();

        try {
            //Make variables (including callables) available for templates
            $route = [$this->router, 'getFullPathByName'];
            $t = [Translator::class, '__'];
            $yield = [$this, 'getChildTemplate'];
            $currentLanguage = Session::i()->get('language');
            require_once INCLUDES_PATH . 'templates/' . $templatePath . '.php';
        } catch (\Exception $e) {
            Logger::error($e);
            ob_get_clean();

            throw new \RuntimeException("Something went wrong in the template '$templatePath'");
        }

        return ob_get_clean();
    }

    /**
     * @param string $path
     * @return string
     */
    private function getChildTemplate(string $path): string
    {
        return $this->template($this->data, $path);
    }
}
