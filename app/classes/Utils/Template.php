<?php namespace MusicCollection\Utils;

use MusicCollection\Routing\Router;
use MusicCollection\Translation\Translator;

/**
 * Class Template
 * @package MusicCollection\Utils
 */
class Template
{
    /**
     * @var array<string, mixed>
     */
    private array $vars = [];

    /**
     * Template constructor.
     *
     * @param Router $router
     */
    public function __construct(protected Router $router)
    {
    }

    /**
     * @param array<string, mixed> $vars
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
        //TODO: See if this can be fixed in a nicer way..
        $this->vars['currentLanguage'] = Session::i()->get('language');
        $this->vars['languages'] = LANGUAGES;
        extract($vars);
        ob_start();

        try {
            //Make functions available for templates
            $route = [$this->router, 'getFullPathByName'];
            $t = [Translator::class, '__'];
            $yield = [$this, 'getChildTemplate'];
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
        return $this->render($this->vars, $path);
    }
}
