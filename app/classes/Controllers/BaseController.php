<?php namespace MusicCollection\Controllers;

use MusicCollection\Routing\Router;
use MusicCollection\Utils\Request;
use MusicCollection\Utils\Session;
use MusicCollection\Utils\Template;

/**
 * Class BaseController
 * @package MusicCollection\Controllers
 */
abstract class BaseController
{
    protected string $templatePath;
    /**
     * @var array<string, mixed>
     */
    private array $data = [];
    /**
     * @var string[]
     */
    protected array $errors = [];

    /**
     * BaseController constructor.
     *
     * @param Session $session
     * @param Router $router
     * @param Template $template
     * @param Request $request
     */
    public function __construct(
        protected Session $session,
        protected Router $router,
        protected Template $template,
        protected Request $request
    ) {
        if (method_exists($this, 'initialize')) {
            $this->initialize();
        }
    }

    /**
     * @param string $name
     * @param array<int, mixed> $arguments
     * @return self
     * @throws \Exception
     */
    public function __call(string $name, array $arguments): self
    {
        //Use the dynamic action name to set the template path
        $className = (new \ReflectionClass($this))->getShortName();
        $this->templatePath = str_replace('controller', '', strtolower($className)) . '/' . $name;

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
     * @param array<string, mixed> $vars
     * @throws \RuntimeException
     */
    protected function renderTemplate(array $vars = []): void
    {
        if (array_key_exists('content', $vars)) {
            throw new \RuntimeException('Key "content" is forbidden as template variable');
        }
        $this->data['content'] = $this->template->render($vars, $this->templatePath);
        $this->data = array_merge($this->data, $vars);
    }

    /**
     * @param array<int|string, mixed> $vars
     */
    protected function setJSON(array $vars = []): void
    {
        $this->data = $vars;
    }

    /**
     * Get response based on content type header, always fallback to HTML as the default
     *
     * @return string
     */
    public function getResponse(): string
    {
        return match ($this->request->requestedContentType()) {
            'application/json' => $this->getJSON(),
            default => $this->getHTML(),
        };
    }

    /**
     * Return the rendered master template HTML
     *
     * @return string
     */
    private function getHTML(): string
    {
        return $this->template->render($this->data, 'master');
    }

    /**
     * @return string
     */
    private function getJSON(): string
    {
        header('Content-Type: application/json');

        return json_encode($this->data);
    }
}
